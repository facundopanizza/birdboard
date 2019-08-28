@extends('layouts.app')

@section('content')
<header class="flex items-center mb-4 py-4">
    <div class="flex justify-between items-end w-full">
        <p class="text-bird text-sm font-normal">
            <a href="/projects">My Projecs</a> / {{ $project->title }}
        </p>

        <a href="{{ $project->path() . '/edit' }}" class="button">Edit Project</a>
    </div>
</header>
<main>
    <div class="lg:flex -mx-3">
        <div class="lg:w-3/4 px-3 mb-6">
            <div class="mb-8">
                <h2 class="text-bird font-normal text-xl mb-3">Tasks</h2>
                @foreach($project->tasks as $task)
                    <div class="card mb-3">
                        <form method="POST" action="{{ $task->path() }}">
                            @csrf
                            @method('PATCH')

                            <div class="flex">
                                <input type="text" class="w-full {{ $task->completed ? 'text-bird' : '' }}" name="body" value="{{ $task->body }}">
                                <input name="completed" type="checkbox" onChange="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                            </div>
                        </form>
                    </div>
                @endforeach
                <div class="card">
                    <form method="POST" action="{{ $project->path() . '/tasks' }}">
                        @csrf

                        <input type="text" placeholder="Begin adding tasks..." name="body" class="w-full" required>
                    </form>
                </div>
            </div>

            <div>
                <h2 class="text-bird font-normal text-xl mb-3">General Notes</h2>
                <form method="POST" action="{{ $project->path() }}">
                    @csrf
                    @method('PATCH')

                    <textarea class="card w-full" name="notes" style="min-height: 200px">{{ $project->notes }}</textarea>

                    <button type="submit" class="button mt-3">Save</button>
                </form>

                @if($errors->any())
                    <div class="text-red-500 text-sm mt-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:w-1/4 px-3">
            <div class="w-full mb-4 h-6 text-lg opacity-0">
            </div>
            @include('projects.card')

            @include('projects.activity.card')
        </div>
    </div>
</main>
@endsection