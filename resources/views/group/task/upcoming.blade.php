@extends('layouts.app', ['title' => $group->name . " > " . __('Upcoming')])

@section('content')
    
    @include('users.partials.header', [
        'title' =>  __('Upcoming tasks')
    ])
    @include('Smartmd::js-parse')

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="card-header-details">
                            <a class="btn btn-sm btn-default float-right" href="{{ route('groups.tasks.create', [$group->id])}}">
                                {{__('New task')}}
                            </a>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div class="row">
                            <div class="col-md-8">
                                @foreach ($tasksByDays as $days => $tasks)
                                    <h2 class="title-days"><span class="line">{{$days == 0 ? __('Today') : $days . __(' days')}}</span></h2>
                                
                                    <ul class="list-unstyled tasks-list tasks-upcoming">
                                        @foreach ($tasks as $task)
                                            <li>
                                                @if ($task->tasktype_id == TaskType::ASSIGNMENT)
                                                    <i class="fas fa-tasks"></i>
                                                @else
                                                    <i class="ni ni-paper-diploma"></i>
                                                @endif
                                                <a href="{{route('groups.tasks.show', [$group->id, $task->id])}}" class="pl-2">
                                                    {{$task->title}}
                                                </a>

                                                <span class="float-right">
                                                    @if ($task->comments && count($task->comments) > 0)
                                                        <span><i class="far fa-comments"></i> {{count($task->comments)}} </span>
                                                    @endif
                                                    @if ($task->attachements && count($task->attachements) > 0)
                                                        <span><i class="fas fa-paperclip"></i> {{count($task->attachements)}}</span>
                                                    @endif
                                                    @if ($task->isPrivate)
                                                        <span class="badge ml-1 badge-danger">{{__('Private')}}</span>
                                                    @endif
                                                    <span class="badge ml-1 badge-info subject-color-{{$task->subject->color}}">{{$task->subject->name}}</span>

                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                            <div class="col-md-4">
                                <h4>{{__('Projects')}}</h4>
                                <ul class="list-unstyled tasks-list tasks-upcoming">
                                    @foreach ($projects as $project)
                                        <li>
                                            <i class="fas fa-folder-open"></i>

                                            <a href="{{route('groups.tasks.show', [$group->id, $project->id])}}" class="pl-2">
                                                {{$project->title}}
                                            </a>

                                            <span class="float-right">
                                                @if ($task->tasks && count($project->tasks) > 0)
                                                    <span><i class="fas fa-folder-open"></i> {{count($project->tasks)}} </span>
                                                @endif
                                                @if ($task->attachements && count($task->attachements) > 0)
                                                    <span><i class="fas fa-paperclip"></i> {{count($task->attachements)}}</span>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                       
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection
