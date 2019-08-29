<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function path()
    {
        return $this->project->path() . '/tasks/' . $this->id;
    }

    public function complete()
    {
        $this->update(['completed' => true]);

        return $this;
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        return $this;
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function addTask($body)
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'project_id' => $this->project_id,
            'description' => $description
            ]);
    }
}
