<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        # disable for testing
//        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
//            'owner_id' => 1
        ];

        # 1) if I visit route and add new project
            # assert we were redirected
        $this->post('/projects', $attributes)->assertRedirect('/projects');

        # 2) should be inserted into the database
        $this->assertDatabaseHas('projects' , $attributes);

        # 3) should be able to see it when I visit the page.
        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        # Reduce ammount of Errors for better view.
//        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);

    }

    /** @test */
    public function a_project_requires_a_title()
    {
//        $this->withoutExceptionHandling();

        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
//        $this->withoutExceptionHandling();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}
