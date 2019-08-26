@extends('layouts.app')

@section('content')
<header class="flex items-center mb-4 py-4">
    <div class="flex justify-between items-end w-full">
        <h2 class="text-bird text-sm font-normal">My Projects</h2>

        <a href="/projects/create" class="button">New Project</a>
    </div>
</header>

<main class="lg:flex lg:flex-wrap -mx-3">
    @forelse($projects as $project)
        <div class="w-full lg:mx-0 lg:w-1/3 px-3 py-3">
            @include('projects.card')
        </div>
    @empty
        <div>No projects yet.</div>
    @endforelse
</main>
@endsection