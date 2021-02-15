<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_repley_are_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create('App\User', ['name' => 'JaneDoe']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => '@JaneDoe look at this. Tell @frankDoe'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_show_the_list_of_user_with_given_characters()
    {
        $this->withoutExceptionHandling();
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'janedoe']);
        create('App\User', ['name' => 'Johndoe2']);

        $results = $this->json('GET', 'api/user', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }
}
