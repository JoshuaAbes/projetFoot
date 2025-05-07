<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressRequest;
use App\Models\Progress;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Get all stories with progress for the authenticated user.
     */
    public function index()
    {
        $progress = Auth::user()->progress()
            ->with(['story', 'currentChapter'])
            ->get();
            
        return response()->json([
            'progress' => $progress
        ]);
    }

    /**
     * Store or update progress for a story.
     */
    public function store(ProgressRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        // Check if progress already exists for this story and user
        $progress = Progress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'story_id' => $validated['story_id']
            ],
            [
                'current_chapter_id' => $validated['current_chapter_id'],
                'visited_chapters' => $validated['visited_chapters'] ?? []
            ]
        );
        
        return response()->json([
            'message' => 'Progress saved successfully',
            'progress' => $progress
        ], 201);
    }

    /**
     * Get progress for a specific story.
     */
    public function show($storyId)
    {
        $story = Story::findOrFail($storyId);
        
        $progress = Progress::where('user_id', Auth::id())
            ->where('story_id', $storyId)
            ->with('currentChapter')
            ->first();
            
        if (!$progress) {
            // If no progress exists, return the starting chapter of the story
            $startingChapter = $story->chapters()
                ->where('is_starting', true)
                ->first();
                
            if (!$startingChapter) {
                return response()->json([
                    'message' => 'This story has no starting chapter'
                ], 404);
            }
            
            // Create a new progress record
            $progress = Progress::create([
                'user_id' => Auth::id(),
                'story_id' => $storyId,
                'current_chapter_id' => $startingChapter->id,
                'visited_chapters' => [$startingChapter->id]
            ]);
            
            $progress->load('currentChapter');
        }
        
        return response()->json([
            'progress' => $progress
        ]);
    }

    /**
     * Delete progress for a specific story.
     */
    public function destroy($storyId)
    {
        $deleted = Progress::where('user_id', Auth::id())
            ->where('story_id', $storyId)
            ->delete();
            
        if (!$deleted) {
            return response()->json([
                'message' => 'No progress found for this story'
            ], 404);
        }
        
        return response()->json([
            'message' => 'Progress deleted successfully'
        ]);
    }
}