<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp() : void
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }
    
    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get('/threads/' . $this->thread->channel->slug . '/' . $this->thread->slug)
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $this->withoutExceptionHandling();
        $channel = factory('App\Channel')->create();

        $threadInChannel = factory('App\Thread')->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory('App\Thread')->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->withoutExceptionHandling();
        $this->signIn(factory('App\User')->create(['name' => 'JohnDoe']));
        
        $threadByJohn = factory('App\Thread')->create(['user_id' => auth()->id()]);
        
        $threadNotByJohn = factory('App\Thread')->create();

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = factory('App\Thread')->create();
        factory('App\Reply', 2)->states('withThread')->create( ['thread_id' => 1]);
        $threadWithThreeReplies = factory('App\Thread')->create();
        factory('App\Reply', 3)->states('withThread')->create( ['thread_id' => 2]);

        $responses = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0, 0, 0, 0, 0, 0], array_column( $responses['data'], 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_threads_by_unanswered()
    {
        factory('App\Reply')->create();

        $responses = $this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1, $responses['data']);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
    
    /** @test */
    public function record_a_new_visit_each_time_the_thread_is_read()
    {
        $thread = create('App\Thread');

        $this->assertEquals(0, $thread->visits);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
