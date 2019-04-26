@extends('layouts.app')

@section('content')
    <div class="container row pb-5 pt-3">
        <!--div class="pt-3 pb-3 text-center">
            <a href="/glamping">
                <span style="float:left;">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <strong>Back</strong>
                </span>
            </a>
        </div--> 
        <div class="col-md-2 float-right mx-5 pl-4" style="position:fixed; right:0;">
            <nav class="nav nav-pills nav-stacked mb-5 pb-5" style="display:block;">
                <a class="nav-item nav-link reports-tabs text-center active" style="background-color:#060f0ed4;" href="#">Daily</a>
                <a class="nav-item nav-link reports-tabs text-center" style="color:#505050" href="#">Weekly</a>
                <a class="nav-item nav-link reports-tabs text-center" style="color:#505050" href="#">Monthly</a>
            </nav>
            <!--div class="card mt-5 py-3 px-0 mx-0"-->
                <form method="POST" action="/reload-daily-lodging-report">
                    @csrf
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <div class="row px-3">
                        <div class="form-group col-md-9 px-0 mx-1">
                            <div class="input-group input-group-sm">
                                @if(isset($display))
                                <input class="form-control lodgingReportDateInputs" id="lodgingReportDate" type="date" name="lodgingReportDate" maxlength="15" placeholder="" value="{{$display}}" required>
                                @else
                                <input class="form-control lodgingReportDateInputs" id="lodgingReportDate" type="date" name="lodgingReportDate" maxlength="15" placeholder="" value="<?php echo date("Y-m-d");?>" required>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 px-0 mx-1">
                            <button class="btn btn-sm btn-success" type="submit">
                                <i class="fa fa-calendar-check" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>
            <!--/div-->
        </div>
        <div class="container col-md-10 col-sm-12">
            {{--<form method="POST" action="/reload-lodging-daily-report">
                @csrf
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div class="row col-md-3 offset-5">
                    <div class="form-group col-md-9 px-1 my-0">
                        <div class="input-group input-group-sm">
                            @if(isset($display))
                            <input class="form-control lodgingReportDateInputs" id="lodgingReportDate" type="date" name="lodgingReportDate" maxlength="15" placeholder="" value="{{$display}}" required>
                            @else
                            <input class="form-control lodgingReportDateInputs" id="lodgingReportDate" type="date" name="lodgingReportDate" maxlength="15" placeholder="" value="<!--?php echo date("Y-m-d");?-->" required>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 px-1">
                        <button class="btn btn-sm btn-success" type="submit">
                            <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </form>--}}
            <div class="card col-md-10 offset-md-1 col-sm-12 py-4 ">
                <div class="row">
                    <div class="col-md-6 col-sm-4">
                        <img src={{asset('logo.jpg')}} class="float-left" style="height:7.5em; width:9.75em;" aria-hidden="true"></img>
                    </div>
                    <div class="col-md-6 col-sm-8 px-5 pt-3">
                        <!--h6>Company name: Valleypoint Campsite</h6-->
                        <h6 class="text-right">Date: {{\Carbon\Carbon::now()->format('F j, o')}}</h6>
                    </div>
                </div>
                <div class="card-body">
                    <h6> Today's Figures </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-bordered" style="font-size:.90em;">
                                <thread>
                                    <tr>
                                        <th colspan="2"> Glamping Accommodation </th>
                                    </tr>
                                <thread>
                                <tbody>
                                    @php
                                        $occupiedTentCount = 0;
                                        $totalTents = 0;
                                        $totalGlampingGuests = 0;
                                        $glampingArrivalCount = 0;
                                        $glampingDepartureCount = 0;
                                    @endphp
                                    <tr>
                                        <td> Occupied tents </td>
                                        @foreach ($occupiedTents as $tentsOccupied)
                                            @php
                                                $occupiedTentCount++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$occupiedTentCount}} </td>
                                    </tr>
                                    <tr>
                                        <td> Unoccupied tents </td>
                                        @foreach ($tents as $tent)
                                            @php
                                                $totalTents++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$totalTents-$occupiedTentCount}} </td>
                                    </tr>
                                    <tr>
                                        <td> Checked-in guests </td>
                                        @foreach ($glampingAccommodations as $glampingAccommodation)
                                            @php
                                                $totalGlampingGuests += $glampingAccommodation->numberOfPax;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$totalGlampingGuests}} </td>
                                    </tr>
                                    <tr>
                                        <td> Arrivals </td>
                                        @foreach ($glampingArrivals as $glampingArrival)
                                            @php
                                                $glampingArrivalCount++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$glampingArrivalCount}} </td>
                                    </tr>
                                    <tr>
                                        <td> Departures </td>
                                        @foreach ($glampingDepartures as $glampingDeparture)
                                            @php
                                                $glampingDepartureCount++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$glampingDepartureCount}} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-bordered" style="font-size:.90em;">
                                <thread>
                                    <tr>
                                        <th colspan="2"> Backpacker Accommodation </th>
                                    </tr>
                                <thread>
                                <tbody>
                                    @php
                                        $occupiedRoomCount = 0;
                                        $totalRooms = 0;
                                        $totalBackpackerGuests = 0;
                                        $backpackerArrivalCount = 0;
                                        $backpackerDepartureCount = 0;
                                    @endphp
                                    <tr>
                                        <td> Occupied rooms </td>
                                        @foreach ($occupiedRooms as $roomsOccupied)
                                            @php
                                                $occupiedRoomCount++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$occupiedRoomCount}} </td>
                                    </tr>
                                    <tr>
                                        <td> Unoccupied rooms </td>
                                        @foreach ($rooms as $room)
                                            @php
                                                $totalRooms++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$totalRooms-$occupiedRoomCount}} </td>
                                    </tr>
                                    <tr>
                                        <td> Checked-in guests </td>
                                        @foreach ($backpackerAccommodations as $backpackerAccommodation)
                                            @php
                                                $totalBackpackerGuests += $backpackerAccommodation->numberOfPax;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$totalBackpackerGuests}} </td>
                                    </tr>
                                    <tr>
                                        <td> Arrivals </td>
                                        @foreach ($backpackerArrivals as $backpackerArrival)
                                            @php
                                                $backpackerArrivalCount++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$backpackerArrivalCount}} </td>
                                    </tr>
                                    <tr>
                                        <td> Departures </td>
                                        @foreach ($backpackerDepartures as $backpackerDeparture)
                                            @php
                                                $backpackerDepartureCount++;
                                            @endphp
                                        @endforeach
                                        <td class="text-right"> {{$backpackerDepartureCount}} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h6> Today's Guest Arrivals </h6>
                        <table class="table table-sm table-bordered" style="font-size:.90em;">
                            <thread>
                                <tr>
                                    <th colspan="6"> Glamping Accommodation </th>
                                </tr>
                            <thread>
                            <tbody>
                                <tr>
                                    <td class="text-center" style="width:6%;"> No. </td>
                                    <td class="text-center" style="width:20%;"> Tent no. </td>
                                    <td class="text-center" style="width:45%;"> Guest name </td>
                                    <td class="text-center"> Package availed </td>
                                </tr>
                                @php
                                    $glampingArrivalsCounter = 1;
                                @endphp
                                @if(count($glampingArrivals) > 0)
                                @foreach ($glampingArrivals as $glampingArrival)
                                <tr>
                                    <td class="text-right"> {{$glampingArrivalsCounter++}}</td>
                                    <td> {{$glampingArrival->unitNumber}} </td>
                                    <td> {{$glampingArrival->firstName}} {{$glampingArrival->lastName}} </td>
                                    <td> {{$glampingArrival->serviceName}} </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center" style="font-style:italic;"> No accommodations to show </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered" style="font-size:.90em;">
                            <thread>
                                <tr>
                                    <th colspan="6"> Backpacker Accommodation </th>
                                </tr>
                            <thread>
                            <tbody>
                                <tr>
                                    <td class="text-center" style="width:6%;"> No. </td>
                                    <td class="text-center" style="width:20%;"> Room no. </td>
                                    <td class="text-center" style="width:45%;"> Guest name </td>
                                    <td class="text-center"> No. of pax </td>
                                </tr>
                                @php
                                    $backpackerArrivalsCounter = 1;
                                @endphp
                                @if(count($backpackerArrivals) > 0)
                                @foreach ($backpackerArrivals as $backpackerArrival)
                                <tr>
                                    <td class="text-right"> {{$backpackerArrivalsCounter++}}</td>
                                    <td> {{$backpackerArrival->unitNumber}} </td>
                                    <td> {{$backpackerArrival->firstName}} {{$backpackerArrival->lastName}} </td>
                                    <td> {{$backpackerArrival->serviceName}} </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center" style="font-style:italic;"> No accommodations to show </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h6> Today's Transactions </h6>
                        <table class="table table-sm table-bordered" style="font-size:.90em;">
                            <!--thread>
                                <tr>
                                    <th class="text-center" colspan="6"> Glamping Accommodation </th>
                                </tr>
                            <thread-->
                            <tbody>
                                <tr>
                                    <td class="text-center" style="width:6%;"> No. </td>
                                    <td class="text-center"> Guest name </td>
                                    <td class="text-center"> Package availed </td>
                                    <td class="text-center"> Quantity </td>
                                    <td class="text-center" style="width:15%;"> Amount paid </td>
                                    <td class="text-center" style="width:15%;"> Balance </td>
                                </tr>
                                @php
                                    $paymentsCounter = 1;
                                @endphp
                                @if(count($payments) > 0)
                                @foreach ($payments as $payment)
                                <tr>
                                    <td> {{$paymentsCounter++}} </td>
                                    <td> {{$payment->firstName}} {{$payment->lastName}} </td>
                                    <td> {{$payment->serviceName}} </td>
                                    <td class="text-right"> {{$payment->quantity}} </td>
                                    <td class="text-right"> ₱ {{number_format((float)($payment->amount), 2, '.', '')}} </td>
                                    <td class="text-right"> ₱ {{number_format((float)($payment->balance), 2, '.', '')}} </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="text-center" style="font-style:italic;"> No payments to show </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
    </div>
@endsection