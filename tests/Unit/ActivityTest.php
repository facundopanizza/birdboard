<?php

namespace Tests\Unit;

use App\User;
use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function it_has_a_user()
    {
        $project = ProjectFactory::create();

        $this->signIn($project->owner);

        $this->assertEquals(auth()->user()->id, $project->activity->first()->user->id);
    }
}
