<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
    
    public function destroy(Thread $thread)
    {
       $thread->unlock();
    }
}
