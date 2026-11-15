<?php

use Illuminate\Support\Facades\Route;
use SpinotekLms\QuestionBank\Http\Controllers\QuestionBankController;
use SpinotekLms\QuestionBank\Http\Controllers\QuestionController;

Route::resource('question-banks', QuestionBankController::class)->except(['create', 'edit']);
Route::get('/question-banks/{questionBank}/questions/create', [QuestionController::class, 'create'])->name('question-banks.questions.create');
Route::post('/question-banks/{questionBank}/questions', [QuestionController::class, 'store'])->name('question-banks.questions.store');
Route::get('/question-banks/{questionBank}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('question-banks.questions.edit');
Route::put('/question-banks/{questionBank}/questions/{question}', [QuestionController::class, 'update'])->name('question-banks.questions.update');
Route::delete('/question-banks/{questionBank}/questions/{question}', [QuestionController::class, 'destroy'])->name('question-banks.questions.destroy');

