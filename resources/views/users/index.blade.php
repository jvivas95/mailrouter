{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main --}}
    <div class="flex-1 ml-60 flex flex-col">

        {{-- Header --}}
        <x-header/>

        {{-- Config --}}
        <div>
            @include('partials.users')
        </div>

    </div>
</div>
@endsection
