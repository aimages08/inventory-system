@extends('layouts.app')
@section('title', 'Add Expense Category')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Add Expense Category</h1>
    <a href="{{ route('expense-categories.index') }}" class="text-sm text-slate-500 hover:text-slate-700"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<form method="POST" action="{{ route('expense-categories.store') }}" class="bg-white rounded-xl shadow-sm p-6">
    @csrf
    @include('expense-categories._form')
    <div class="flex gap-2 mt-6">
        <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"><i class="bi bi-check-lg me-1"></i> Save</button>
        <a href="{{ route('expense-categories.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Cancel</a>
    </div>
</form>
@endsection