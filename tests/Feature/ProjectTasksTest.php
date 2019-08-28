<?php

namespace Tests\Unit;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->signIn($project->owner);

        $this->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $response = $this->get($project->path());

        $response->assertSee('Test Task');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $response = $this->post($project->path() . '/tasks', ['body' => 'Test task']);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => "Test task"]);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $response = $this->patch($project->tasks->first()->path(), ['body' => 'changed']);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => "changed"]);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->signIn($project->owner);

        $this->patch($project->tasks->first()->path(), ['body' => 'changed']);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
        ]);
    }

    /** @test */
    public function a_task_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->signIn($project->owner);

        $this->delete($project->tasks->first()->path());

        $this->assertCount(0, $project->fresh()->tasks);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->signIn($project->owner);

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_can_be_incomplete()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->signIn($project->owner);

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->patch($project->tasks->first()->path(), [
            'body' => 'incompleted',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'incompleted',
            'completed' => '0'
        ]);
    }

    /** @test */
    public function a_task_require_a_body()
    {
        $project = ProjectFactory::create();

        $this->signIn($project->owner);

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks')->assertSessionHasErrors('body');
    }
}
