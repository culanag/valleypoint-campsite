@extends('layouts.app')

@section('content')
    <div class="col-md-12 text-center pos-tabs pb-1">
        <nav class="nav nav-pills centered-pills py-2">
            <a class="nav-item nav-link active" style="background-color:#060f0ed4;" href="#">Create Order</a>
            <a class="nav-item nav-link" style="color:#505050" href="/view-tables">View Tables</a>
        </nav>
    </div>
    <div class="container-fluid col-md-12 pb-5 pt-4 px-5">
        <div class="row">
            <div class="col-md-4 order-md-12 mb-4 mx-0">
                <div class="card p-0 m-0">
                    <h4 class="text-muted text-center py-3">Order Slip</h4>
                    <table class="table table-striped" style="font-size:.88em;">
                        <thead>
                            <tr>
                                <th scope="col" style="width:40%">Description</th>
                                <th scope="col">Qty.</th>
                                <th scope="col">Price</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody id="orderSlip">
                            <tr id="emptyEntryHolder">
                                <td style="text-align:center" colspan="4">Add items from the menu</td>
                                {{--<td>Tapsilog</td>
                                <td>1</td>
                                <td>99.00</td>
                                <td>99.00</td>--}}
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" scope="row">TOTAL:</th>
                                <th id="ordersGrandTotal" style="text-align:right;"></th>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <button type="button" class="btn btn-primary btn-block" style="text-align:center;">
                                        Get payment
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row col-md-8">
                <div class="col-md-3 pr-0 rounded-0">
                    <div class="list-group rounded-0">            
                        <a href="#" id="appetizer" class='rounded-left rounded-0 list-group-item makeorder active' style="color:black">Appetizer</a>
                        <a href="#" id="bread" class='rounded-0 list-group-item makeorder' style="color:black">Bread</a>
                        <a href="#" id="breakfast" class='rounded-0 list-group-item makeorder' style="color:black">Breakfast</a>
                        <a href="#" id="group meals" class='rounded-0 list-group-item makeorder' style="color:black">Group Meals</a>
                        <a href="#" id="noodles" class='rounded-0 list-group-item makeorder' style="color:black">Noodles</a>
                        <a href="#" id="riceBowl" class='rounded-0 list-group-item makeorder' style="color:black">Rice Bowl</a>
                        <a href="#" id="soup" class='rounded-0 list-group-item makeorder' style="color:black">Soup</a>
                        <a href="#" id="beverages" class='rounded-0 list-group-item makeorder' style="color:black">Beverages </a>
                    </div>
                </div>

                <div class="col-md-9 px-0">
                    <div class="card m-0 ml-0 border-left-0 rounded-0 px-3" style="height:24.5em;"> 
                        <div class="row p-3" id="Menu">
                        @foreach ($foods as $food)
                        @if($food->foodCategory == 'appetizers')
                            <a class="px-1 mx-1">       
                                <div class="card px-0 mx-1 menu-item" style="width:9.3rem; height:5em; cursor:pointer" id="{{$food->id}}">
                                    <div class="card-body text-center px-2 py-2 mx-0">
                                    <h6 class="card-text">
                                        {{$food->foodName}}
                                    </h6>
                                    </div>
                                </div>
                            </a>
                        @endif
                        @endforeach
                        </div>    
                    </div>
                    
                    <div class="row">
                        <div class="form-group row mt-2 col-sm-6">
                            <label class="col-sm-4" for="itemQuantity">Quantity:</label>
                            <div class="input-group input-group-sm col-sm-4">
                                <input class="form-control" type="number" name="itemQuantity" id="itemQuantity" min="1" max="500" placeholder="1" value="" required>
                            </div>
                        </div>
                        </div class="col-sm-4 mt-2 mr-0 pr-0">
                            <button id="addItemButton" class="btn btn-success" style="width:10em;" type="button" disabled>Add Item</button>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div id="orderInputs" style="display:none">
                <input type="hidden" id="itemDescription" value="">
                <input type="hidden" id="itemUnitPrice" value="">
                <input type="hidden" id="itemTotalPrice" value="">
            </div>

            <div id="snackbar"></div>
        </div>
@endsection