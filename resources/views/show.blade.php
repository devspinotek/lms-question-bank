<x-backend-layout>
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <a href="{{ route('admin.question-banks.index') }}" class="text-decoration-none text-muted small d-inline-block mb-1">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Bank Soal
            </a>
            <h4 class="fw-bold text-dark mb-1">{{ $questionBank->title }}</h4>
            <p class="text-muted text-sm mb-0">{{ $questionBank->description ?: 'Kelola daftar soal pilihan ganda pada bank soal ini.' }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEditBank">
                <i class="fas fa-edit me-1"></i> Edit Info
            </button>
            <a href="{{ route('admin.question-banks.questions.create', $questionBank) }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Soal
            </a>
        </div>
    </div>

    <x-bs5-error-list />

    @forelse ($questionBank->questions as $index => $question)
        <x-card class="mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-primary">Soal #{{ $index + 1 }}</span>
                        <span class="badge bg-light text-dark border">{{ \SpinotekLms\QuestionBank\Models\Question::TYPES[$question->type] ?? $question->type }}</span>
                        @if ($question->image_url)
                            <span class="badge bg-info-subtle text-info-emphasis"><i class="fas fa-image me-1"></i> Ada Gambar</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.question-banks.questions.edit', [$questionBank, $question]) }}"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.question-banks.questions.destroy', [$questionBank, $question]) }}" method="POST"
                            onsubmit="return confirm('Hapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if ($question->image_url)
                    <div class="mb-3">
                        <img src="{{ $question->image_url }}" alt="Gambar Soal" class="img-fluid rounded border" style="max-height: 220px;">
                    </div>
                @endif

                <div class="fw-semibold text-dark mb-3 content-styles" style="font-size: 15px;">{!! $question->question_text !!}</div>

                <div class="row g-2">
                    @foreach ($question->options as $option)
                        <div class="col-12 col-md-6">
                            <div class="p-2 px-3 rounded border {{ $option->is_correct ? 'border-success bg-success bg-opacity-10 fw-semibold text-success' : 'bg-light text-dark' }}">
                                <span class="me-1">{{ $option->label }}.</span> {{ $option->option_text }}
                                @if ($option->is_correct)
                                    <i class="fas fa-check-circle ms-1"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>
    @empty
        <x-card>
            <div class="card-body text-center py-5">
                <i class="fas fa-circle-question fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold text-dark">Belum Ada Soal</h5>
                <p class="text-muted mb-3">Tambahkan soal pilihan ganda (beserta gambar jika diperlukan) ke Bank Soal ini.</p>
                <a href="{{ route('admin.question-banks.questions.create', $questionBank) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Soal Pertama
                </a>
            </div>
        </x-card>
    @endforelse

    <!-- Modal Edit Bank Info -->
    <div class="modal fade" id="modalEditBank" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.question-banks.update', $questionBank) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Info Bank Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Bank Soal <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ $questionBank->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">- Umum / Tanpa Kategori -</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $questionBank->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $questionBank->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-backend-layout>
