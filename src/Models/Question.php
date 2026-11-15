<?php

namespace SpinotekLms\QuestionBank\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Question extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'questions';

    public const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    public const TYPE_TRUE_FALSE = 'true_false';
    public const TYPE_MULTIPLE_ANSWER = 'multiple_answer';
    public const TYPE_ESSAY = 'essay';

    public const TYPES = [
        self::TYPE_MULTIPLE_CHOICE => 'Pilihan Ganda',
    ];

    protected $fillable = [
        'question_bank_id',
        'type',
        'question_text',
        'explanation',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getMorphClass()
    {
        return config('question-bank.question_morph_class') ?: parent::getMorphClass();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('question_image')->singleFile();
    }

    public function getImageUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('question_image');

        return $url !== '' ? $url : null;
    }

    public function bank()
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('order')->orderBy('id');
    }

    public function assessments()
    {
        $assessmentModel = config('question-bank.models.assessment', 'App\\Models\\Assessment');

        return $this->belongsToMany($assessmentModel, 'assessment_questions', 'question_id', 'assessment_id')
            ->withPivot(['order', 'points'])
            ->withTimestamps();
    }
}

