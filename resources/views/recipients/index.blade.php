{{-- resources/views/recipients/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main --}}
    <div class="flex-1 ml-60 flex flex-col">

        {{-- Header --}}
        <x-header/>

        {{-- Recipients --}}
        <div>
            @include('partials.recipients')
        </div>

    </div>
</div>
@endsection

@push('scripts')
    @vite([
        'resources/js/recipients.js'
    ])
@endpush
