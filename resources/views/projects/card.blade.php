<div class="card flex flex-col" style="height: 200px">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-secondary pl-4">
        <a href="{{ $project->path() }}">
            {{ $project->title }}
        </a>
    </h3>

    <div class="text-bird flex-1">{{ str_limit($project->description, 100) }}</div>

    @can ('manage', $project)
    <footer>
        <form class="text-right" method="POST" action="{{ $project->path() }}">
            @csrf
            @method('DELETE')

            <button type="submit" class="text-xs self-end">Delete</button>
        </form>
    </footer>
    @endcan
</div>