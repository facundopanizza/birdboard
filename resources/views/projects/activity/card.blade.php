<div class="card mt-3">
    <ul class="text-xs">
        @foreach(App\Activity::where('project_id', $project->id)->get() as $activity)
        <li class="{{ $loop->last ? '' : 'mb-1' }}">
            @include("projects.activity.$activity->description")
            <span class='text-bird'>{{ $activity->created_at->diffForHumans(null, true) }}</span>
        </li>
        @endforeach
    </ul>
</div>