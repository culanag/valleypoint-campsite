<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guests;
use App\Accommodation;
use App\GuestStay;
use Auth;

class GuestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $guest = new Guests;
        $guest->lastName = 'Tagudar';
        $guest->firstName = 'Vince';
        $guest->contactNumber = '09087018753';
        $guest->numberOfPax = '2';
        $guest->save();
        
        $accommodation = new Accommodation;
        $accommodation->accommodationType = 'transient';
        $accommodation->price = '3500';
        $accommodation->paymentStatus = 'pending';
        $accommodation->staffID = '1';
        $accommodation->unitID = '3';
        
        $guest->accommodation()->save($accommodation);

        return 'Hello';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addGuest()
    {
        //
        $guest = new Guests;
        $guest->lastName = 'Tagudar';
        $guest->firstName = 'Vince';
        $guest->contactNumber = '09087018753';
        $guest->numberOfPax = '2';
        $guest->save();
        
        $accommodation = new Accommodation;
        $accommodation->accommodationType = 'transient';
        $accommodation->price = '3500';
        $accommodation->paymentStatus = 'pending';
        $accommodation->staffID = '1';
        $accommodation->unitID = '3';
        
        $guest->accommodation()->save($accommodation);

        return 'Hello';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkin(Request $request)
    {
        //
        $guest = new Guests;
        $guest->lastName = $request->input('lastName');
        $guest->firstName = $request->input('firstName');
        $guest->contactNumber = $request->input('contactNumber');
        $guest->numberOfPax = $request->input('numberOfPax');
        $guest->save();

        $accommodation = new Accommodation;        
        $accommodation->accommodationType = 'glamping';
        $accommodation->price = '3500';
        $accommodation->paymentStatus = 'pending';
        $accommodation->userID = Auth::user()->id;
        $accommodation->unitID = $request->input('unitID');                
        $accommodation->save();

        $guestStay = new GuestStay;
        $guestStay->guestID = $guest->id;
        $guestStay->accommodationID = $accommodation->id;
        //$guestStay->checkinDatetime = $request->input('checkinDate')+' '+$request->input('checkinTime');
        //$guestStay->checkoutDatetime = $request->input('checkoutDate')+' '+$request->input('checkoutTime');
        $guestStay->checkinDatetime = '2019-03-27 15:45:21';
        $guestStay->checkoutDatetime = '2019-03-29 15:45:21';
        return redirect('/glamping');
    }

    /**
     * Show the check in form
     *
     * @return \Illuminate\Http\Response
     */
    public function showCheckinForm($unitID)
    {
        return view('lodging.checkin')->with('unitID', $unitID);
    }
}
