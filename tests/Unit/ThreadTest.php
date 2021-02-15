<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{

    use RefreshDatabase;

    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function testBasicTest()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }
    
    /** @test */
    public function a_thread_has_a_path()
    {
        $thread = factory('App\Thread')->create();

        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->slug, $thread->path());
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }
    
    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        \Notification::fake();

        $this->signIn()->thread->subscribe()->addReply([
            'body' => 'Foobar',
            'user_id' => 999
        ]);

        \Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
    
    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->get()->count()
        );
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);


        $this->assertCount(0, $thread->subscriptions);
    }
    
    /** @test */
    public function it_know_it_the_user_is_subscribed_to()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));

        });
    }
    
    /** @test */
    public function aThreadMayBeLocked()
    {
        $this->assertFalse($this->thread->locked);
        $this->thread->lock();
        $this->assertTrue($this->thread->locked);
    }

//    /** @test */
//    public function a_thread_records_each_visit()
//    {
//        $thread = make('App\Thread', ['id' => 1]);
//
//        $thread->visits()->reset();
//
//        $this->assertSame(0, $thread->visits()->count());
//
//        $thread->visits()->record();
//
//        $this->assertEquals(1, $thread->visits()->count());
//
//        $thread->visits()->record();
//
//        $this->assertEquals(2, $thread->visits()->count());
//    }
}
