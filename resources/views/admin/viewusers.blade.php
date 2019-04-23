@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="pt-3 pb-3">
        <a href="/admin-dashboard">
            <span style="float:left;">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                <strong>Back</strong>
            </span>
        </a>
        <h3 class="text-center">Users</h3>
    </div>
    <div class="col-md-12">
        <a class="btn btn-sm btn-success mb-2" href="/add-user">Add user</a>
        <table id="usersTable" data-order='[[ 0, "asc" ]]' class="table table-sm dataTable stripe compact" cellspacing="0">
            <thead>
                <tr>                   
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Contact No.</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                    @if(count($users) > 0)
                    @foreach($users as $user)
                <tr>          
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->contactNumber}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->role}}</td>
                    <td>
                        <a href="edit-user/{{$user->id}}">
                            <button class="btn btn-sm btn-info">Edit</button>
                        </a>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                    @endforeach
                    @endif
            </tbody>
        </table>
    </div>
    {{--<div style="position:absolute; bottom:0; right:0;">
        <button class="btn btn-lg btn-success" style="border-radius:50% !important;">
            <span class="fa fa-plus" aria-hidden="true"></span>
        </button>
    </div>--}}
</div> 
@endsection