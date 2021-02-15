<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Favoritable;
use App\Inspection\Spam;
use App\Rules\Recaptcha;
use App\Rules\SpamFree;
use App\Thread;
use App\Filters\ThreadFilters;
use App\Trending;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * Class ThreadController
 * @package App\Http\Controllers
 */
class ThreadController extends Controller
{

    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel = null, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index',[
            'threads' => $threads,
            'trending' => $trending->get()
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {
        $request->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => [$recaptcha]
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title'   => request('title'),
            'body'    => request('body')
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

//        $thread->visits()->record();
        $thread->increment('visits');

        return view('threads.show', [
            'thread' => $thread
        ]);
    }

    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/profiles/' . auth()->user()->name);
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function getThreads(Channel $channel = null, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if ($channel != null && $channel->exists) {
            $threads = $channel->threads();
        } elseif ($channel == null) {
            $threads = Thread::latest()->filter($filters);
        }

        $threads = $threads->filter($filters)->paginate(10);

        return $threads;
    }
}
