@extends('layouts.app', ['title' => __('Search groups')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Consult, join or create a group')
    ])

    <div class="container mt--7">
        <div class="card bg-white">

            <div class="card-header">
                <h1 class="text-center">{{ __("Type your group Here") }}</h1>
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <input type="text" id="groupInput" class="form-control w-75 p-3" placeholder="ide1-a, inf3-dlm..." aria-label="Field to input the class">
                </div>

                <div id="groupsContainer" class="d-flex flex-column justify-content-start align-items-center my-4"> 
                    <div id="createGroupCard" class="card bg-white w-50 p-3 dashed-bottom">
                        <form action="{{route('groups.store')}}" method="post">
                        @method('post')
                        @csrf
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <!--"create" button-->
                            <input type="hidden" name="name" value="" id="inputNameHidden">
                            <h1 id="createClassName"></h1>
                            <button id="createButton" @guest data-toggle="modal" data-target="#modal-notification" @endguest class="disabled btn btn-primary">{{ __('Create') }}</button>
                        </div>
                        </form>
                    </div>
                    <!--groups-->
                    <div id="model" class="card bg-white w-50 p-3 d-none" data-id="-1">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <h1 class="groupName">{{__("Test class 2")}}</h1>
                            <!--"join" button-->
                            <button type="submit" @guest data-toggle="modal" data-target="#modal-notification" @endguest class="btn group-button">{{ __('Requested') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @auth
            @include('layouts.footers.auth')
        @endauth

        <!-- modal for authentication -->
        <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
            <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-danger">
                    
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-notification">{{__("Login required")}}</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        
                        <div class="py-3 text-center">
                            <i class="ni ni-bell-55 ni-3x"></i>
                            <h4 class="heading mt-4">{{__("You are not authenticated")}}</h4>
                            <p>{{__("You need to log in to create or join a group !")}}</p>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <a href="{{route('login')}}"><button type="button" class="btn btn-white">{{__('Login')}}</button></a>
                        <a href="{{route('register')}}"><button type="button" class="btn btn-white">{{__('Sign in')}}</button></a>
                        <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">{{__('Close')}}</button> 
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
<script>
    //set timeout on live search
    let timeout;
    $("#groupInput").keyup(function () {
        $("#inputNameHidden").val($("#groupInput").val());
        clearTimeout(timeout);
        timeout = setTimeout(liveSearch, 250); 
    });

    //Add the name of the group to the create button on-the-fly
    $("#createButton").click(function(e) {
        let completeUrl = $(this).attr('href') + "/?name=" +  $("#groupInput").val();
        $(this).attr('href', completeUrl);
    });

    //Behavior of "join buttons" when clicked
    $('#groupsContainer').on('click', '.group-button.btn-group-join', function(e) {
        //ajax call to the "join" route
        let button = $(this);
        let idGroup = button.attr("data-groupID");
        $.ajax({
            /* the route pointing to the post function */
            url: "{{ route('groups.join', 'idGroup') }}".replace('idGroup', idGroup),
            type: 'GET',
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                //on success : Change the apparence of the button
                button.removeClass('btn-group-join');
                assignStyle(button, buttonStyles[status.pending]);
            }
        });
    });


    function liveSearch(){
        var groupName = $("#groupInput").val()
        $("#createClassName").text(groupName)
        if(!groupName){
            $("#createClassName").text("Type a group name");
            buildListGroups();
        } else {
            $.ajax({
                /* the route pointing to the post function */
                url: "{{ route('groups.filtered', 'groupName') }}".replace('groupName', groupName),
                type: 'GET',
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    if(data.valid){
                        $("#createButton").removeClass("disabled");
                    } else {
                        $("#createButton").addClass("disabled");
                    }
                    if(data.groups){
                        buildListGroups(data.groups);
                    }
                }
            });
        }
    }

    //Build the "groups" card from a list of groups
    function buildListGroups(groups){
        //clean groups except "create" field
        $("#groupsContainer").children().not("#createGroupCard, #model").remove();
        //build groups
        if(groups){
            groups.forEach(group => {
                //build groups one by one
                buildGroup(group);
            });
        }
    }

    //build a "group" card on the livesearch
    function buildGroup(group){
        //clone model tweet
        let groupBody = $("#model").clone();
        //add group name
        groupBody.find(".groupName").text(group.name);
        groupBody.attr("data-id", group.id);

        //assign style to button
        let button = groupBody.find(".group-button");
        let style;
        if(group.request.requesting){
            let requestStatus = group.request.status;
            style = buttonStyles[requestStatus];
        } else {
            style = buttonStyles[status.none];
            button.addClass('btn-group-join');
        }
        applyStyleButton(button, style);
        button.attr("data-groupID", group.id);

        //remove model-related tags
        groupBody.removeClass("d-none");
        groupBody.removeAttr("id");

        //append : Ready to go !
        $("#groupsContainer").append(groupBody[0]);
    }

    function applyStyleButton(button, style){
        button.addClass(style.buttonType);
        button.html(style.buttonText);
    }

    let status = {
        none : -1,
        pending : 0,
        refused : 1,
        accepted : 2,
    };

    let buttonStyles = {
        //-1 special : Not requested yet
        [status.none]: {
            buttonType : "btn-primary",
            buttonText : "join",
        },
        // 0 -> pending, 1 -> refused, 2 accepted
        [status.pending] : {
            buttonType : "btn-info",
            buttonText : "requested",
        },
        [status.refused] : {
            buttonType : "btn-warning",
            buttonText : "refused",
        },
        [status.accepted] : {
            buttonType : "btn-success",
            buttonText : "accepted",
        },
    };

    //init "create" group name
    $("#createClassName").text("Type a group name");
</script>
@endpush