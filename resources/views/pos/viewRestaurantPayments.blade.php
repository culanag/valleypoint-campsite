@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="pt-3 pb-3">
        <h3 class="text-center">Payment Transactions</h3>
    </div>
    <div class="col-md-12">
        <table id="restaurantPaymentsTable" data-order='[[ 0, "asc" ]]' class="table table-sm dataTable stripe compact" cellspacing="0">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Table Number</th> 
                    <th>Date</th> 
                    <th>Payment Status</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>

                @foreach($restPayments as $restaurantPayment)
                <tr>
                    <td>{{$restaurantPayment->orderID}}</td>
                    <td>{{$restaurantPayment->queueNumber}}</td>
                    <td class=>{{$restaurantPayment->paymentStatus}}</td> 
                    <td>{{$restaurantPayment->paymentDatetime}}</td>
                    <td class="text-right">{{number_format((float)($restaurantPayment->amount), 2, '.', '')}}</td>
                                              
                </tr>
                @endforeach
                   
            </tbody>
        </table>
</div>
@endsection