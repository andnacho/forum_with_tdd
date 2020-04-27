<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function non_administrator_may_not_lock_thread()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        try {
            $this->post(route('locked-threads.store', $thread));
        } catch(\Exception $e) {
            $this->assertEquals('You do not have permission to this action', $e->getMessage());
        }

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function administrator_can_lock_thread()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked, 'Failed asserting that the thread was locked');
    }

    /** @test */
    public function administrator_can_unlock_thread()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => false]);

        $this->delete(route('locked-threads.destroy', $thread))->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread was unlocked');
    }

   /** @test */
   public function once_locked_a_thread_may_not_receive_new_replies()
   {
       $this->signIn();
       $thread = create('App\Thread');

       $thread->lock();

       $this->post($thread->path() . '/replies', [
           'body' => 'Footbar',
           'user' => auth()->id(),
       ])->assertStatus(422);
   }
}
