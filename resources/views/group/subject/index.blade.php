@extends('layouts.app', ['title' => __('Manage subjects')])

@section('content')
    
    @include('users.partials.header', [
        'title' => __('Manage subjects')
    ])   

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="card-header-details">
                            <h3>{{__('Subjects list')}}
                                <button class="btn btn-sm btn-default float-right subject-modal-create" data-toggle="modal" data-target="#subject-modal" data-href="{{ route('groups.subjects.store', [$group->id])}}">
                                    {{__('New subject')}}
                                </button>
                            </h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Subject</th>
                                <th scope="col">Tasks</th>
                                <th scope="col">Color</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                            <tr>
                                <th scope="row">
                                    {{$subject->name}}
                                </th>
                                <td>
                                    {{count($subject->tasks)}}
                                </td>
                                <td class="subject-color-{{$subject->color}}">
                                    <span class="label label-info ">color</span>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item subject-modal-edit" href="#" data-toggle="modal" data-name="{{$subject->name}}" data-color="{{$subject->color}}" data-target="#subject-modal" data-id="{{$subject->id}}" data-href="{{route('groups.subjects.update', [$subject->group->id, $subject->id])}}">{{__('Edit')}}</a>
                                            <form action="{{route('groups.subjects.destroy', [$group->id, $subject->id])}}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="dropdown-item">{{__('Delete')}}</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        @include('group.subject.modal')
        
        @include('layouts.footers.auth')
    </div>
@endsection
