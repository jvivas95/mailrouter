{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard — MailRouter')

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
    </div>{{-- /main --}}
</div>{{-- /flex --}}
@endsection

@push('scripts')
    @vite([
        'resources/js/dashboard.js'
    ])
@endpush
