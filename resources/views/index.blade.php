<x-backend-layout>
    <x-card>
        <x-card.header title="Bank Soal" icon="fas fa-database" iconColor="primary"
            subtitle="Kelola kumpulan soal pilihan ganda beserta gambar untuk digunakan di E-Course maupun evaluasi lainnya.">
            <x-button.refresh />
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateBank">
                <i class="fas fa-plus me-1"></i> Tambah Bank Soal
            </button>
        </x-card.header>
        <div class="card-body">
            <form>
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-4 col-lg-3">
                        <x-filter.select name="category_id">
                            <option value="">- Semua Kategori -</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-filter.select>
                    </div>
                    <div class="col-12 col-md-8 col-lg-4 ms-auto">
                        <x-filter.search placeholder="Cari judul bank soal..." />
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <x-table.rounded>
                    <thead>
                        <tr>
                            <th width="10">No</th>
                            <th width="160">Aksi</th>
                            <th>Title</th>
                            <th>Kategori</th>
                            <th>Jumlah Soal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questionBanks as $bank)
                            <tr>
                                <td>{{ increment($questionBanks, $loop) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <a href="{{ route('admin.question-banks.show', $bank) }}" class="btn btn-primary btn-sm text-nowrap">
                                            <i class="fas fa-list-check me-1"></i> Kelola Soal
                                        </a>
                                        <form action="{{ route('admin.question-banks.destroy', $bank) }}" method="POST"
                                            onsubmit="return confirm('Hapus Bank Soal ini beserta seluruh soalnya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $bank->title }}</div>
                                    @if ($bank->description)
                                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($bank->description, 100) }}</div>
                                    @endif
                                </td>
                                <td>{{ $bank->category?->name ?? 'Umum' }}</td>
                                <td>{{ $bank->questions_count }} Soal</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" align="center">
                                    -tidak ada data-
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table.rounded>
            </div>

            <x-pagination :data="$questionBanks" />
        </div>
    </x-card>

    <!-- Modal Create Bank Soal -->
    <div class="modal fade" id="modalCreateBank" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.question-banks.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Bank Soal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Bank Soal <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Sistemasi Order Apotek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori (Opsional)</label>
                        <select name="category_id" class="form-select">
                            <option value="">- Umum / Tanpa Kategori -</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Keterangan singkat bank soal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan & Kelola Soal</button>
                </div>
            </form>
        </div>
    </div>
</x-backend-layout>

