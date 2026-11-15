<x-backend-layout>
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <a href="{{ !empty($attachCourseId) ? route('admin.courses.tests.index', $attachCourseId) : route('admin.question-banks.show', $questionBank) }}"
                class="text-decoration-none text-muted small d-inline-block mb-1">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold text-dark mb-1">Tambah Soal Baru</h4>
            <p class="text-muted text-sm mb-0">Bank Soal: <strong>{{ $questionBank->title }}</strong></p>
        </div>
    </div>

    <x-card>
        <div class="card-body p-4">
            <x-bs5-error-list />
            <x-form :action="route('admin.question-banks.questions.store', $questionBank)" enctype="multipart/form-data">
                @include('question-bank::questions._form')

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ !empty($attachCourseId) ? route('admin.courses.tests.index', $attachCourseId) : route('admin.question-banks.show', $questionBank) }}"
                        class="btn btn-light">Batal</a>
                    <x-button.submit>Simpan Soal</x-button.submit>
                </div>
            </x-form>
        </div>
    </x-card>
</x-backend-layout>

