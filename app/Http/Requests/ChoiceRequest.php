<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Chapter;

class ChoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        // Check if the user owns the related chapters
        $fromChapterId = $this->input('from_chapter_id');
        $fromChapter = Chapter::findOrFail($fromChapterId);
        
        return $fromChapter->story->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string|max:255',
            'from_chapter_id' => 'required|exists:chapters,id',
            'to_chapter_id' => 'required|exists:chapters,id',
        ];
    }
}