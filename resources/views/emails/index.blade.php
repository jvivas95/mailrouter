{{-- resources/views/emails/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main --}}
    <div class="flex-1 ml-60 flex flex-col">

        {{-- Header --}}
        <x-header/>

        {{-- Stats --}}
        <x-emails-stats
            :stats="$stats"
            :emails="$emails"
        />

    </div>
</div>

@push('scripts')
    @vite([
        'resources/js/emails.js'
    ])
@endpush
