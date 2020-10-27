@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    
    @include('users.partials.header', [
        'title' => __('Create a Task')
    ])   

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('Task') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('groups.tasks.store', ['group' => $group]) }}" autocomplete="off" id="form-task" enctype="multipart/form-data">
                            
                            @csrf
                            @method('post')
                            
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-name" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', '') }}" required autofocus>

                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('subject_id') ? ' has-danger' : '' }}">
                                            
                                            <select class="form-control form-control-alternative{{ $errors->has('subject_id') ? ' is-invalid' : '' }}" id="subjectid" name="subject_id">
                                                <option value="">{{__('Select a subject')}}</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{$subject->id}}" {{ old('subject_id', '-1')==$subject->id ? 'selected' : '' }}>{{$subject->name}}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('subject_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subject_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('due_at') ? ' has-danger' : '' }}">
                                            <div class="input-group input-group-alternative">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                                </div>
                                                <input class="form-control datepicker" placeholder="Select date" type="text" value="{{old('due_at', '')}}" name="due_at">
                                            </div>

                                            @if ($errors->has('due_at'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('due_at') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="taskType">{{__('Type')}}</label>
                                    <select class="form-control form-control-alternative{{ $errors->has('tasktype_id') ? ' is-invalid' : '' }}" id="taskType" name="tasktype_id">
                                        <option value="">{{__('Select a type')}}</option>
                                        @foreach ($types as $type)
                                            <option value="{{$type->id}}" {{ old('tasktype_id', '-1')==$type->id ? 'selected' : '' }}>{{$type->name}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('tasktype_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tasktype_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                    
                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                
                                    <textarea id="editor" class="form-control form-control-alternative" rows="3" placeholder="{{ __('Description') }}" name="description">{{old('description', '')}}</textarea>

                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('isPrivate') ? ' has-danger' : '' }}">
                                    <div class="custom-control custom-control-alternative custom-checkbox mb-3">
                                        <input class="custom-control-input {{ $errors->has('isPrivate') ? ' is-invalid' : '' }}" type="checkbox" id="isPrivate" name="isPrivate" {{old('isPrivate', '0')=='on' ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="isPrivate">{{__('Make this task private')}}</label>
                                    </div>

                                    @if ($errors->has('isPrivate'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('isPrivate') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('attachement') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-files">{{ __('Files') }}</label>
                                    <input type="file" name="attachement[]" id="input-files" class="form-control form-control-alternative{{ $errors->has('attachement') ? ' is-invalid' : '' }}" multiple>
                                    
                                    @if ($errors->has('attachement'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('attachement') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                            
                        </form>
                        
                        @push('js')
                            <script src="{{ asset('argon') }}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
                            @include('Smartmd::head')
                        @endpush
                        @push('script')
                            <script>
                                var editor = new Smartmd({
                                    el: "#editor",
                                    height: "400px",
                                    isFullScreen: false,
                                    isPreviewActive: false,
                                    uploads: {
                                        type: ['jpeg', 'png', 'bmp', 'gif', 'jpg', 'pdf'],
                                        maxSize: 4096,
                                        typeError: 'Support format {type}.',
                                        sizeError: 'File size is more than {maxSize} kb.',
                                        serverError: 'Upload failed on {msg}',
                                        url: "{{ route('groups.upload', ['group' => $group]) }}"
                                    }
                                });
                               
                          </script>
                        @endpush
                        
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
