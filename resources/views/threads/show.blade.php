@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/tribute.css">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row my-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                    <img src="/storage/{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-2">
                                    <a href="{{ $thread->creator->path() }}">{{ $thread->creator->name }}</a> posted:
                                    <span class="font-weight-bold">{{ $thread->title }}</span>
                                    @can ('delete', $thread)
                                    <form action="{{ $thread->path() }}" method="post" class="float-right">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" value="Delete" class="btn btn-danger">
                                    </form>
                                    @endcan
                                    </h4>
                                </div>
                                <div class="card-body">

                                    <article>

                                        <div class="body">{{ $thread->body }}</div>
                                    </article>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-md-12">
                           <replies @removed="repliesCount--" @added="repliesCount++"></replies>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row my-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    Este post fue publicado hace {{ $thread->created_at->diffForHumans() }} por
                                    <a href="#">{{ $thread->creator->name }}</a>, actualmente tiene <span v-text="repliesCount"></span>
                                    {{ Str::plural('comentario', $thread->replies_count) }}
{{--                                    {{ $thread->replies()->count() == 1 ? ' comentario' : ' comentarios' }}--}}

                                    <p class="level">
                                        <subscribe-button class="" :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                                        <button class="btn btn-secondary ml-2"
                                                v-if="authorize('isAdmin')"
                                                @click="toggleLock"
                                                v-text="locked ? 'Unlock' : 'Lock'">
                                            Lock
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </thread-view>
@endsection
