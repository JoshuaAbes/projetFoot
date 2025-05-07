<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use Illuminate\Http\JsonResponse;

class ChoiceController extends Controller
{
    public function getChoices(): JsonResponse
    {
        $choices = Choice::with(['chapter', 'nextChapter'])->get();
        return response()->json($choices);
    }

    public function getChoice(Choice $choice): JsonResponse
    {
        $choice->load(['chapter', 'nextChapter']);
        return response()->json($choice);
    }

    public function createChoice(StoreChoiceRequest $request): JsonResponse
    {
        $choice = Choice::create($request->validated());
        return response()->json($choice, 201);
    }

    public function updateChoice(UpdateChoiceRequest $request, Choice $choice): JsonResponse
    {
        $choice->update($request->validated());
        return response()->json($choice);
    }

    public function deleteChoice(Choice $choice): JsonResponse
    {
        $choice->delete();
        return response()->json(['message' => 'Choice deleted successfully']);
    }
}
