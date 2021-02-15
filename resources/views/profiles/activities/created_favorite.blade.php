@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $activity->subject->favorited->path() }}">
        {{ $profileUser->name }} favorited a reply
{{--            "{{ $activity->subject->thread->title }}"--}}
        </a>
    @endslot
    @slot('body')
        <div class="body">{{ $activity->subject->favorited->body }}</div>
    @endslot
@endcomponent
