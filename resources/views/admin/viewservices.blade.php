@extends('layouts.app')

@section('content')
<div class="container" style="position:fixed;">
    <div class="pt-3 pb-3">
        <a href="#">
            <span style="float:left;">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                <strong>Back</strong>
            </span>
        </a>
        <h3 class="text-center">Services</h3>
    </div>
    <div class="col-md-12">
        <table id="usersTable" data-order='[[ 0, "asc" ]]' class="table table-sm dataTable stripe compact" cellspacing="0">
            <thead>
                <tr>                   
                    <th>ID</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Price (lean)</th>
                    <th>Price (peak)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                    @if(count($services) > 0)
                    @foreach($services as $service)
                <tr>          
                    <td>{{$service->id}}</td>
                    <td>{{$service->serviceType}}</td>
                    <td>{{$service->serviceName}}</td>
                    <td>{{$service->price}}</td>
                    <td>{{$service->leanPrice}}</td>
                    <td>{{$service->peakPrice}}</td>
                    <td>
                        <a href="edit-service/{{$service->id}}">
                            <button class="btn btn-sm btn-info">Edit</button>
                        </a>
                        <a id="{{$service->id}}" class="delete-service-modal" data-toggle="modal" data-target="#deleteServiceModal">
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </a>
                    </td>
                </tr>
                    @endforeach
                    @endif
            </tbody>
        </table>
    </div>
</div> 

<!-- Delete service modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="deleteServiceModalBody">
            </div>
            <div class="modal-footer">
                <a href="" id="confirmServiceDeletion"><button type="button" class="btn btn-danger" style="width:5em;">Yes</button></a>
                <button type="button" class="btn btn-primary" style="width:5em;" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection