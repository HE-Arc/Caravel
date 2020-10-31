@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => (isset($group->id) ? __('Update a group') : __('Create a group'))
    ])

    @if (isset($group->id))
        <form method="post" action="{{ route('groups.update', ['group' => $group]) }}" autocomplete="off" id="form-group">
        @method('patch')
    @else
        <form method="post" action="{{ route('groups.create') }}" autocomplete="off" id="form-group">
        @method('post')
    @endif
    @csrf

    <div class="container mt--7">
        <div class="card bg-white">
            <div class="card-header text-center">
                @if(isset($group->id)) <h1>Update a group</h1> @else <h1>Create a new group</h1> @endif
            </div>
            <div class="card-body">
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
                        <img id="group-picture" src="@if(isset($group->id)){{$group->picture}} @else {{asset(config('caravel.groups.pictureFolder').config('caravel.groups.pictureBase'))}} @endif" width="250" height="250"/>
                        <input type="file" accept="image/png,image/jpeg" name="picture" id="input-picture" class="d-none form-control form-control-alternative{{ $errors->has('picture') ? ' is-invalid' : '' }}" required autofocus>
                    </div>
                </div>

                <!-- Users list TODO -->
                <h2>Current users</h2>
                <div>
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