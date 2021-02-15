<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed'
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed body.'
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function anauthorized_users_may_not_update_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_thread_can_be_updated()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body.'
        ]);

        tap($thread->fresh(), function($thread) {
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed body.', $thread->body);
        });
    }
}
