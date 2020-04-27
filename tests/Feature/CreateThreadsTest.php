<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_may_not_create_threads()
    {
//        $this->withoutExceptionHandling();
        $thread = factory('App\Thread')->raw();
        $this->post('/threads', $thread)
            ->assertStatus(302);
        $this->assertDatabaseMissing('threads', $thread);
    }
    
    /** @test */
    public function guest_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function new_user_must_authenticate_their_email_before_creating_threads()
    {
        $this->signIn(create('App\User', [
            'email_verified_at' => null
        ]));

        $thread = factory('App\Thread')->raw();

        $response = $this->post('/threads', $thread);

        $response->assertRedirect('threads')
            ->assertSessionHas('flash', 'You must first confirm you email address');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $response = $this->get($response->headers->get('Location'));

        $response->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = factory('App\Thread')->raw($overrides);

        return $this->post('/threads', $thread);
    }
    
    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(\App\Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(\App\Thread::whereSlug('foo-title-3')->exists());
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(\App\Thread::whereSlug('some-title-24-2')->exists());
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create('App\Thread');

        $response = $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();

        $response = $this->delete($thread->path())->assertStatus(403);

    }

    /** @test */
    public function authorized_user_can_delete_a_thread()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
                'subject_id' => $thread->id,
                'subject_type' => get_class($thread),
                'type' => 'created_thread'
            ]
        );
        $this->assertDatabaseMissing('activities', [
                'subject_id' => $reply->id,
                'subject_type' => get_class($reply),
                'type' => 'created_reply'
            ]
        );
    }
}
