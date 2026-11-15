@php
    $opts = isset($question) ? $question->options->keyBy(fn($o) => strtolower($o->label)) : collect();
    $correctOpt = isset($question) ? $question->options->firstWhere('is_correct', true) : null;
    $correctKey = old('correct_option', $correctOpt ? strtolower($correctOpt->label) : 'a');
@endphp

@if (!empty($attachCourseId) && !empty($attachPurpose))
    <input type="hidden" name="attach_course_id" value="{{ $attachCourseId }}">
    <input type="hidden" name="attach_purpose" value="{{ $attachPurpose }}">
@endif

<div class="row mb-3">
    <div class="col-lg-2">
        <x-form.input-label>Tipe Soal</x-form.input-label>
    </div>
    <div class="col-lg-10">
        <select name="type" class="form-select">
            @foreach (\SpinotekLms\QuestionBank\Models\Question::TYPES as $typeKey => $typeLabel)
                <option value="{{ $typeKey }}" {{ old('type', @$question->type ?? 'multiple_choice') === $typeKey ? 'selected' : '' }}>
                    {{ $typeLabel }}
                </option>
            @endforeach
        </select>
        <x-form.input-error name="type" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-2">
        <x-form.input-label :required="true">Pertanyaan</x-form.input-label>
    </div>
    <div class="col-lg-10">
        <x-form.textarea id="editor" name="question_text">{{ old('question_text', @$question->question_text) }}</x-form.textarea>
        <x-form.input-error name="question_text" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-2">
        <x-form.input-label>Gambar Soal (Opsional)</x-form.input-label>
    </div>
    <div class="col-lg-10">
        <input type="file" class="form-control dropify" name="question_image"
            data-default-file="{{ @$question->image_url }}">
        <x-form.input-error name="question_image" />
        <x-alert.info>Opsional. Ukuran gambar maks 2MB dengan format JPG, JPEG, PNG, atau WebP.</x-alert.info>
        @if (!empty($question?->image_url))
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeQuestionImage">
                <label class="form-check-label small text-danger" for="removeQuestionImage">
                    Hapus gambar soal saat ini
                </label>
            </div>
        @endif
    </div>
</div>

@foreach (['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
    <div class="row mb-3">
        <div class="col-lg-2">
            <x-form.input-label :required="true">Opsi {{ $label }}</x-form.input-label>
        </div>
        <div class="col-lg-10">
            <input type="text" name="options[{{ $key }}]" class="form-control"
                value="{{ old('options.' . $key, $opts->get($key)?->option_text) }}"
                placeholder="Masukkan pilihan jawaban {{ $label }}" required>
            <x-form.input-error name="options.{{ $key }}" />
        </div>
    </div>
@endforeach

<div class="row mb-3">
    <div class="col-lg-2">
        <x-form.input-label :required="true">Kunci Jawaban</x-form.input-label>
    </div>
    <div class="col-lg-10">
        <select name="correct_option" class="form-select" required>
            @foreach (['a' => 'Opsi A', 'b' => 'Opsi B', 'c' => 'Opsi C', 'd' => 'Opsi D'] as $key => $label)
                <option value="{{ $key }}" {{ $correctKey === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-form.input-error name="correct_option" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-2">
        <x-form.input-label>Pembahasan (Opsional)</x-form.input-label>
    </div>
    <div class="col-lg-10">
        <x-form.textarea id="editor-explanation" name="explanation">{{ old('explanation', @$question->explanation) }}</x-form.textarea>
        <x-form.input-error name="explanation" />
    </div>
</div>

@push('scripts')
    <x-scripts.ckeditor id="editor" />
    <x-scripts.ckeditor id="editor-explanation" />
@endpush

