<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use Illuminate\Http\JsonResponse;

class StoryController extends Controller
{
    public function getStories(): JsonResponse
    {
        $stories = Story::all()->map(function ($story) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'summary' => $story->summary,
                'author' => $story->author,
                'cover' => $story->cover ?? null,
            ];
        });

        return response()->json($stories);
    }

    public function getStory(Story $story): JsonResponse
    {
        $story->load('chapters.choices');
        return response()->json($story);
    }

    public function createStory(StoreStoryRequest $request): JsonResponse
    {
        $story = Story::create($request->validated());
        return response()->json($story, 201);
    }

    public function updateStory(UpdateStoryRequest $request, Story $story): JsonResponse
    {
        $story->update($request->validated());
        return response()->json($story);
    }

    public function deleteStory(Story $story): JsonResponse
    {
        $story->delete();
        return response()->json(['message' => 'Story deleted successfully']);
    }

    public function getFirstChapter(Story $story): JsonResponse
    {
        $chapter = $story->chapters()
            ->with('choices')
            ->orderBy('chapter_number')
            ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Aucun chapitre trouvé'], 404);
        }

        return response()->json([
            'chapter' => [
                'id' => $chapter->id,
                'content' => $chapter->content,
                'image' => $chapter->image,
            ],
            'choices' => $chapter->choices->map(fn($choice) => [
                'id' => $choice->id,
                'text' => $choice->text,
                'next_chapter_id' => $choice->next_chapter_id,
            ])
        ]);
    }
}
