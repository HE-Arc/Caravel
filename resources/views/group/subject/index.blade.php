@extends('layouts.app', ['title' => __('Subjects')])

@section('content')
    
    @include('users.partials.header', [
        'title' => __(isset($subject->user) ? 'Edit a task' : 'Create a task')
    ])   

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('Subject') }}</h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Subject</th>
                                <th scope="col">Tasks</th>
                                <th scope="col">Projects</th>
                                <th scope="col">Color</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                          <img alt="Image placeholder" src="../../assets/img/theme/bootstrap.jpg">
                                        </a>
                                        <div class="media-body">
                                            <span class="mb-0 text-sm">Argon Design System</span>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    {{count($subject->tasks)}}
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                      <i class="bg-warning"></i> pending
                                    </span>
                                </td>
                                <td>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal">{{__('Delete')}}</a>
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
