@forelse ($threads as $thread)
    <article>
        <div class="level">
            <div class="flex">
                <h4 class="flex">
                    <a href="{{ $thread->path() }}">
                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong style="color: #2ea3b5">
                                {{ $thread->title }}
                            </strong>
                        @else
                            {{ $thread->title }}
                        @endif
                    </a>
                </h4>

                <h5>Posted by: <a
                        href="{{ $thread->creator->path() }}">{{ $thread->creator->name }}</a>
                </h5>
            </div>

            <a href="{{ $thread->path() }}">
                <strong>{{ $thread->replies_count }} {{ Str::plural('respuesta', $thread->replies_count) }}</strong>
            </a>
        </div>
        <div>
            <div class="card-body">{{ $thread->body }}</div>
        </div>

        <div class="card-footer">
             Visits: {{ $thread->visits }}
        </div>
    </article>
    <hr>
@empty
    <p>There is no revelevant options for this request</p>
@endforelse
