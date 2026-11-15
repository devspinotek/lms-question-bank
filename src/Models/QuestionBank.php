<?php

namespace SpinotekLms\QuestionBank\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;

    protected $table = 'question_banks';

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'question_bank_id')->orderBy('order')->orderBy('id');
    }

    public function category()
    {
        $categoryModel = config('question-bank.models.category', 'App\\Models\\Category');

        return $this->belongsTo($categoryModel, 'category_id');
    }

    public function creator()
    {
        $userModel = config('question-bank.models.user', 'App\\Models\\User');

        return $this->belongsTo($userModel, 'created_by');
    }
}

