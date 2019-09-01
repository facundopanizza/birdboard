<?php

namespace Tests\Feature;

use App\Task;
use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        tap($project->activity->first(), function($activity) {
            $this->assertEquals('created_project', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updating_a_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $originalTitle = $project->title;

        $project->update(['title' => 'Changed']);

        tap($project->activity->last(), function ($activity) use ($originalTitle) {

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Changed']
            ];

            $this->assertEquals('updated_project', $activity->description);
            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    public function creating_a_new_task()
    {
        $this->withExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create();

        $this->assertCount(1, $project->activity);

        tap($project->tasks->first()->activity->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks->first()->complete();

        $this->assertCount(2, $project->tasks->first()->activity);

        tap($project->tasks->first()->activity->last(), function ($activity) {
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks->first()->complete();

        $project->tasks->first()->incomplete();

        $this->assertCount(3, $project->tasks->first()->activity);

        tap($project->tasks->first()->activity->last(), function ($activity) {
            $this->assertEquals('incompleted_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(1, $project->activity);
        $this->assertCount(2, $project->tasks->first()->activity);

        tap($project->tasks->first()->activity->last(), function ($activity) {
            $this->assertEquals('deleted_task', $activity->description);
        });
    }
}