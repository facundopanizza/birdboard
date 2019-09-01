<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $this->signIn();

        factory(Project::class)->create(['owner_id' => auth()->user()->id]);

        $this->assertInstanceOf(Project::class, auth()->user()->projects->first());
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {   
        $this->signIn();
        $john =  auth()->user();

        $project = factory(Project::class)->create(['owner_id' => $john->id]);

        $this->assertCount(1, $john->accessibleProjects());

        $sally = factory(User::class)->create();
        $nick = factory(User::class)->create();

        $sallyProject= factory(Project::class)->create(['owner_id' => $sally->id]);
        $sallyProject->invite($nick);

        $this->assertCount(1, $john->accessibleProjects());

        $sallyProject->invite($john);
        $this->assertCount(2, $john->accessibleProjects());
    }
}
