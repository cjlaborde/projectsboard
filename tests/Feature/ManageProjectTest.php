<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_project()
    {
//        $this->withoutExceptionHandling();
//        $this->withoutMiddleware();

        # sign in user
       $project = factory('App\Project')->create();
//        dd($attributes);

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        # disable for testing
        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        $this->actingAs(factory('App\User')->create());

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
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
    public function a_user_can_view_their_project()
    {
        # Reduce ammount of Errors for better view.
        $this->be(factory('App\User')->create());

        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);

    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        # sign in user
        $this->be(factory('App\User')->create());

//        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }

    /** @test */
    public function a_project_requires_a_title()
    {
//        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
//        $this->withoutExceptionHandling();
                $this->withoutMiddleware();

        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}

















//
//
///** @test */
//public function guests_cannot_control_project()
//{
////        $this->withoutExceptionHandling();
//    $this->withoutMiddleware();
//
//    $attributes = factory('App\Project')->raw();
////        dd($attributes);
//
//    $response = $this->post('/projects', $attributes);
//    $response->dump();
//    $response->assertRedirect('login');
//}
//
///** @test */
//public function guest_cannot_view_projects()
//{
//    # if you not sign in redirected to login page
//    $this->get('/projects')->assertRedirect('login');
//}
//
///** @test */
//public function guest_cannot_view_a_single_project()
//{
//    # create project
//    $project = factory('App\Project')->create();
//
//
//    # if you not sign in redirected to login page
//    $this->get($project->path())->assertRedirect('login');
//}