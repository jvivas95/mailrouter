{{-- resources/views/emails/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main --}}
    <div class="flex flex-1 flex-col lg:ml-60">

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
