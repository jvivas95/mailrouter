{{-- resources/views/emails/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main --}}
    <div class="flex-1 ml-60 flex flex-col">

        {{-- Header --}}
        @include('components.header')

        {{-- Stats --}}
        @include('components.emails-stats')

        {{-- Grid principal --}}

    </div>
</div>

@push('scripts')
    @vite([
        'resources/js/emails.js'
    ])
@endpush
