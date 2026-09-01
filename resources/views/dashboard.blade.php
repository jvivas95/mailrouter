{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard — MailRouter')

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
    </div>{{-- /main --}}
</div>{{-- /flex --}}
@endsection

@push('scripts')
    @vite([
        'resources/js/dashboard.js'
    ])
@endpush
