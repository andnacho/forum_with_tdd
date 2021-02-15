@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <avatar-form :user="{{ $profileUser }}"></avatar-form>
                    </div>
                </div>
            </div>
        </div>
    @forelse ($activities as $date => $activity)
        <h3 class="page-header">{{ $date }}</h3>
        @foreach($activity as $record)
            @if(view()->exists("profiles.activities.{$record->type}"))
                    @include ("profiles.activities.{$record->type}", ['activity' => $record])
            @endif
        @endforeach
    @empty
            <div class="container p-5">
                <p class="text-center">There is no activity for these user.</p>
            </div>
    @endforelse
    {{--            {{ $threads->links() }}--}}
@endsection
