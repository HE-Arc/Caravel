@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Create a group')
    ])   

    <div class="container-fluid mt--7">
        <div class="card bg-white">
            <p>Name : {{$group->name}}</p>
            <p>ID : {{$group->id}}</p>
            @if ($errors)
                <p>Erro : {{$errors}}</p>
            @endif
    
        </div>

        @include('layouts.footers.auth')
    </div>
    <!--    $table->id();
            $table->string('name', 150);
            $table->string('picture');
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->nullable();
   -->
@endsection
