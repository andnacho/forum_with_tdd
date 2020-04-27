<?php

namespace Tests\Feature;

use App\Reply;
use function foo\func;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $this->actingAs($user);

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make([
            'user_id' => $user->id,
            'thread_id' => $thread->id
        ]);

        $arrayReply = $reply->toArray();

        $this->post($thread->path() . '/replies', $arrayReply);


        $this->assertDatabaseHas('replies', [
            'user_id' => $arrayReply['user_id'],
            'thread_id' => $arrayReply['thread_id'],
            'body' => $arrayReply['body']
        ]);

        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->raw(['body' => null]);

        $this->post($thread->path() . '/replies', $reply)
            ->assertSessionHasErrors('body');
    }
    
    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn();

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }
    
    /** @test */
    public function authorized_user_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function authorized_user_can_update_replies()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'You been changed';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply ]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn();

        $this->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(302);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply', [
            'user_id' => auth()->id()
        ]);

        $this->patch( '/replies/' . $reply->id, [
            'body' => 'aaaaaaaaaaa'
        ])
            ->assertStatus(422);
    }
    
    /** @test */
    public function user_may_only_reply_a_maximun_of_once_per_minute()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'My simple reply'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(201);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }
}
