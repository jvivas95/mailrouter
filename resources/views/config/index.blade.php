{{-- resources/views/config/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main --}}
    <div class="flex flex-1 flex-col lg:ml-60">

        {{-- Header --}}
        @include('components.header')

        {{-- Config --}}
        <div>
            @include('partials.config')
        </div>

    </div>
</div>
@endsection
