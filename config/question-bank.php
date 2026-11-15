<?php

return [
    'route' => [
        'prefix' => 'backend',
        'name_prefix' => 'admin.',
        // Default middleware tanpa admin.module agar package tetap independen.
        // Host app yang memakai spinotek-lms-admin-permissions dapat menambahkan 'admin.module:courses' di config publish.
        'middleware' => ['web', 'auth'],
    ],

    'models' => [
        'category' => \App\Models\Category::class,
        'user' => \App\Models\User::class,
        'course' => \App\Models\Course::class,
        'assessment' => \App\Models\Assessment::class,
    ],

    // Opsional: pertahankan morph class lama jika migrasi dari App\Models\Question yang sudah punya media.
    'question_morph_class' => null,

    'per_page' => 12,
];
