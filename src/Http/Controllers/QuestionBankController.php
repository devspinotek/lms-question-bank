<?php

namespace SpinotekLms\QuestionBank\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use SpinotekLms\QuestionBank\Models\QuestionBank;

class QuestionBankController extends Controller
{
    public function index(Request $request)
    {
        $questionBanks = QuestionBank::query()
            ->with(['category'])
            ->withCount('questions')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate((int) config('question-bank.per_page', 12))
            ->withQueryString();

        $categories = $this->getCategories();

        return view('question-bank::index', compact('questionBanks', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateBankRequest($request);
        $data['created_by'] = auth()->id();

        $bank = QuestionBank::create($data);

        if (function_exists('notyf')) {
            notyf('Bank soal berhasil dibuat');
        }

        return redirect()->route('admin.question-banks.show', $bank);
    }

    public function show(QuestionBank $questionBank)
    {
        $questionBank->load([
            'category',
            'questions.options',
            'questions.media',
        ]);

        $categories = $this->getCategories();

        return view('question-bank::show', compact('questionBank', 'categories'));
    }

    public function update(Request $request, QuestionBank $questionBank)
    {
        $data = $this->validateBankRequest($request);
        $questionBank->update($data);

        if (function_exists('notyf')) {
            notyf('Bank soal berhasil diperbarui');
        }

        return back();
    }

    public function destroy(QuestionBank $questionBank)
    {
        $questionBank->delete();

        if (function_exists('notyf')) {
            notyf('Bank soal berhasil dihapus');
        }

        return redirect()->route('admin.question-banks.index');
    }

    private function validateBankRequest(Request $request): array
    {
        $categoryRule = Schema::hasTable('categories')
            ? 'nullable|exists:categories,id'
            : 'nullable|integer';

        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => $categoryRule,
        ]);
    }

    private function getCategories(): Collection
    {
        $categoryModel = config('question-bank.models.category');

        if ($categoryModel && class_exists($categoryModel)) {
            return $categoryModel::orderBy('name')->get();
        }

        return collect();
    }
}

