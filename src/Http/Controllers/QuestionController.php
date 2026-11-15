<?php

namespace SpinotekLms\QuestionBank\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SpinotekLms\QuestionBank\Models\Question;
use SpinotekLms\QuestionBank\Models\QuestionBank;
use SpinotekLms\QuestionBank\Services\QuestionService;

class QuestionController extends Controller
{
    public function create(Request $request, QuestionBank $questionBank)
    {
        $attachCourseId = $request->query('attach_course_id');
        $attachPurpose = $request->query('attach_purpose');

        return view('question-bank::questions.create', compact(
            'questionBank',
            'attachCourseId',
            'attachPurpose'
        ));
    }

    public function store(Request $request, QuestionBank $questionBank)
    {
        $data = QuestionService::validateQuestionRequest($request);
        $question = QuestionService::createQuestion($questionBank, $data, $request);

        $attachCourseId = $request->input('attach_course_id');
        $attachPurpose = $request->input('attach_purpose');

        $courseModel = config('question-bank.models.course');
        $assessmentModel = config('question-bank.models.assessment');

        if (
            $attachCourseId
            && $courseModel
            && $assessmentModel
            && class_exists($courseModel)
            && class_exists($assessmentModel)
            && in_array($attachPurpose, [$assessmentModel::PURPOSE_PRE_TEST, $assessmentModel::PURPOSE_POST_TEST], true)
        ) {
            $course = $courseModel::find($attachCourseId);
            if ($course) {
                $assessment = $course->assessments()->firstOrCreate(
                    ['purpose' => $attachPurpose],
                    [
                        'title' => $attachPurpose === $assessmentModel::PURPOSE_PRE_TEST ? 'Pre-Test' : 'Post-Test',
                        'is_active' => false,
                        'passing_score' => 70,
                        'max_attempts' => 1,
                    ]
                );

                $nextOrder = ((int) $assessment->questions()->max('assessment_questions.order')) + 1;
                $assessment->questions()->attach($question->id, [
                    'order' => $nextOrder,
                    'points' => 1,
                ]);

                if (function_exists('notyf')) {
                    notyf('Soal berhasil dibuat dan dipasang ke ' . ($attachPurpose === $assessmentModel::PURPOSE_PRE_TEST ? 'Pre-Test' : 'Post-Test'));
                }

                return redirect()->route('admin.courses.tests.index', $course);
            }
        }

        if (function_exists('notyf')) {
            notyf('Soal berhasil ditambahkan ke Bank Soal');
        }

        return redirect()->route('admin.question-banks.show', $questionBank);
    }

    public function edit(QuestionBank $questionBank, Question $question)
    {
        abort_unless($question->question_bank_id === $questionBank->id, 404);
        $question->load(['options', 'media']);

        return view('question-bank::questions.edit', compact('questionBank', 'question'));
    }

    public function update(Request $request, QuestionBank $questionBank, Question $question)
    {
        abort_unless($question->question_bank_id === $questionBank->id, 404);

        $data = QuestionService::validateQuestionRequest($request);
        QuestionService::updateQuestion($question, $data, $request);

        if (function_exists('notyf')) {
            notyf('Soal berhasil diperbarui');
        }

        return redirect()->route('admin.question-banks.show', $questionBank);
    }

    public function destroy(QuestionBank $questionBank, Question $question)
    {
        abort_unless($question->question_bank_id === $questionBank->id, 404);

        $question->delete();

        if (function_exists('notyf')) {
            notyf('Soal berhasil dihapus');
        }

        return back();
    }
}

