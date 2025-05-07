<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    /**
     * Display a listing of the chapters for a story.
     */
    public function index($storyId)
    {
        $story = Story::findOrFail($storyId);
        
        // Check if story is published or user is the owner
        if (!$story->is_published && $story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view this story'
            ], 403);
        }
        
        $chapters = $story->chapters;
        
        return response()->json([
            'chapters' => $chapters
        ]);
    }

    /**
     * Store a newly created chapter in storage.
     */
    public function store(ChapterRequest $request, $storyId)
    {
        $story = Story::findOrFail($storyId);
        
        // Check if user is the owner of the story
        if ($story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to add chapters to this story'
            ], 403);
        }
        
        $validated = $request->validated();
        $validated['story_id'] = $storyId;
        
        // If this is the first chapter, make it the starting chapter
        if ($story->chapters()->count() === 0) {
            $validated['is_starting'] = true;
        }
        
        // If setting this chapter as starting, unset any other starting chapters
        if (isset($validated['is_starting']) && $validated['is_starting']) {
            $story->chapters()->where('is_starting', true)->update(['is_starting' => false]);
        }
        
        $chapter = Chapter::create($validated);
        
        return response()->json([
            'message' => 'Chapter created successfully',
            'chapter' => $chapter
        ], 201);
    }

    /**
     * Display the specified chapter.
     */
    public function show($storyId, $chapterId)
    {
        $story = Story::findOrFail($storyId);
        
        // Check if story is published or user is the owner
        if (!$story->is_published && $story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view this story'
            ], 403);
        }
        
        $chapter = Chapter::with('choices')->where('story_id', $storyId)
            ->findOrFail($chapterId);
        
        return response()->json([
            'chapter' => $chapter
        ]);
    }

    /**
     * Update the specified chapter in storage.
     */
    public function update(ChapterRequest $request, $storyId, $chapterId)
    {
        $story = Story::findOrFail($storyId);
        
        // Check if user is the owner of the story
        if ($story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update chapters in this story'
            ], 403);
        }
        
        $chapter = Chapter::where('story_id', $storyId)->findOrFail($chapterId);
        
        $validated = $request->validated();
        
        // If setting this chapter as starting, unset any other starting chapters
        if (isset($validated['is_starting']) && $validated['is_starting']) {
            $story->chapters()->where('is_starting', true)
                ->where('id', '!=', $chapterId)
                ->update(['is_starting' => false]);
        }
        
        $chapter->update($validated);
        
        return response()->json([
            'message' => 'Chapter updated successfully',
            'chapter' => $chapter
        ]);
    }

    /**
     * Remove the specified chapter from storage.
     */
    public function destroy($storyId, $chapterId)
    {
        $story = Story::findOrFail($storyId);
        
        // Check if user is the owner of the story
        if ($story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to delete chapters from this story'
            ], 403);
        }
        
        $chapter = Chapter::where('story_id', $storyId)->findOrFail($chapterId);
        $chapter->delete();
        
        return response()->json([
            'message' => 'Chapter deleted successfully'
        ]);
    }
}