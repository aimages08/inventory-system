@extends('layouts.app')

@section('title', 'Expense Categories')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Expense Categories</h1>
        <p class="text-sm text-slate-500">Group your expenses</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('expenses.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">← Expenses</a>
        <a href="{{ route('expense-categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"><i class="bi bi-plus-lg"></i> Add Category</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Expenses</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($categories as $cat)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $cat->name }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ Str::limit($cat->description, 60) ?: '—' }}</td>
                    <td class="px-4 py-3">{{ $cat->expenses_count }}</td>
                    <td class="px-4 py-3">
                        @if($cat->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('expense-categories.edit', $cat) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded inline-block"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('expense-categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-slate-400">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>

@endsection