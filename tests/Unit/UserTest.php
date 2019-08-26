<?php

namespace Tests\Unit;

use App\Project;
use Tests\TestCase;
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
}
