@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    
    @include('users.partials.header', [
        'title' => $task->title
    ])
    @include('Smartmd::js-parse')

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

                        <div class="col-10">
                            <textarea id="editor" placeholder="test" style="display: none">
                                {{ $task->description }}
                            </textarea>
                            <div id="content" class="markdown-body">
                        
                        </div>
                </div>
            </div>
        </div>

        @push('script')
            <script>
                // create Parsemd object use javascript parse markdown
                var parse = new Parsemd();
                var html = parse.render(document.getElementById("editor").value.replace(/^\s+|\s+$/g, ''));
                document.getElementById("content").innerHTML = html;
            </script>
        @endpush
        
        @include('layouts.footers.auth')
    </div>
@endsection
