<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Story::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'stories' => $stories
        ]);
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoryRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        $story = Story::create($validated);
        
        return response()->json([
            'message' => 'Story created successfully',
            'story' => $story
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $story = Story::with('chapters')->findOrFail($id);
        
        // Check if story is published or user is the owner
        if (!$story->is_published && $story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view this story'
            ], 403);
        }
        
        return response()->json([
            'story' => $story
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoryRequest $request, string $id)
    {
        $story = Story::findOrFail($id);
        
        // Check if user is the owner
        if ($story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update this story'
            ], 403);
        }
        
        $story->update($request->validated());
        
        return response()->json([
            'message' => 'Story updated successfully',
            'story' => $story
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $story = Story::findOrFail($id);
        
        // Check if user is the owner
        if ($story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this story'
            ], 403);
        }
        
        $story->delete();
        
        return response()->json([
            'message' => 'Story deleted successfully'
        ]);
    }
}