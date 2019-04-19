@extends('layouts.app')

@section('content')
    <div class="col-md-12 text-center lodging-tabs">
        <nav class="nav nav-pills centered-pills">
            <a class="nav-item nav-link" style="color:#505050" href="/glamping">Physical View</a>
            <a class="nav-item nav-link active" style="background-color:#505050" href="#">Calendar View</a>
        </nav>
    </div>
    <div class="container-fluid lodging-tabs mx-1 pt-1">
        <ul class="nav nav-tabs w-100 pt-0">
            <li class="nav-item">
                <a class="nav-link active" href="/calendar-glamping">Glamping</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="color:#505050;" href="/calendar-backpacker">Backpacker</a>
            </li>
        </ul>
    </div>    
    <form>
        <div class="container col-md-6 offset-3 row px-5" style="padding-left:5.5em;">
            <div class="form-group px-2">
                <!--label class="mb-0" for="checkin" style="padding-right:0;">Check-in date</label-->
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                        </span>
                    </div>
                    <input class="form-control glampingCalendarInputs" id="glampingCalendarFrom" type="date" name="glampingCalendarFrom" maxlength="15" placeholder="" value="<?php echo date("Y-m-d");?>" required>
                </div>
            </div>
            <span>-</span>
            <div class="form-group px-2">
                <!--label class="mb-0" for="checkout" style="padding-right:0;">Check-out date</label-->
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                        </span>
                    </div>
                    <input class="form-control glampingCalendarInputs" type="date" id="glampingCalendarTo" name="glampingCalendarTo" maxlength="15" placeholder="" value="" required>
                </div>
            </div>
            <div>
                <a id="loadCalendarGlamping">
                    <button class="btn btn-sm btn-success">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </a>
            </div>
        </div>
    </form>
    <div class="container-fluid scrollbar-near-moon" style="overflow-y:auto; height:62vh; overflow-x:auto;">
        <table class="table table-sm">
        <thead id="glampingCalendarHead">
            <tr class="pt-5">
            <th style="text-align:center; position:sticky; top:0; background-color:rgb(233, 236, 239); z-index:100;"></th>            
            @if(count($dates) > 0)
            @foreach($dates as $date)
                <td style="text-align:center; position:sticky; top:0; background-color:rgb(233, 236, 239);" scope="col" colspan="2">
                    {{\Carbon\Carbon::parse($date)->format('D')}}
                    <hr class="py-0 my-0">
                    {{\Carbon\Carbon::parse($date)->format('M j')}}
                </td>
            @endforeach
            @endif
            </tr>
        </thead>
        <tbody>
            <!--tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td></td>
            </tr-->
            @if(count($units) > 0)
            @foreach($units as $unit)
                <tr>
                <td scope="row" style="text-align:center; position:sticky; left:0; background-color:rgb(233, 236, 239);">{{$unit->unitNumber}}</td>
                @foreach($dates as $date)
                @php
                    $idAM = $unit->id.'-'.$unit->unitNumber.(string)$date.'AM';
                    $idPM = $unit->id.'-'.$unit->unitNumber.(string)$date.'PM';

                    //$hitAM = false;
                    //$hitPM = false;
                    //$hit = false;

                    $withCheckin = false;
                    $withCheckout = false;
                    $withBoth = false;
                    $inBetween = false;

                    $guestNameToCheckin = '';
                    $guestNameToCheckout = '';

                    $guestNameInBetween = '';

                    $occupiedColor = 'rgb(255, 109, 135)';
                    $reservedColor = 'rgb(47, 228, 180)';

                    $occupiedStartColor = 'rgb(215, 69, 95)';
                    $reservedStartColor = 'rgb(7, 198, 140)';

                    $checkinIsAccommodation = false;
                    $checkoutIsAccommodation = false;

                    $guestName = '';

                    for($index = 0; $index < count($blockDates); $index++) {

                        if(($unit->unitNumber == $blockDates[$index]->unitNumber) &&
                           ($date >= \Carbon\Carbon::parse($blockDates[$index]->checkinDatetime)->format('Y-m-d')) &&
                           ($date <= \Carbon\Carbon::parse($blockDates[$index]->checkoutDatetime)->format('Y-m-d'))) {
                            
                            //$guestName = '';
                            
                            $selectedUnitID = $blockDates[$index]->unitID;  
                            
                            if($date == \Carbon\Carbon::parse($blockDates[$index]->checkinDatetime)->format('Y-m-d')) {
                                $withCheckin = true;
                                $guestNameToCheckin = $blockDates[$index]->firstName.' '.$blockDates[$index]->lastName;

                                if (isset($blockDates[$index]->accommodationID)) {
                                    $checkinIsAccommodation = true;
                                } else if (isset($blockDates[$index]->reservationID)) {
                                    $checkinIsAccommodation = false;
                                    $checkinReservationID =  $blockDates[$index]->reservationID;
                                }

                            } else if ($date == \Carbon\Carbon::parse($blockDates[$index]->checkoutDatetime)->format('Y-m-d')) {
                                $withCheckout = true;
                                $guestNameToCheckout = $blockDates[$index]->firstName.' '.$blockDates[$index]->lastName;

                                if (isset($blockDates[$index]->accommodationID)) {
                                    $checkoutIsAccommodation = true;
                                } else if (isset($blockDates[$index]->reservationID)) {
                                    $checkoutIsAccommodation = false;                                          
                                    $checkoutReservationID =  $blockDates[$index]->reservationID;
                                }

                            } else if (($date > \Carbon\Carbon::parse($blockDates[$index]->checkinDatetime)->format('Y-m-d') &&
                                       ($date < \Carbon\Carbon::parse($blockDates[$index]->checkoutDatetime)->format('Y-m-d')))) {
                                $inBetween = true;
                                $guestNameInBetween = $blockDates[$index]->firstName.' '.$blockDates[$index]->lastName;

                                if (isset($blockDates[$index]->accommodationID)) {
                                    $checkinIsAccommodation = true;
                                } else if (isset($blockDates[$index]->reservationID)) {
                                    $checkinIsAccommodation = false;                                          
                                    $reservationID =  $blockDates[$index]->reservationID;
                                }
                            }                            
                        }
                    }

                    if($withCheckin && $withCheckout) {
                        $withBoth = true;
                        $withCheckin = false;
                        $withCheckout = false;
                    }
                @endphp
                {{--@if($isAccommodation)--}}
                    @if($withCheckin && $checkinIsAccommodation)
                    <td scope="col" id="{{$idAM}}" style="padding:0;">
                        {{--<a data-toggle="modal" data-target="#checkin-reserve" class="load-calendar-units" style="cursor:pointer;height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>--}}
                    </td>                
                    <td scope="col" id="{{$idPM}}" style="background-color:{{$occupiedStartColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckin}}</b><br><em>Click to view</em>" href="/edit-details/{{$selectedUnitID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>

                    @elseif($withCheckin && !($checkinIsAccommodation))
                    <td scope="col" id="{{$idAM}}" style="padding:0;">
                        {{--<a data-toggle="modal" data-target="#checkin-reserve" class="load-calendar-units" style="cursor:pointer;height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>--}}
                    </td>      
                    <td scope="col" id="{{$idPM}}" style="background-color:{{$reservedStartColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckin}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$checkinReservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>
                    
                    @elseif($withCheckout && $checkoutIsAccommodation)                
                    <td scope="col" id="{{$idAM}}" style="background-color:{{$occupiedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckout}}</b><br><em>Click to view</em>" href="/edit-details/{{$selectedUnitID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>             
                    <td scope="col" id="{{$idPM}}" style="padding:0;">
                        <a data-toggle="modal" data-target="#checkin-reserve" class="load-calendar-units" style="cursor:pointer;height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>

                    @elseif($withCheckout && !($checkoutIsAccommodation))               
                    <td scope="col" id="{{$idAM}}" style="background-color:{{$reservedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckout}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$checkoutReservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>             
                    <td scope="col" id="{{$idPM}}" style="padding:0;">
                        <a data-toggle="modal" data-target="#checkin-reserve" class="load-calendar-units" style="cursor:pointer;height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>
                    
                    @elseif($inBetween && $checkinIsAccommodation)                
                    <td scope="col" id="{{$idAM}}" style="background-color:{{$occupiedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameInBetween}}</b><br><em>Click to view</em>" href="/edit-details/{{$selectedUnitID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>                
                    <td scope="col" id="{{$idPM}}" style="background-color:{{$occupiedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameInBetween}}</b><br><em>Click to view</em>" href="/edit-details/{{$selectedUnitID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>

                    @elseif($inBetween && !($checkinIsAccommodation))              
                    <td scope="col" id="{{$idAM}}" style="background-color:{{$reservedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameInBetween}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$reservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>                
                    <td scope="col" id="{{$idPM}}" style="background-color:{{$reservedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameInBetween}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$reservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>

                    @elseif($withBoth && $checkoutIsAccommodation && !($checkinIsAccommodation))                
                    <td scope="col" id="{{$idAM}}" style="background-color:{{$occupiedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckout}}</b><br><em>Click to view</em>" href="/edit-details/{{$selectedUnitID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>                
                    <td scope="col" id="{{$idPM}}" style="background-color:{{$reservedStartColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckin}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$checkinReservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>

                    @elseif($withBoth && !($checkoutIsAccommodation) && !($checkinIsAccommodation))                
                    <td scope="col" id="{{$idAM}}" style="background-color:{{$reservedColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckout}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$checkoutReservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>                
                    <td scope="col" id="{{$idPM}}" style="background-color:{{$reservedStartColor}}; padding:0;">
                        <a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<b>{{$guestNameToCheckin}}</b><br><em>Click to view</em>" href="/view-reservation-details/{{$selectedUnitID}}/{{$checkinReservationID}}" style="height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>

                    @else
                    
                    <td scope="col" id="{{$idAM}}" style="padding:0;">
                        <a data-toggle="modal" data-target="#checkin-reserve" class="load-calendar-units" style="cursor:pointer;height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>                
                    <td scope="col" id="{{$idPM}}" style="padding:0;">
                        <a data-toggle="modal" data-target="#checkin-reserve" class="load-calendar-units" style="cursor:pointer;height:100%;width:100%;display: block; text-decoration:none;">&nbsp;</a>
                    </td>
                    @endif
                @endforeach
                </tr>
            @endforeach
            @endif
        </tbody>
        </table>                  
    </div>
    <!-- Check-in or reserve modal -->
    <div class="modal fade right" id="checkin-reserve" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <h4 id="modal-head2"><h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">×</span>
                    </button>
                </div>
                <!--Body-->
                <div class="modal-body" id="modal-body-empty">
                    <!--div class="col-md-12">
                        <h5 class="text-center mb-4">Choose action:</h5>
                    </div-->
                </div>
                <!--Footer-->
                <div class="modal-footer justify-content-right">
                    <a href="" id="checkinMain">
                        <button type="button" class="btn btn-primary">Check-in</button>
                    </a>
                    <a href="" id="reserveEmpty">
                        <button type="button" class="btn btn-secondary">Add reservation</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
 