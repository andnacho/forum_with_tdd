@extends('layouts.app')

@section('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset-min.css" integrity="sha256-t2ATOGCtAIZNnzER679jwcFcKYfLlw01gli6F6oszk8=" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/algolia-min.css" integrity="sha256-HB49n/BZjuqiCtQQf49OdZn63XuKFaxcIHWf0HNKte8=" crossorigin="anonymous">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum Threads</div>

                    <div class="card-body">
                        @include('threads._list')

                        {{ $threads->render() }}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @if (count($trending))

                    <div class="card">
                        <div class="card-header">
                            Trending threads
                        </div>
                        <div class="card-body">
                            @foreach($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">
                                        {{ $thread->title }}
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>

                @endif
                    <div class="card">
                        <div class="card-header">
                            Search
                        </div>
                        <div class="card-body">
                            <test-algolia public-key="{{ config('scout.algolia.secret') }}" app-id="{{ config('scout.algolia.id') }}"></test-algolia>
                        </div>
                    </div>
            </div>


        </div>
    </div>
@endsection
