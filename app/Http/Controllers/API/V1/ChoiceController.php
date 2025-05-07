<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChoiceRequest;
use App\Models\Chapter;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChoiceController extends Controller
{
    /**
     * Display a listing of choices for a chapter.
     */
    public function index($chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);
        
        // Check if user is authorized to view this chapter
        if (!$chapter->story->is_published && $chapter->story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view choices for this chapter'
            ], 403);
        }
        
        $choices = $chapter->choices()->with('toChapter')->get();
        
        return response()->json([
            'choices' => $choices
        ]);
    }

    /**
     * Store a newly created choice in storage.
     */
    public function store(ChoiceRequest $request)
    {
        $validated = $request->validated();
        
        // Make sure from_chapter and to_chapter are not the same
        if ($validated['from_chapter_id'] === $validated['to_chapter_id']) {
            return response()->json([
                'message' => 'From chapter and to chapter cannot be the same'
            ], 422);
        }
        
        $choice = Choice::create($validated);
        
        return response()->json([
            'message' => 'Choice created successfully',
            'choice' => $choice
        ], 201);
    }

    /**
     * Display the specified choice.
     */
    public function show($id)
    {
        $choice = Choice::with(['fromChapter', 'toChapter'])->findOrFail($id);
        
        // Check if user is authorized to view this choice
        if (!$choice->fromChapter->story->is_published && 
            $choice->fromChapter->story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view this choice'
            ], 403);
        }
        
        return response()->json([
            'choice' => $choice
        ]);
    }

    /**
     * Update the specified choice in storage.
     */
    public function update(ChoiceRequest $request, $id)
    {
        $choice = Choice::findOrFail($id);
        
        // Check if user is authorized to update this choice
        if ($choice->fromChapter->story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update this choice'
            ], 403);
        }
        
        $validated = $request->validated();
        
        // Make sure from_chapter and to_chapter are not the same
        if ($validated['from_chapter_id'] === $validated['to_chapter_id']) {
            return response()->json([
                'message' => 'From chapter and to chapter cannot be the same'
            ], 422);
        }
        
        $choice->update($validated);
        
        return response()->json([
            'message' => 'Choice updated successfully',
            'choice' => $choice
        ]);
    }

    /**
     * Remove the specified choice from storage.
     */
    public function destroy($id)
    {
        $choice = Choice::findOrFail($id);
        
        // Check if user is authorized to delete this choice
        if ($choice->fromChapter->story->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this choice'
            ], 403);
        }
        
        $choice->delete();
        
        return response()->json([
            'message' => 'Choice deleted successfully'
        ]);
    }
}