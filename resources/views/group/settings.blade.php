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
                <h1>Update a group</h1>
            </div>
            
            <div class="card-body">
                <!-- errors -->
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
                <!-- inputs -->
                <div class="d-flex flex-row justify-content-around">
                    <div class="d-flex flex-column justify-content-around">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-name">{{ __('name') }}</label>
                            <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Inf-DLM-2020') }}" value="{{ old('name', $group->name) }}" required autofocus>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-description">{{ __('description') }}</label>
                            <textarea name="description" id="input-description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Groupe Inf-DLM de la volée 2020 !') }}" value="{{ old('description', $group->description) }}" required autofocus></textarea>
                        </div>
                    </div>
                        
                    <div class="form-group{{ $errors->has('picture') ? ' has-danger' : '' }}">
                        <img id="group-picture" src="@if(isset($group->picture)){{$group->picture}} @else {{asset(config('caravel.groups.pictureFolder').config('caravel.groups.pictureBase'))}} @endif" width="250" height="250"/>
                        <input type="file" accept="image/png,image/jpeg,image/jpg" name="picture" id="input-picture" class="d-none form-control form-control-alternative{{ $errors->has('picture') ? ' is-invalid' : '' }}" required autofocus>
                        <p class="font-italic blockquote-footer width-250">Recommended : Square dimensions (N*N px). The image will be resized at 250*250 px.</p>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                </div>

                <!-- Users list TODO -->
                <h2>Current users</h2>
                <div class="d-flex flex-row">
                    @foreach ($users as $user)
                        
                    @endforeach
                </div>
            </div>
        </div>
        </form>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
<script>
//make image click trigget the file input event
$('#group-picture').click(function(){ 
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