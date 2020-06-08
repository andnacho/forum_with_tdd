<div class="row my-3" v-if="editing">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="form-group level">
                    <input type="text" class="form-control" v-model="form.title">
                    @can ('delete', $thread)
                        <form action="{{ $thread->path() }}" method="post" class="float-right">
                            @csrf
                            @method('delete')
                            <input type="submit" value="Delete" class="btn btn-danger">
                        </form>
                        @endcan
                </div>

            </div>
            <div class="card-body">
                <div class="form-group">
                    <text-editor v-model="form.body"></text-editor>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-sm btn-primary" @click="update">Update</button>
                <button class="btn btn-sm btn-danger" @click="cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

{{--Is not editing de thread--}}
<div class="row my-3" v-else>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">

                <img src="/storage/{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-2">
                <a href="{{ $thread->creator->path() }}">{{ $thread->creator->name }}</a> posted:
                <span class="font-weight-bold" v-text="form.title"></span>
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
                    <div class="body" v-html="form.body"></div>
                </article>
            </div>
            <div class="card-footer" v-if="authorize('owns', thread)">
                <button class="btn btn-sm btn-info" @click="editing = true">edit</button>
            </div>
        </div>
    </div>
</div>
