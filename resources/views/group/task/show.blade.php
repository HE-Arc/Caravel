@extends('layouts.app', ['title' => $task->subject->group->name . " > " . $task->subject->name . " > " . $task->title])

@section('content')
    
    @include('users.partials.header', [
        'title' =>  $task->subject->group->name . " > " . $task->subject->name
    ])
    @include('Smartmd::js-parse')

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="card-header-details">
                            <span class="state ">
                                @if ($task->due_at->isPast())
                                    <span class="badge badge-danger"> {{__('overdue')}} </span>
                                @else
                                    <span class="badge badge-success"> {{__('due')}} </span>
                                @endif
                            </span>
                            <span class=""> {{__(' Opened by ') }} </span>
                            <span class=" font-weight-bold"> {{$task->user->name }}</span>
                            <span> {{ $task->created_at->diffForHumans()}} </span>
                        <a class="btn btn-sm btn-info float-right" href="{{ route('groups.tasks.create', [$task->subject->group->id])}}">{{__('New')}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2 class="mb-0">
                            {{ $task->title }}
                            <div class="float-right">
                                @if ($task->user->id == auth()->user()->id)
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('groups.tasks.edit', [$task->subject->group->id, $task->id])}}">{{__('Edit')}}</a>
                                    <form method="POST" class="float-right" action="{{ route('groups.tasks.destroy', [$task->subject->group->id, $task->id])}}">
                                        @csrf
                                        @method('DELETE')
                                
                                        <button class="btn btn-sm btn-outline-danger" >
                                            {{__('Delete')}}
                                        </button>
                                    </form>
                                @endif
                                
                            </div>
                        </h2>
                        <span class="mb-0">
                            <i class="far fa-calendar-check"></i> {{ $task->due_at->isoFormat('dddd, D MMMM') }}
                        </span>
                        @if ($task->comments && count($task->comments) > 0)
                            <span> · <i class="far fa-comments"></i> {{count($task->comments)}} </span>
                        @endif
                        @if ($task->attachements && count($task->attachements) > 0)
                            <span> · <i class="fas fa-paperclip"></i> {{count($task->attachements)}} </span>
                        @endif
                        
                        <hr class="my-2"/>

                        <div class="row justify-content-center mt-4 px-4">
                            <div class="col-9 ">
                                <textarea id="editor" placeholder="test" style="display: none">{{ $task->description }}</textarea>
                                <div id="content" class=""></div>
                                
                            </div>
                            <div class="col-3">
                                @if ($task->attachements && count($task->attachements) > 0)
                                    <i class="fas fa-paperclip"></i> {{__('Files')}}
                                    <ul>
                                        @foreach ($task->attachements as $file)
                                            <li>
                                                <a href="{{route('groups.files', ['group' => $group, 'file' => $file->path])}}">{{$file->name}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="row px-4 mb-4">
                            <div class="col-9">
                                <hr class="my-2"/>
                            </div>
                        </div>
                        <div class="row px-4">
                            @foreach ($task->comments as $comment)
                                <div class="col-9">
                                    <div class="comment mb-4">
                                        <span class="avatar avatar-sm rounded-circle float-left mt-1">
                                            <img alt="" src="{{$comment->user->picture}}">
                                        </span>
                                        <div class="comment-wrapper ml-0 pl-0 ml-md-5 md-3">
                                            <div class="comment-header">
                                                <span class=" font-weight-bold"> {{$comment->user->name }}</span>
                                                @if ($task->user->id == $comment->user->id)
                                                    <span class="badge badge-primary"> {{__('author')}} </span>
                                                @endif
                                                {{__('commented')}} {{ $comment->created_at->diffForHumans()}}
                                                

                                                @if (auth()->user()->id == $comment->user->id)
                                                    <form method="POST" style="display: inline;" action="{{route('groups.tasks.comment.delete', [$task->subject->group->id, $task->id, $comment->id])}}">
                                                        @csrf
                                                        @method('DELETE')
                                                
                                                        <button class="btn btn-sm btn-outline-default py-0" >
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            <textarea style="display: none;"  class="form-control form-control-alternative comment-data" >{{$comment->message}}</textarea>       
                                            <div class="comment-display"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                    </div>

                    <div class="card-footer">

                        <form action="{{route('groups.tasks.comment.store', [$task->subject->group->id, $task->id])}}" method="post">
                            @csrf
                            @method('post')
                            <span class="avatar avatar-sm rounded-circle float-left mt-3">
                                <img alt="" src="{{auth()->user()->picture}}">
                            </span>
                            <div class="ml-0 pl-0 ml-md-5 pl-md-3">
                                
                                <div class="form-group{{ $errors->has('message') ? ' has-danger' : '' }}">
                                    <textarea id="commentEditor" class="form-control form-control-alternative" rows="3" placeholder="{{ __('Leave a comment...') }}" name="message">{{old('message', '')}}</textarea>

                                    @if ($errors->has('message'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-success">{{ __('Comment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('js')
            <script src="{{ asset('argon') }}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
            @include('Smartmd::head')
        @endpush
        @push('script')
            <script>
                var editor = new Smartmd({
                    el: "#commentEditor",
                    height: "400px",
                    isFullScreen: false,
                    isPreviewActive: true,
                    uploads: {
                        type: ['jpeg', 'png', 'bmp', 'gif', 'jpg', 'pdf'],
                        maxSize: 4096,
                        typeError: 'Support format {type}.',
                        sizeError: 'File size is more than {maxSize} kb.',
                        serverError: 'Upload failed on {msg}',
                        url: "{{ route('groups.upload', ['group' => $task->subject->group->id]) }}"
                    }
                });
                
            </script>
        @endpush
        @push('script')
            <script>
                // create Parsemd object use javascript parse markdown
                var parse = new Parsemd();
                var html = parse.render(document.getElementById("editor").value.replace(/^\s+|\s+$/g, ''));
                document.getElementById("content").innerHTML = html;

                $(".comment").each(function( index ) {
                    var parent = $(this);
                    console.log(parent.find('.comment-data').val());
                    var data =  parse.render(parent.find('.comment-data').val().replace(/^\s+|\s+$/g, ''));
                    parent.find('.comment-display').html(data);
                });
            </script>
        @endpush
        
        @include('layouts.footers.auth')
    </div>
@endsection
