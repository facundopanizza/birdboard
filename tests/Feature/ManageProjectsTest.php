<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guest_cannot_create_project()
    {
        $attributes = factory('App\Project')->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_a_single_project()
    {
        $project = factory('App\Project')->create();

        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'notes' => 'General note here.'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee(str_limit($attributes['description'], 100))
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $this->signIn($project->owner);

        $this->patch($project->path(), [
            'title' => 'New title',
            'description' => 'New description',
            'notes' => 'changed',
            ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'New title',
            'description' => 'New description',
            'notes' => 'changed',
        ]);
    }

    /** @test */
    public function a_user_can_update_a_project_general_notes()
    {
        $project = ProjectFactory::create();

        $this->signIn($project->owner);

        $this->patch($project->path(), [
            'notes' => 'changed',
            ]);

        $this->assertDatabaseHas('projects', [
            'notes' => 'changed',
        ]);
    }

     /** @test */
    public function unauthorized_users_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();


        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->signIn();

        $user = auth()->user();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();


        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->signIn($project->owner);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->be(factory('App\User')->create());

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();

        $project =  ProjectFactory::create();

        $this->patch($project->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_project_require_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_require_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $project = ProjectFactory::create();

        $this->assertInstanceOf('App\User', $project->owner);
    }

    /** @test */
    public function a_user_can_see_all_projets_they_have_been_invited_to_on_their_dashboard()
    {
        $this->signIn();
        $user = auth()->user();

        $project = ProjectFactory::create();
        
        $project->invite($user);

        $this->get('/projects')
            ->assertSee($project->title);
    }
}