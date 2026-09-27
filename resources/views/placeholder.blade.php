@extends('layouts.app')

@section('title', 'Coming Soon')

@section('content')
<div class="text-center py-20">
    <i class="bi bi-tools text-5xl text-blue-500"></i>
    <h2 class="text-2xl font-bold mt-4 text-slate-800">Coming Soon</h2>
    <p class="text-slate-500 mt-1">This module is under construction.</p>
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 mt-6 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>
@endsection