@extends('layouts.app')

@section('header')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>

                    <div class="card-body">

                        <form method="POST" action="/threads">

                            <div class="form-group">
                                <label for="">Choose a Channel:</label>
                                <select type="text" name="channel_id" class="form-control">
                                    <option value="">Choose one...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : ''}}>{{$channel->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text"
                                       class="form-control" name="title" id="title" aria-describedby="helpId"
                                       value="{{ old('title') }}">
                            </div>
                            <div class="form-group">
                                <label for="body">Body</label>
                                <text-editor name="body"></text-editor>
{{--                                <textarea class="form-control" name="body" id="body" rows="3">{{old('body')}}</textarea>--}}
                            </div>

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPCHA_SECRET') }}"></div>
                            <br/>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
