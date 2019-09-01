<?php

namespace App;

use Illuminate\Support\Arr;

trait RecordsActivity
{
    public $oldAttribute = [];

    public static function bootRecordsActivity()
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->oldAttribute = $model->getOriginal();
                });
            }
        }
    }

    protected function activityDescription($description)
    {
        return "{$description}_" . strtolower(class_basename($this));
    }

    protected static function recordableEvents()
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        } 
        return ['recorded', 'updated'];
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
                'user_id' => ($this->project ?? $this)->owner->id,
                'description' => $description,
                'changes' => $this->activityChanges(),
                'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function activityChanges()
    {
            if (!$this->wasChanged()) {
                return null;
            }
            return [
                'before' => Arr::except(array_diff_assoc($this->oldAttribute, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at')
            ];
    }
}