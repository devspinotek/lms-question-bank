<?php

namespace SpinotekLms\QuestionBank\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SpinotekLms\QuestionBank\Models\Question;
use SpinotekLms\QuestionBank\Models\QuestionBank;

class QuestionService
{
    public static function validateQuestionRequest(Request $request): array
    {
        return $request->validate([
            'type' => 'nullable|in:' . implode(',', array_keys(Question::TYPES)),
            'question_text' => 'required|string',
            'explanation' => 'nullable|string',
            'question_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_image' => 'nullable|boolean',
            'options' => 'required|array|size:4',
            'options.a' => 'required|string',
            'options.b' => 'required|string',
            'options.c' => 'required|string',
            'options.d' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
        ]);
    }

    public static function createQuestion(QuestionBank $bank, array $data, Request $request): Question
    {
        return DB::transaction(function () use ($bank, $data, $request) {
            $nextOrder = ((int) $bank->questions()->max('order')) + 1;

            $question = $bank->questions()->create([
                'type' => $data['type'] ?? Question::TYPE_MULTIPLE_CHOICE,
                'question_text' => $data['question_text'],
                'explanation' => $data['explanation'] ?? null,
                'order' => $nextOrder,
                'is_active' => true,
            ]);

            self::syncOptions($question, $data['options'], $data['correct_option']);

            if ($request->hasFile('question_image')) {
                $question->addMediaFromRequest('question_image')->toMediaCollection('question_image');
            }

            return $question;
        });
    }

    public static function updateQuestion(Question $question, array $data, Request $request): Question
    {
        return DB::transaction(function () use ($question, $data, $request) {
            $question->update([
                'type' => $data['type'] ?? $question->type,
                'question_text' => $data['question_text'],
                'explanation' => $data['explanation'] ?? null,
            ]);

            $question->options()->delete();
            self::syncOptions($question, $data['options'], $data['correct_option']);

            if ($request->boolean('remove_image')) {
                $question->clearMediaCollection('question_image');
            }

            if ($request->hasFile('question_image')) {
                $question->addMediaFromRequest('question_image')->toMediaCollection('question_image');
            }

            return $question;
        });
    }

    private static function syncOptions(Question $question, array $options, string $correctKey): void
    {
        $labels = ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'];
        $order = 1;

        foreach ($labels as $key => $label) {
            $question->options()->create([
                'label' => $label,
                'option_text' => $options[$key],
                'is_correct' => $key === $correctKey,
                'order' => $order++,
            ]);
        }
    }
}

