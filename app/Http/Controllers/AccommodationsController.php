<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Guests;
use App\Accommodation;
use App\GuestStay;
use App\Units;
use Auth;

class AccommodationsController extends Controller
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
     * Show the check in form
     *
     * @return \Illuminate\Http\Response
     */
    public function showCheckinForm($unitID)
    {
        return view('lodging.checkin')->with('unitID', $unitID);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkin(Request $request)
    {
        $accommodation = new Accommodation;         
        $accommodation->serviceID = '4';
        $accommodation->unitID = $request->input('unitID');
        $accommodation->numberOfPax = $request->input('numberOfPax');
        $accommodation->paymentStatus = 'pending';
        $accommodation->userID = Auth::user()->id;
        $accommodation->checkinDatetime = $request->input('checkinDate').' '.$request->input('checkinTime');
        $accommodation->checkoutDatetime = $request->input('checkoutDate').' '.$request->input('checkoutTime'); 
        $accommodation->save();

        $guest = new Guests;
        $guest->lastName = $request->input('lastName');
        $guest->firstName = $request->input('firstName');
        $guest->accommodationID = $accommodation->id;   
        $guest->contactNumber = $request->input('contactNumber');
        $guest->save();

        if ($accommodation->numberOfPax > 1) {
            $guest2 = new Guests;
            $guest2->lastName = $request->input('lastName1');
            $guest2->firstName = $request->input('firstName1');
            $guest2->accommodationID = $accommodation->id;
            $guest2->listedUnder = $guest->id;   
            $guest2->contactNumber = $request->input('contactNumber1');
            $guest2->save();

            if ($accommodation->numberOfPax > 2) {
                $guest3 = new Guests;
                $guest3->lastName = $request->input('lastName2');
                $guest3->firstName = $request->input('firstName2');
                $guest3->accommodationID = $accommodation->id;
                $guest3->listedUnder = $guest->id;   
                $guest3->contactNumber = $request->input('contactNumber2');
                $guest3->save();
    
                
            }
        }

        $unit = Units::find($request->input('unitID'));
        $unit->update([
            'status' => 'occupied'
        ]);

        return redirect('/glamping');
    }
}
