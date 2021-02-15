<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        //        $trending = collect(Redis::zrevrange('trending_threads', 0, 4))->map(function($thread) {
        //            return json_decode($thread);
        //        });

        return array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));
    }
    
    public function push($thread)
    {
        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path'  => $thread->path()
        ]));
    }
}
