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
        <x-header/>

        {{-- Stats --}}
        <x-emails-stats
            :stats="$stats"
            :emails="$emails"
        />

        {{-- Grid principal --}}
        <div class="grid grid-cols-3 gap-6 items-start">

            {{-- Columna izquierda (2/3) --}}
            <div class="col-span-2 space-y-6">

            {{-- Config — solo admin --}}
            @if(auth()->user()->isAdmin())
                @include('partials.users')
            @endif

            </div>

        </div>{{-- /grid --}}
    </div>{{-- /main --}}
</div>{{-- /flex --}}
@endsection

@push('scripts')
    @vite([
        'resources/js/dashboard.js'
    ])
@endpush
