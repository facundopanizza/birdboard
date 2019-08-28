<?php

namespace Tests\Unit;

use App\Task;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_belongs_to_a_project()
    {
        $this->withoutExceptionHandling();

        $task = factory(Task::class)->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    function it_has_a_path()
    {
        $task = factory(Task::class)->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }

    /** @test */
    function it_can_be_completed()
    {
        $task = factory(Task::class)->create();

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test */
    function it_can_be_incompleted()
    {
        $task = factory(Task::class)->create();

        $task->complete();

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }
}
