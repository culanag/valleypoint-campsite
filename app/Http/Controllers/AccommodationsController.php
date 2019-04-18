<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Arr;
use App\Guests;
use App\Accommodation;
use App\AccommodationUnits;
use App\Units;
use App\Services;
use App\Charges;
use App\Payments;
use Carbon\Carbon;
use Auth;

class AccommodationsController extends Controller
{
    /**
     * Show the check in form
     *
     * @return \Illuminate\Http\Response
     */
    public function showCheckinForm($unitID)
    {
        $unit = DB::table('units')
        ->where('id', '=', $unitID)
        ->get();

        $unitSource = DB::table('units')
        ->select('units.unitNumber')
        ->where('units.unitType', '=', 'tent')
        ->orderBy('id', 'ASC')
        ->get();
        //->toArray();

        //return $unitSource;

        return view('lodging.checkinGlamping')->with('unit', $unit)->with('unitSource', $unitSource);
    }

    /**
     * Show the check in form
     *
     * @return \Illuminate\Http\Response
     */
    public function showCheckinFromFinder(Request $request)
    {
        $unitsSelected =  explode(',', $request->input('checkedUnits'));

        $units = array();
        $unitNumber = array();

        for($count = 0; $count < count($unitsSelected); $count++) {
            $unit = DB::table('units')
            ->where('id', '=',$unitsSelected[$count])
            ->get();

            array_push($units, $unit[0]);
            array_push($unitNumber, $unit[0]->unitNumber);
        }

        //return $units;
        $charges = $units;

        $givenCheckinDate =  $request->input('checkin');
        $givenCheckoutDate = $request->input('checkout');        

        $unitSource = DB::table('units')
        ->select('units.unitNumber')
        ->where('units.unitType', '=', 'tent')
        ->orderBy('id', 'ASC')
        ->get();

        return view('lodging.checkinGlamping')->with('unitNumber', $unitNumber)->with('units', $units)->with('charges', $charges)->with('givenCheckinDate', $givenCheckinDate)->with('givenCheckoutDate', $givenCheckoutDate)->with('unitSource', $unitSource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkinGlamping(Request $request)
    {
        $this->validate($request, [
            'contactNumber' => 'required|min:11|max:11',
            'firstName' => 'required|max:30',
            'lastName' => 'required|max:30'
        ]);

        $accommodation = new Accommodation;    
        $unitNumbers = array_map('trim', explode(',', $request->input('unitNumber')));  //for the three for loops

        $accommodation->numberOfPax = $request->input('numberOfPaxGlamping');
        $accommodation->numberOfUnits = $request->input('numberOfUnits');
        $accommodation->userID = Auth::user()->id;
        $accommodation->save();

        $guest = new Guests;
        $guest->lastName = $request->input('lastName');
        $guest->firstName = $request->input('firstName');
        $guest->accommodationID = $accommodation->id;   
        $guest->contactNumber = $request->input('contactNumber');
        $guest->save(); 

        $chargesCount = 0;
        $chargesArray = array();

        for($count = 0; $count < $request->input('numberOfUnits'); $count++) { //for loop two
            
            $accommodationPackage = 'accommodationPackage'.$unitNumbers[$count];
            $checkinDate = 'checkinDate'.$unitNumbers[$count];
            $checkoutDate = 'checkoutDate'.$unitNumbers[$count];

            $totalPrice = 'totalPrice'.$unitNumbers[$count];

            $unit = DB::table('units')->where('unitNumber', '=', $unitNumbers[$count])->select('units.*')->get();

            $accommodationUnit = new AccommodationUnits;
            $accommodationUnit->accommodationID = $accommodation->id;
            $accommodationUnit->unitID = $unit[0]->id;
            $accommodationUnit->status = 'ongoing';
            $accommodationUnit->checkinDatetime = $request->input($checkinDate).' '.'14:00';
            $accommodationUnit->checkoutDatetime = $request->input($checkoutDate).' '.'12:00';
            $accommodationUnit->numberOfPax = $request->input($accommodationPackage);
            $accommodationUnit->serviceID =  $request->input($accommodationPackage);
            $accommodationUnit->save();

            $charges = new Charges;
            $charges->quantity = $request->input($accommodationPackage);
            $charges->totalPrice = $request->input($totalPrice);
            $charges->balance = $request->input($totalPrice);
            $charges->remarks = 'unpaid';
            $charges->accommodationID = $accommodation->id;
            $charges->serviceID = $request->input($accommodationPackage);
            $charges->save();
            $chargesCount++;
            array_push($chargesArray, $charges->id);
        }

        if($request->input('additionalServicesCount') > 0) {
            for($count = 1; $count <= $request->input('additionalServicesCount'); $count++) {
                $additionalServiceID = 'additionalServiceID'.$count;
                $additionalServiceNumberOfPax = 'additionalServiceNumberOfPax'.$count;
                $additionalTotalPrice = 'additionalServiceTotalPrice'.$count;
                if($request->input($additionalServiceID)) {
                    $charges = new Charges;                    
                    $charges->quantity = $request->input($additionalServiceNumberOfPax);
                    $charges->totalPrice = $request->input($additionalTotalPrice);
                    $charges->balance = $request->input($additionalTotalPrice);
                    $charges->remarks = 'unpaid';
                    $charges->accommodationID = $accommodation->id;
                    $charges->serviceID = $request->input($additionalServiceID);
                    $charges->save();
                    $chargesCount++;
                    array_push($chargesArray, $charges->id);
                }
            }
        }

        $amountPaid = $request->input('amountPaid');

        if($request->input('amountPaid') == '') {
            $amountPaid = 0;
        }

        for($count = 0; $count < $chargesCount; $count++) {
            $paymentEntry = 'payment'.$count;
            if($request->input($paymentEntry)) {
                $payment = new Payments;
                $payment->paymentDatetime = Carbon::now();
                
                $chargePrice = $request->input($paymentEntry);

                if(!($amountPaid == 0)) {
                    if(($amountPaid - $chargePrice) >= 0) {
                        $amountPaid -= $chargePrice;
                        $payment->amount = $chargePrice;
                        $payment->paymentStatus = 'full';
                        $payment->chargeID = $chargesArray[$count];
                        $payment->save();
        
                        $charge = Charges::find($chargesArray[$count]);
                        $charge->update([
                            'remarks' => 'full',
                            'balance' => '0'
                        ]);
                        
                    } else if(($amountPaid - $chargePrice) < 0) {
                        $payment->amount = $amountPaid;
                        $payment->paymentStatus = 'partial';
                        $payment->chargeID = $chargesArray[$count];
                        $payment->save();

                        $balance = $chargePrice - $amountPaid;
        
                        $charge = Charges::find($chargesArray[$count]);
                        $charge->update([
                            'remarks' => 'partial',
                            'balance' => $balance
                        ]);    
                        $amountPaid = 0;                    
                    }
                }
            }
        }

        return redirect('/glamping');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //new checkinBackpacker

     public function checkinBackpacker(Request $request){

        //$fuck = DBs::table('gago)');
        
        /*
        $this->validate($request, [
            'contactNumber' => 'required|min:11|max:11',
            'firstName' => 'required|max:30', 
            'lastName' => 'required|max:30'
    ]);*/

    $accommodation = new Accommodation;    
    $unitNumbers = array_map('trim', explode(',', $request->input('unitNumber')));  //for the three for loops

            $accommodation = new Accommodation; 
            $accommodation->numberOfPax = $request->input('numberOfPaxBackpacker');
            $accommodation->userID = Auth::user()->id;
            $accommodation->save(); 

            $unitNumbers = array_map('trim', explode(',', $request->input('roomNumber')));

            //return $unitNumbers;

            //

            $guest = new Guests;
            $guest->lastName = $request->input('lastName');
            $guest->firstName = $request->input('firstName');
            $guest->accommodationID = $accommodation->id;   
            $guest->contactNumber = $request->input('contactNumber');
            $guest->save();
            
            $chargesCount = 0;
            $chargesArray = array();

            $chargesCount = 0;
            $chargesArray = array();
    
            for($count = 0; $count < count($unitNumbers); $count++) { //for loop two
                
                //$accommodationPackage = 'accommodationPackage'.$unitNumbers[$count];
                $checkinDate = 'checkinDate'.$unitNumbers[$count];
                $checkoutDate = 'checkoutDate'.$unitNumbers[$count];
    
                $totalPrice = 'totalPrice'.$unitNumbers[$count];
    
                $unit = DB::table('units')->where('unitNumber', '=', $unitNumbers[$count])->select('units.*')->get();
    
                $accommodationUnit = new AccommodationUnits;
                $accommodationUnit->accommodationID = $accommodation->id;
                $accommodationUnit->unitID = $unit[0]->id;
                $accommodationUnit->numberOfPax = $accommodation->numberOfPax;
                $accommodationUnit->checkinDateTime = $request->input('checkinDate');
                $accommodationUnit->checkoutDateTime = $request->input('checkoutDate');
                $accommodationUnit->save();
            }
        return redirect('/transient-backpacker');
     }

     //old checkinBackpacker

    /*public function checkinBackpacker(Request $request)
    {
        $this->validate($request, [
            'contactNumber' => 'required|min:11|max:11',
            'checkinDate' => 'required', 'checkoutDate' => 'required',
        'firstName' => 'required|max:30', 'lastName' => 'required|max:30'
    ]);

        
    if(count($reservedAccommodations)>0){
        for($count=0;$count<count($reservedAccommodations);$count+1){
            if($request->input('checkinDate')<=$reservedAccommodations[$count]){
            //return redirect()->back()->withInput();    
            return("Nakapasok");
            }else{
                return("Error pre");
            }
        }
    }

        $BeforeAccommodations = DB::table('accommodations')
        ->select('accommodations.checkinDatetime')
        ->whereDate('accommodations.checkinDatetime', '<>', Carbon::now())
        ->get();
        $AfterAccommodations = DB::table('accommodations')
        ->select('accommodations.checkoutDatetime')
        ->whereDate('accommodations.checkinDatetime', '<>', Carbon::now())            
        ->get();

        if($request->input('checkinDate') >= $BeforeAccommodations && $request->input('checkinDate') <= $AfterAccommodations)
        {
            return("Hello");
        }else{
            return $BeforeAccommodations;

        if ($request->input('checkinDate') > $request->input('checkoutDate')){
            return redirect()->back()->withInput();
            
        }

        
        //GAC
        $accommodation = new Accommodation;                 
        $accommodation->numberOfPax = $request->input('numberOfPaxBackpacker');
        $accommodation->checkinDatetime = $request->input('checkinDate').' '.$request->input('checkinTime');
        $accommodation->checkoutDatetime = $request->input('checkoutDate').' '.$request->input('checkoutTime'); 
        $accommodation->serviceID = 5;
        $accommodation->userID = Auth::user()->id;
        //$accommodation->unitID = $request->input('unitID');
        //$accommodation->paymentStatus = $request->input('paymentStatus');
        $accommodation->save();

        //accomodation

        $accommodation->numberOfPax = $request->input('numberOfPaxBackpacker');
        $accommodation->numberOfUnits = $request->input('numberOfBunks');
        $accommodation->userID = Auth::user()->id;
        $accommodation->save();

        $guest = new Guests;
        $guest->lastName = $request->input('lastName');
        $guest->firstName = $request->input('firstName');
        $guest->accommodationID = $accommodation->id;   
        $guest->contactNumber = $request->input('contactNumber');
        $guest->save();

        //guest

        $guest = new Guests;
        $guest->lastName = $request->input('lastName');
        $guest->firstName = $request->input('firstName');
        $guest->accommodationID = $accommodation->id;   
        $guest->contactNumber = $request->input('contactNumber');
        $guest->save(); 

        if ($accommodation->numberOfPax > 1) {
            for ($count = 1; $count < $accommodation->numberOfPax; $count++) {
                $accompanyingGuest = new Guests;

                $lastName = 'lastName'.$count;
                $firstName = 'firstName'.$count;

                $accompanyingGuest->lastName = $request->input($lastName);
                $accompanyingGuest->firstName = $request->input($firstName);
                $accompanyingGuest->accommodationID = $accommodation->id;
                $accompanyingGuest->listedUnder = $guest->id;   
                $accompanyingGuest->save();
            }
        }

        //for loop based from checkin glamping

        $chargesCount = 0;
        $chargesArray = array();

        for($count = 0; $count < $request->input('numberOfBunks'); $count++) { //for loop two; numberOfUnits changed to numberOfBunks
            
            //$accommodationPackage = 'accommodationPackage'.$unitNumbers[$count];
            $checkinDate = 'checkinDate'.$unitNumbers[$count];
            $checkoutDate = 'checkoutDate'.$unitNumbers[$count];

            $totalPrice = 'totalPrice'.$unitNumbers[$count];

            //$unit = DB::table('units')->where('unitNumber', '=', $unitNumbers[$count])->select('units.*')->get();

            $accommodationUnit = new AccommodationUnits;
            $accommodationUnit->accommodationID = $accommodation->id;
            $accommodationUnit->unitID = $unit[0]->id;
            $accommodationUnit->status = 'ongoing';
            $accommodationUnit->checkinDatetime = $request->input($checkinDate).' '.'14:00';
            $accommodationUnit->checkoutDatetime = $request->input($checkoutDate).' '.'12:00';
            $accommodationUnit->numberOfPax = $request->input($accommodationPackage);
            $accommodationUnit->serviceID =  $request->input($accommodationPackage);
            $accommodationUnit->save();

            $charges = new Charges;
            $charges->quantity = $request->input($accommodationPackage);
            $charges->totalPrice = $request->input($totalPrice);
            $charges->balance = $request->input($totalPrice);
            $charges->remarks = 'unpaid';
            $charges->accommodationID = $accommodation->id;
            $charges->serviceID = $request->input($accommodationPackage);
            $charges->save();
            $chargesCount++;
            array_push($chargesArray, $charges->id);
        }

        



        $service = Services::find(5);
        
        $charge = new Charges;
        $charge->quantity = $request->input('numberOfPax');
        $charge->totalPrice = $charge->quantity*$service->price;
        $charge->remarks = $request->input('paymentStatus');
        $charge->accommodationID = $accommodation->id;
        $charge->serviceID = $service->id;
        $charge->save();

        if($request->input('paymentStatus') != 'unpaid') {
            $payment = new Payments;
            $payment->paymentDatetime = Carbon::now();
            $payment->amount = $request->input('amountPaid');
            $payment->paymentStatus = $request->input('paymentStatus');
            $payment->chargeID = $charge->id;
            $payment->save();
        }

        $accommodationUnit = new AccommodationUnits;
        $accommodationUnit->accommodationID = $accommodation->id;
        $accommodationUnit->unitID = $request->input('unitID');
        $accommodationUnit->status = 'ongoing';
        $accommodationUnit->save();

        return redirect('/transient-backpacker');

}*/



    /**
     *  Add reservation guests
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response 
     */

    public function addReservation(Request $request)
    {

        $accommodation = new Accommodation;
        $accommodation->serviceID = '6';
        $accommodation->paymentStatus = 'pending';
        $accommodation->userID = Auth::user()->id;
        $accommodation->unitID = $request->input('unitID');
        $accommodation->checkinDatetime = $request->input('checkinDate').' '.$request->input('checkinTime');
        $accommodation->checkoutDatetime = $request->input('checkoutDate').' '.$request->input('checkoutTime');
        $accommodation->numberOfPax = $request->input('numberOfPax'); 
        $accommodation->save();

        $guest = new Guests;
        $guest->accommodationID = $accommodation->id;
        $guest->lastname = $request->input('lastName');
        $guest->firstname = $request->input('firstName');
        $guest->contactNumber = $request->input('contactNumber');
        $guest->save();

        return redirect ('/glamping');
    }

        /**
     * Display all reservations.
     * 
     * @return \Illuminate\Http\Response
     */
    public function viewReservation()
    {
        $reserve = DB::table('guests')
        ->leftJoin('accommodations', 'accommodations.id', 'guests.accommodationID')
        ->leftJoin('units', 'units.id', 'accommodations.unitID')
        ->leftJoin('services', 'services.id', 'accommodations.serviceID')
        ->select('units.id as unitID', 'accommodations.id as accommodationID', 'guests.id as guestID',
        'services.id as serviceID', 'units.unitNumber', 'units.unitType', 'units.partOf', 'units.capacity',
        'guests.accommodationID', 'guests.lastName', 'guests.firstName', 'guests.listedUnder', 
        'guests.contactNumber','accommodations.unitID', 'accommodations.serviceID', 
        'accommodations.numberOfPax', 'accommodations.paymentStatus', 'accommodations.checkinDatetime',
        'accommodations.checkoutDatetime', 'services.serviceName', 'services.price')
        ->get();
        return view('lodging.view-reserve')->with('reserve', $reserve);
        //return $reserve;

    }
    /**
    * Show the check in backpacker form
    *
    * @return \Illuminate\Http\Response
    */
    public function showCheckinBackpackerForm($unitID)
    {
        $unit = DB::table('units')
        ->where('id', '=', $unitID)
        ->get();
        return view('lodging.checkinBackpacker')->with('unit', $unit);     
        
    }

    /**
     * Show add Reservation form
     * 
     * @return \Illuminate\Http\Response
     */
    public function showAddReserveForm($unitID)
    {
        return view ('lodging.add-reserve')->with('unitID', $unitID);
    }

    /**
     * Checkout glamping guests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkoutGlamping(Request $request)
    {

        //$fuark = DBs::table('luha');
        for($count = 0; $count < $request->input('chargesCount'); $count++) {
            $existingCharge = 'charge'.$count;
            $paymentEntry = 'payment'.$count;
            if($request->input($paymentEntry)) {
                $payment = new Payments;
                $payment->paymentDatetime = Carbon::now();
                $payment->amount = $request->input($paymentEntry);
                $payment->paymentStatus = 'full';
                $payment->chargeID = $request->input($existingCharge);
                $payment->save();

                $charge = Charges::find($request->input($existingCharge));
                $charge->update([
                    'remarks' => 'full'
                ]);
            }
        }

        $additionalChargesCount = 0;
        $additionalChargesArray = array();

        if($request->input('additionalServicesCount') > 0) {
            for($count = 1; $count <= $request->input('additionalServicesCount'); $count++) {
                $additionalServiceID = 'additionalServiceID'.$count;
                $additionalServiceNumberOfPax = 'additionalServiceNumberOfPax'.$count;
                $additionalTotalPrice = 'additionalServiceTotalPrice'.$count;
                if($request->input($additionalServiceID)) {
                    $charges = new Charges;                    
                    $charges->quantity = $request->input($additionalServiceNumberOfPax);
                    $charges->totalPrice = $request->input($additionalTotalPrice);
                    $charges->balance = $request->input($additionalTotalPrice);
                    $charges->remarks = 'unpaid';
                    $charges->accommodationID = $request->input('accommodationID');
                    $charges->serviceID = $request->input($additionalServiceID);
                    $charges->save();
                    $additionalChargesCount++;
                    array_push($additionalChargesArray, $charges->id);
                }
            }
        }

        $firstAdditionalCharge = $request->input('chargesCount'); //get index of newly added charges

        for($count = 0; $count < $additionalChargesCount; $count++) {
            $index = $count+$firstAdditionalCharge;
            $paymentEntry = 'payment'.$index;
            if($request->input($paymentEntry)) {
                $payment = new Payments;
                $payment->paymentDatetime = Carbon::now();
                $payment->amount = $request->input($paymentEntry);
                $payment->paymentStatus = 'full';
                $payment->chargeID = $additionalChargesArray[$count];
                $payment->save();

                $charge = Charges::find($additionalChargesArray[$count]);
                $charge->update([
                    'remarks' => 'full'
                ]);
            }
        }

        $oneUnit = $request->input('checkOutOneUnit');
        if($oneUnit == 1) {
            $units = DB::table('accommodation_units')
            ->where('accommodation_units.accommodationID', '=', $request->input('accommodationID'))
            ->update(array('status' => 'finished','checkoutDatetime' => Carbon::now()));
        } else {
            $unitIDs = explode(',', $request->input('unitCheckout'));
            //return $unitIDs;
            for($count = 0; $count < count($unitIDs); $count++) {
                $units = DB::table('accommodation_units')
                ->where('accommodation_units.accommodationID', '=', $request->input('accommodationID'))
                ->where('accommodation_units.unitID', '=', $unitIDs[$count])
                ->update(array('status' => 'finished','checkoutDatetime' => Carbon::now()));
            }
        } 

        return redirect('/glamping');
    }
}