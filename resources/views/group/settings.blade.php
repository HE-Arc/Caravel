@extends('layouts.app', ['title' => __('Group settings')])

@section('content')
    @include('users.partials.header', [
        'title' =>  __('Group settings')
    ])

    <!-- this page allows you to change the existing groupe -->
    <form method="post" action="{{ route('groups.update', ['group' => $group]) }}" autocomplete="off" id="form-group" enctype="multipart/form-data">
    @method('patch')
    @csrf

    <div class="container mt--7">
        <div class="card bg-white">
            <div class="card-header text-center">
                <h1>Manage and edit {{$group->name}}</h1>
            </div>
            
            <!-- Setting inputs -->
            <div class="card-body">
                <div class="d-flex flex-column">
                    <!-- Image -->
                    <div class="form-group{{ $errors->has('picture') ? ' has-danger' : '' }}">
                       <!-- with hover effect -->
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="hovereffect">
                                <img id="group-picture" class="img-fluid" src="{{asset($group->pictureOrDefault())}}" alt="group picture">
                                <div class="overlay" id="img-overlay">
                                   <h2>Change image</h2>
                                </div>
                            </div>
                        </div>
                        <!--hidden input-->
                        <input type="file" accept="image/png,image/jpeg,image/jpg" name="picture" id="input-picture" class="d-none form-control form-control-alternative{{ $errors->has('picture') ? ' is-invalid' : '' }}">
                    </div>
                    <p class="font-italic blockquote-footer p-1">Recommended : Square dimensions (N*N px). The image will be resized at 250*250 px</p>

                    <!-- Name -->
                    <div class="form-group w-50{{ $errors->has('name') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-name">{{ __('name') }}</label>
                        <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Inf-DLM-2020') }}" value="{{ old('name', $group->name) }}" required autofocus>
                    </div>
                    <!-- Description -->
                    <div class="form-group w-50{{ $errors->has('description') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-description">{{ __('description') }}</label>
                        <textarea name="description" id="input-description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Your description here') }}">{{ old('description', $group->description) }}</textarea>
                    </div>
                </div>

                <div class="text-right">
                    <!-- "save" button -->
                    <button type="submit" class="btn btn-sm btn-outline-success">{{ __('Save') }}</button>
                    </form> <!-- end of "save" form -->
                    <!-- "delete" form -->
                    @if ($isLeader)
                    <form action="{{route('groups.destroy', $group)}}" method="post">
                        @method('delete')
                        @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                    </form>    
                    @endif</div>
                
                <div class="p-4">
                    <h1 class="text-center p-2 ">Manage</h1>
                    <div class="d-flex flex-row justify-content-center">
                        <!-- "members" button, with badge -->
                        <a href="{{route('groups.members', $group)}}" id="Members" class="btn btn-primary">
                            {{ __('Members ') }}<span class="badge badge-success">{{$membersCount}}</span>
                        </a>
                        <!-- "pending" button, with badge -->
                        <a href="{{route('groups.pending', $group)}}" id="pending" class="btn btn-primary">
                            {{ __('pending Requests ') }} @if ($pendingCount>0) <span class="badge badge-danger">{{$pendingCount}}</span> @endif 
                        </a>
                    </div>    
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
<script>
//make image click trigget the file input event
$('#group-picture,#img-overlay').click(function(){ 
        $('#input-picture').trigger('click');
    });

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#group-picture').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$("#input-picture").change(function() {
  readURL(this);
});
</script>
@endpush