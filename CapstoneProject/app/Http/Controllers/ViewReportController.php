<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use PDF;

class ViewReportController extends Controller
{
    //
    
    public function getQueryReport(Request $req){
        $SelectedReport = trim($req->input('SelectedReport'));
        $IncludeDeleted = $req->input('IncludeDeleted');
        $GeneratedReport;
        if($SelectedReport == "Accomodations"){
            $GeneratedReport = $this->getAccomodations($IncludeDeleted);
        }
        else if($SelectedReport == "Beach Activities"){
            $GeneratedReport = $this->getBeachActivities($IncludeDeleted);
        }
        else if($SelectedReport == "Boats"){
            $GeneratedReport = $this->getBoats($IncludeDeleted);
        }
        else if($SelectedReport == "Cottages Only"){
            $GeneratedReport = $this->getCottages($IncludeDeleted);
        }
        else if($SelectedReport == "Cottage Types Only"){
            $GeneratedReport = $this->getCottageTypes($IncludeDeleted);
        }
        else if($SelectedReport == "Customers"){
            $GeneratedReport = $this->getCustomers($IncludeDeleted);
        }
        else if($SelectedReport == "Fees"){
            $GeneratedReport = $this->getFees($IncludeDeleted);
        }
        else if($SelectedReport == "Inoperational Dates"){
            $GeneratedReport = $this->getInoperationalDates($IncludeDeleted);
        }
        else if($SelectedReport == "Packages"){
            $GeneratedReport = $this->getPackages($IncludeDeleted);
        }
        else if($SelectedReport == "Rental Items"){
            $GeneratedReport = $this->getItems($IncludeDeleted);
        }
        else if($SelectedReport == "Rooms & Cottages"){
            $GeneratedReport = $this->getRoomsCottages($IncludeDeleted);
        }
        else if($SelectedReport == "Rooms Only"){
            $GeneratedReport = $this->getRooms($IncludeDeleted);
        }
        else if($SelectedReport == "Room Types Only"){
            $GeneratedReport = $this->getRoomTypes($IncludeDeleted);
        }

        return response()->json($GeneratedReport);
    }

    public function PrintQueryReport(Request $request) {

        $SelectedReport = $request->input('PrintSelectedReport');
        $IncludeDeleted = $request->input('PrintIncludeDeleted');

        $ReservationReport = trim($request->input('rReservationReport'));
        $ReservationMonth = trim($request->input('rReservationMonth'));
        $ReservationYear = trim($request->input('rReservationYear'));
        $DailyReservation = trim($request->input('rDailyReservation'));

        var_dump($ReservationReport);

        if($SelectedReport == "Accomodations") {
            $GeneratedReport = $this->getAccomodations($IncludeDeleted);
        }else if($SelectedReport == "Beach Activities") {
            $GeneratedReport = $this->getBeachActivities($IncludeDeleted);
        }else if($SelectedReport == "Boats") {
            $GeneratedReport = $this->getBoats($IncludeDeleted);
        }else if($SelectedReport == "Cottages Only") {
            $GeneratedReport = $this->getCottages($IncludeDeleted);
        }else if($SelectedReport == "Cottage Types Only") {
            $GeneratedReport = $this->getCottageTypes($IncludeDeleted);
        }else if($SelectedReport == "Customers") {
            $GeneratedReport = $this->getCustomers($IncludeDeleted);
        }else if($SelectedReport == "Fees") {
            $GeneratedReport = $this->getFees($IncludeDeleted);
        }else if($SelectedReport == "Inoperational Dates") {
            $GeneratedReport = $this->getInoperationalDates($IncludeDeleted);
        }else if($SelectedReport == "Packages") {
            $GeneratedReport = $this->getPackages($IncludeDeleted);
        }else if($SelectedReport == "Rental Items") {
            $GeneratedReport = $this->getItems($IncludeDeleted);
        }else if($SelectedReport == "Reservations") {
            $GeneratedReport = $this->getReservations($ReservationReport, $ReservationMonth, $ReservationYear, $DailyReservation);
        }else if($SelectedReport == "Rooms & Cottages") {
            $GeneratedReport = $this->getRoomsCottages($IncludeDeleted);
        }else if($SelectedReport == "Rooms Only") {
            $GeneratedReport = $this->getRooms($IncludeDeleted);
        }else if($SelectedReport == "Room Types Only") {
            $GeneratedReport = $this->getRoomTypes($IncludeDeleted);
        }


        $dtmNow = Carbon::now('Asia/Manila');
        $dateNow = $dtmNow->toFormattedDateString();

        $pdf = PDF::loadview('pdf.query_report', ['name' => $SelectedReport, 'date' => $dateNow, 'queries' => $GeneratedReport])->setPaper('letter', 'landscape');
        return $pdf->stream();

    }

    public function getReservationReport(Request $req){
        $ReservationReport = trim($req->input('ReservationReport'));
        $ReservationMonth = trim($req->input('ReservationMonth'));
        $ReservationYear = trim($req->input('ReservationYear'));
        $DailyReservation = trim($req->input('DailyReservation'));

        if($ReservationReport == "Daily"){
            $DailyReservation = Carbon::parse($DailyReservation)->format('Y-m-d');
            $ReservationInfo = DB::table('tblReservationDetail as a')
                        ->join ('tblCustomer as b', 'a.strResDCustomerID', '=' , 'b.strCustomerID')
                        ->select('a.strReservationID',
                                 DB::raw('CONCAT(b.strCustFirstName , " " , b.strCustMiddleName , " " , b.strCustLastName) AS Name'),
                                 'a.intResDNoOfAdults',
                                 'a.intResDNoOfKids',
                                 'a.dtmResDArrival',
                                 'a.dtmResDDeparture',
                                 'b.strCustContact',
                                 'b.strCustEmail',
                                 'b.strCustAddress',
                                 'a.intResDStatus')
                        ->where(DB::raw('Date(a.dtmResDArrival)'), '=', $DailyReservation)
                        ->get();
        }

        else{
            $ReservationMonth = Carbon::parse($ReservationMonth)->format('m');
            $ReservationInfo = DB::table('tblReservationDetail as a')
                        ->join ('tblCustomer as b', 'a.strResDCustomerID', '=' , 'b.strCustomerID')
                        ->select('a.strReservationID',
                                 DB::raw('CONCAT(b.strCustFirstName , " " , b.strCustMiddleName , " " , b.strCustLastName) AS Name'),
                                 'a.intResDNoOfAdults',
                                 'a.intResDNoOfKids',
                                 'a.dtmResDArrival',
                                 'a.dtmResDDeparture',
                                 'b.strCustContact',
                                 'b.strCustEmail',
                                 'b.strCustAddress',
                                 'a.intResDStatus')
                        ->whereMonth('dtmResDArrival', '=', $ReservationMonth)
                        ->whereYear('dtmResDArrival', '=', $ReservationYear)
                        ->get();
        }

        foreach($ReservationInfo as $Info){
            $Info->dtmResDArrival = Carbon::parse($Info->dtmResDArrival)->format('M j, Y');
            $Info->dtmResDDeparture = Carbon::parse($Info->dtmResDDeparture)->format('M j, Y');
            if($Info->intResDStatus == 1){
                $Info->intResDStatus = "Pending Reservation";
            }
            else if($Info->intResDStatus == 2){
                $Info->intResDStatus = "Confirmed Reservation";
            }
            else if($Info->intResDStatus == 3){
                $Info->intResDStatus = "Cancelled Reservation";
            }
            else if($Info->intResDStatus == 4){
                $Info->intResDStatus = "Currently in resort";
            }
            else if($Info->intResDStatus == 5){
                $Info->intResDStatus = "Reservation Finished";
            }
        }

        return response()->json($ReservationInfo);
    }
    
    //get Packages
    public function getPackages($IncludeDeleted){
        if($IncludeDeleted == 1){
            $Packages = DB::table('tblPackage as a')
                        ->join ('tblPackagePrice as b', 'a.strPackageID', '=' , 'b.strPackageID')
                        ->select('a.strPackageID',
                                 'a.strPackageName',
                                 'a.strPackageStatus',
                                 'a.intPackageDuration',
                                 'b.dblPackagePrice',
                                 'a.intPackagePax',
                                 'a.strPackageDescription',
                                 'a.intBoatFee')
                        ->where('b.dtmPackagePriceAsOf',"=", DB::raw("(SELECT MAX(dtmPackagePriceAsOf) FROM tblPackagePrice WHERE strPackageID = a.strPackageID)"))
                        ->where(function($query){
                            $query->where('a.strPackageStatus', '=', 'Available')
                                  ->orWhere('a.strPackageStatus', '=', "Unavailable");
                        })
                        ->get();
        }
        else{
            $Packages = DB::table('tblPackage as a')
                        ->join ('tblPackagePrice as b', 'a.strPackageID', '=' , 'b.strPackageID')
                        ->select('a.strPackageID',
                                 'a.strPackageName',
                                 'a.strPackageStatus',
                                 'a.intPackageDuration',
                                 'b.dblPackagePrice',
                                 'a.intPackagePax',
                                 'a.strPackageDescription',
                                 'a.intBoatFee')
                        ->where('b.dtmPackagePriceAsOf',"=", DB::raw("(SELECT MAX(dtmPackagePriceAsOf) FROM tblPackagePrice WHERE strPackageID = a.strPackageID)"))
                        ->get();
        }

        foreach($Packages as $Package){
            if($Package->intBoatFee == "1"){
                $Package->intBoatFee = "Free";
            }
            else{
                $Package->intBoatFee = "Not Free";
            }
        }

        return $Packages;        
    }


    //get accomodations
   public function getAccomodations($IncludeDeleted){
        $GeneratedReport;
        if($IncludeDeleted == 1){
            $GeneratedReport = DB::table('tblRoomType as a')
                            ->join ('tblRoomRate as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                            ->select('a.strRoomTypeID', 
                                     'a.strRoomType', 
                                     'a.intRoomTCapacity', 
                                     'a.intRoomTNoOfBeds', 
                                     'a.intRoomTNoOfBathrooms', 
                                     'a.intRoomTAirconditioned',
                                     'a.intRoomTCategory',
                                     'b.dblRoomRate', 
                                     'a.strRoomDescription',
                                     'a.intRoomTDeleted')
                            ->where('b.dtmRoomRateAsOf',"=", DB::raw("(SELECT max(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = a.strRoomTypeID)"))
                            ->orderBy('strRoomType')
                            ->get();
        }
        else{
            $GeneratedReport = DB::table('tblRoomType as a')
                            ->join ('tblRoomRate as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                            ->select('a.strRoomTypeID', 
                                     'a.strRoomType', 
                                     'a.intRoomTCapacity', 
                                     'a.intRoomTNoOfBeds', 
                                     'a.intRoomTNoOfBathrooms', 
                                     'a.intRoomTAirconditioned',
                                     'a.intRoomTCategory',
                                     'b.dblRoomRate', 
                                     'a.strRoomDescription',
                                     'a.intRoomTDeleted')
                            ->where([['b.dtmRoomRateAsOf',"=", DB::raw("(SELECT max(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = a.strRoomTypeID)")], ['a.intRoomTDeleted',"=", "1"]])
                            ->orderBy('strRoomType')
                            ->get(); 
        }

        foreach ($GeneratedReport as $Accomodation) {
            if($Accomodation->intRoomTAirconditioned == '1'){
                $Accomodation->intRoomTAirconditioned = 'Yes';
            }
            else{
                $Accomodation->intRoomTAirconditioned = 'No';
            }

            if($Accomodation->intRoomTCategory == '1'){
                $Accomodation->intRoomTCategory = 'Room';
            }
            else{
                $Accomodation->intRoomTCategory = 'Cottage';
            }

            if($Accomodation->intRoomTDeleted == '1'){
                $Accomodation->intRoomTDeleted = 'Available';
            }
            else{
                $Accomodation->intRoomTDeleted = 'Deleted';
            }
        }
        
        return $GeneratedReport;
    }
    
    //get Beach Activities
    public function getBeachActivities($IncludeDeleted){
        $Activities;
        if($IncludeDeleted == 1){
            $Activities = DB::table('tblBeachActivity as a')
                ->join ('tblBeachActivityRate as b', 'a.strBeachActivityID', '=' , 'b.strBeachActivityID')
                ->select('a.strBeachActivityID',
                         'a.strBeachAName',
                         'a.strBeachAStatus',
                         'b.dblBeachARate',
                         'a.intBeachABoat',
                         'a.strBeachADescription')
                ->where('b.dtmBeachARateAsOf',"=", DB::raw("(SELECT max(dtmBeachARateAsOf) FROM tblBeachActivityRate WHERE strBeachActivityID = a.strBeachActivityID)"))
                ->orderBy('strBeachAName')
                ->get(); 
        }
        else{
                $Activities = DB::table('tblBeachActivity as a')
                ->join ('tblBeachActivityRate as b', 'a.strBeachActivityID', '=' , 'b.strBeachActivityID')
                ->select('a.strBeachActivityID',
                         'a.strBeachAName',
                         'a.strBeachAStatus',
                         'b.dblBeachARate',
                         'a.intBeachABoat',
                         'a.strBeachADescription')
                ->where([['b.dtmBeachARateAsOf',"=", DB::raw("(SELECT max(dtmBeachARateAsOf) FROM tblBeachActivityRate WHERE strBeachActivityID = a.strBeachActivityID)")],['a.strBeachAStatus', '!=', 'deleted']])
                ->orderBy('strBeachAName')
                ->get(); 
        }
        
        foreach ($Activities as $Activity) {

            if($Activity->intBeachABoat == '1'){
                $Activity->intBeachABoat = 'Yes';
            }
            else{
                $Activity->intBeachABoat = 'No';
            }

        }
        
        return $Activities;
    }
    
    //get Boats
    public function getBoats($IncludeDeleted){
        $Boats;
        if($IncludeDeleted == 1){
            $Boats = DB::table('tblBoat as a')
                ->join ('tblBoatRate as b', 'a.strBoatID', '=' , 'b.strBoatID')
                ->select('a.strBoatID', 
                         'a.strBoatName',
                         'a.intBoatCapacity',
                         'b.dblBoatRate',
                         'a.strBoatStatus',
                         'a.strBoatDescription')
                ->where('b.dtmBoatRateAsOf',"=", DB::raw("(SELECT max(dtmBoatRateAsOf) FROM tblBoatRate WHERE strBoatID = a.strBoatID)"))
                ->orderBy('strBoatName')
                ->get();  
        }
        else{
             $Boats = DB::table('tblBoat as a')
                ->join ('tblBoatRate as b', 'a.strBoatID', '=' , 'b.strBoatID')
                ->select('a.strBoatID', 
                         'a.strBoatName',
                         'a.intBoatCapacity',
                         'b.dblBoatRate',
                         'a.strBoatStatus',
                         'a.strBoatDescription')
                ->where('b.dtmBoatRateAsOf',"=", DB::raw("(SELECT max(dtmBoatRateAsOf) FROM tblBoatRate WHERE strBoatID = a.strBoatID)"))
                ->where(function($query){
                    $query->where('a.strBoatStatus', '=', 'Available')
                          ->orWhere('a.strBoatStatus', '=', "Unavailable");
                })
                ->orderBy('strBoatName')
                ->get();   
        }
        
        return $Boats;
    }
    
    //get Cottages
    public function getCottages($IncludeDeleted){
        $Cottages;
        if($IncludeDeleted == 1){
            $Cottages = DB::table('tblRoom as a')
            ->join ('tblRoomType as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
            ->select('a.strRoomID',
                    'b.strRoomType',
                    'a.strRoomName',
                    'a.strRoomStatus')
            ->where('b.intRoomTCategory', '=', '0')
            ->orderBy('strRoomName')
            ->get();
        }
        else{
            $Cottages = DB::table('tblRoom as a')
            ->join ('tblRoomType as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
            ->select('a.strRoomID',
                    'b.strRoomType',
                    'a.strRoomName',
                    'a.strRoomStatus')
            ->where([['a.strRoomStatus', '!=', 'deleted'],['b.intRoomTCategory', '=', '0']])
            ->orderBy('strRoomName')
            ->get();
        }
        
        return $Cottages;
    }
    
    //get Cottage Types
    public function getCottageTypes($IncludeDeleted){
        $GeneratedReport;
        if($IncludeDeleted == 1){
            $GeneratedReport = DB::table('tblRoomType as a')
                            ->join ('tblRoomRate as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                            ->select('a.strRoomTypeID', 
                                     'a.strRoomType', 
                                     'a.intRoomTCapacity', 
                                     'a.intRoomTCategory',
                                     'b.dblRoomRate', 
                                     'a.strRoomDescription',
                                     'a.intRoomTDeleted')
                            ->where([['b.dtmRoomRateAsOf',"=", DB::raw("(SELECT max(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = a.strRoomTypeID)")],['a.intRoomTCategory', '=', '0']])
                            ->orderBy('strRoomType')
                            ->get();
        }
        else{
            $GeneratedReport = DB::table('tblRoomType as a')
                            ->join ('tblRoomRate as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                            ->select('a.strRoomTypeID', 
                                     'a.strRoomType', 
                                     'a.intRoomTCapacity', 
                                     'a.intRoomTCategory',
                                     'b.dblRoomRate', 
                                     'a.strRoomDescription',
                                     'a.intRoomTDeleted')
                            ->where([['b.dtmRoomRateAsOf',"=", DB::raw("(SELECT max(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = a.strRoomTypeID)")], ['a.intRoomTCategory', '=', '0'], ['a.intRoomTDeleted',"=", "1"]])
                            ->orderBy('strRoomType')
                            ->get(); 
        }

        foreach ($GeneratedReport as $Accomodation) {
            if($Accomodation->intRoomTCategory == '1'){
                $Accomodation->intRoomTCategory = 'Room';
            }
            else{
                $Accomodation->intRoomTCategory = 'Cottage';
            }

            if($Accomodation->intRoomTDeleted == '1'){
                $Accomodation->intRoomTDeleted = 'Available';
            }
            else{
                $Accomodation->intRoomTDeleted = 'Deleted';
            }
        }
        
        return $GeneratedReport;
    }
    
    //get Customers
    public function getCustomers($IncludeDeleted){
        $CustomerDetails;
        if($IncludeDeleted == 1){
            $CustomerDetails = DB::table('tblCustomer')->orderBy('strCustLastName')->get();
        }
        else{
            $CustomerDetails = DB::table('tblCustomer')->where('intCustStatus', 1)->orderBy('strCustLastName')->get();
        }
        foreach($CustomerDetails as $Customer){
            if($Customer->strCustGender == "M"){
                $Customer->strCustGender = "Male";
            }
            else{
                $Customer->strCustGender = "Female";
            }
            if($Customer->intCustStatus == 1){
                $Customer->intCustStatus = "Active";
            }
            else{
                $Customer->intCustStatus = "Deleted";
            }
        }
        
        return $CustomerDetails;
    }
    
    //get Fees
    public function getFees($IncludeDeleted){
        $Fees;
        if($IncludeDeleted == 1){
            $Fees = DB::table('tblFee as a')
                ->join ('tblFeeAmount as b', 'a.strFeeID', '=' , 'b.strFeeID')
                ->select('a.strFeeID',
                         'a.strFeeName',
                         'a.strFeeStatus',
                         'b.dblFeeAmount',
                         'a.strFeeDescription')
                ->where('b.dtmFeeAmountAsOf',"=", DB::raw("(SELECT max(dtmFeeAmountAsOf) FROM tblFeeAmount WHERE strFeeID = a.strFeeID)"))
                ->orderBy('strFeeName')
                ->get();
        }
        else{
            $Fees = DB::table('tblFee as a')
                ->join ('tblFeeAmount as b', 'a.strFeeID', '=' , 'b.strFeeID')
                ->select('a.strFeeID',
                         'a.strFeeName',
                         'a.strFeeStatus',
                         'b.dblFeeAmount',
                         'a.strFeeDescription')
                ->where([['b.dtmFeeAmountAsOf',"=", DB::raw("(SELECT max(dtmFeeAmountAsOf) FROM tblFeeAmount WHERE strFeeID = a.strFeeID)")],['a.strFeeStatus', '!=', 'deleted']])
                ->orderBy('strFeeName')
                ->get();
        }
        
        return $Fees;
    }
    
    public function getInoperationalDates($IncludeDeleted){
        if($IncludeDeleted == 1){
            $Dates = DB::table('tblInoperationalDate')->get();
        }
        else{
            $Dates = DB::table('tblInoperationalDate')->where('intDateStatus','!=','0')->get();              
        }

        foreach ($Dates as $Date) {
            if($Date->intDateStatus == '1'){
                $Date->intDateStatus = 'Active';
            }
            else if($Date->intDateStatus == '2'){
                $Date->intDateStatus= 'Inactive';
            }
            else{
                $Date->intDateStatus = 'Deleted';
            }

            $Date->dteStartDate = Carbon::parse($Date->dteStartDate)->format('M j, Y');

            $Date->dteEndDate = Carbon::parse($Date->dteEndDate)->format('M j, Y');
        }

        return $Dates;
    }
    
    // get Items
    public function getItems($IncludeDeleted){
        $Items;
        if($IncludeDeleted == 1){
            $Items = DB::table('tblItem as a')
                ->join ('tblItemRate as b', 'a.strItemID', '=' , 'b.strItemID')
                ->select('a.strItemID',
                         'a.strItemName',
                         'a.intItemQuantity',
                         'b.dblItemRate',
                         'a.strItemDescription',
                         'a.intItemDeleted')
                ->where('b.dtmItemRateAsOf',"=", DB::raw("(SELECT max(dtmItemRateAsOf) FROM tblItemRate WHERE strItemID = a.strItemID)"))
                ->orderBy('strItemName')
                ->get(); 
        }
        else{
            $Items = DB::table('tblItem as a')
                ->join ('tblItemRate as b', 'a.strItemID', '=' , 'b.strItemID')
                ->select('a.strItemID',
                         'a.strItemName',
                         'a.intItemQuantity',
                         'b.dblItemRate',
                         'a.strItemDescription',
                         'a.intItemDeleted')
                ->where([['b.dtmItemRateAsOf',"=", DB::raw("(SELECT max(dtmItemRateAsOf) FROM tblItemRate WHERE strItemID = a.strItemID)")],
                        ['a.intItemDeleted',"=", "1"]])
                ->orderBy('strItemName')
                ->get();  
        }
        
        foreach($Items as $Item){
            if($Item->intItemDeleted == 1){
                $Item->intItemDeleted = "Available";
            }
            else{
                $Item->intItemDeleted = "Deleted";
            }
        }
        
        return $Items;
    }
    
    public function getReservations($one, $two, $three, $four){
        $ReservationReport = $one;
        $ReservationMonth = $two;
        $ReservationYear = $three;
        $DailyReservation = $four;

        if($ReservationReport == "Daily"){
            $DailyReservation = Carbon::parse($DailyReservation)->format('Y-m-d');
            $ReservationInfo = DB::table('tblReservationDetail as a')
                        ->join ('tblCustomer as b', 'a.strResDCustomerID', '=' , 'b.strCustomerID')
                        ->select('a.strReservationID',
                                 DB::raw('CONCAT(b.strCustFirstName , " " , b.strCustMiddleName , " " , b.strCustLastName) AS Name'),
                                 'a.intResDNoOfAdults',
                                 'a.intResDNoOfKids',
                                 'a.dtmResDArrival',
                                 'a.dtmResDDeparture',
                                 'b.strCustContact',
                                 'b.strCustEmail',
                                 'b.strCustAddress',
                                 'a.intResDStatus')
                        ->where(DB::raw('Date(a.dtmResDArrival)'), '=', $DailyReservation)
                        ->get();
        }

        else{
            $ReservationMonth = Carbon::parse($ReservationMonth)->format('m');
            $ReservationInfo = DB::table('tblReservationDetail as a')
                        ->join ('tblCustomer as b', 'a.strResDCustomerID', '=' , 'b.strCustomerID')
                        ->select('a.strReservationID',
                                 DB::raw('CONCAT(b.strCustFirstName , " " , b.strCustMiddleName , " " , b.strCustLastName) AS Name'),
                                 'a.intResDNoOfAdults',
                                 'a.intResDNoOfKids',
                                 'a.dtmResDArrival',
                                 'a.dtmResDDeparture',
                                 'b.strCustContact',
                                 'b.strCustEmail',
                                 'b.strCustAddress',
                                 'a.intResDStatus')
                        ->whereMonth('dtmResDArrival', '=', $ReservationMonth)
                        ->whereYear('dtmResDArrival', '=', $ReservationYear)
                        ->get();
        }

        foreach($ReservationInfo as $Info){
            $Info->dtmResDArrival = Carbon::parse($Info->dtmResDArrival)->format('M j, Y');
            $Info->dtmResDDeparture = Carbon::parse($Info->dtmResDDeparture)->format('M j, Y');
            if($Info->intResDStatus == 1){
                $Info->intResDStatus = "Pending Reservation";
            }
            else if($Info->intResDStatus == 2){
                $Info->intResDStatus = "Confirmed Reservation";
            }
            else if($Info->intResDStatus == 3){
                $Info->intResDStatus = "Cancelled Reservation";
            }
            else if($Info->intResDStatus == 4){
                $Info->intResDStatus = "Currently in resort";
            }
            else if($Info->intResDStatus == 5){
                $Info->intResDStatus = "Reservation Finished";
            }
        }

        return $ReservationInfo;

    }
    
    // get Rooms Cottages
    public function getRoomsCottages($IncludeDeleted){
        $RoomsCottages;
        if($IncludeDeleted == 1){
            $RoomsCottages = DB::table('tblRoom as a')
                        ->join ('tblRoomType as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                        ->select('a.strRoomID',
                                'b.strRoomType',
                                'b.intRoomTCategory',
                                'a.strRoomName',
                                'a.strRoomStatus')
                        ->orderBy('strRoomName')
                        ->get();
        }
        else{
            $RoomsCottages = DB::table('tblRoom as a')
            ->join ('tblRoomType as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
            ->select('a.strRoomID',
                    'b.strRoomType',
                    'b.intRoomTCategory',
                    'a.strRoomName',
                    'a.strRoomStatus')
            ->where('a.strRoomStatus', '!=', 'deleted')
            ->orderBy('strRoomName')
            ->get();
        }
        
        foreach($RoomsCottages as $RoomCottage){
            if($RoomCottage->intRoomTCategory == 1){
                $RoomCottage->intRoomTCategory = "Room";
            }
            else{
                $RoomCottage->intRoomTCategory = "Cottage";
            }
        }
        
        return $RoomsCottages;
        
    }
    
    
    // get Rooms
    public function getRooms($IncludeDeleted){
        $Rooms;
        if($IncludeDeleted == 1){
            $Rooms = DB::table('tblRoom as a')
            ->join ('tblRoomType as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
            ->select('a.strRoomID',
                    'b.strRoomType',
                    'a.strRoomName',
                    'a.strRoomStatus')
            ->where('b.intRoomTCategory', '=', '1')
            ->orderBy('strRoomName')
            ->get();
        }
        else{
            $Rooms = DB::table('tblRoom as a')
            ->join ('tblRoomType as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
            ->select('a.strRoomID',
                    'b.strRoomType',
                    'a.strRoomName',
                    'a.strRoomStatus')
            ->where([['a.strRoomStatus', '!=', 'deleted'],['b.intRoomTCategory', '=', '1']])
            ->orderBy('strRoomName')
            ->get();
        }

        return $Rooms;
    }
    
    
    // get Room Types
    public function getRoomTypes($IncludeDeleted){
        $GeneratedReport;
        if($IncludeDeleted == 1){
            $GeneratedReport = DB::table('tblRoomType as a')
                            ->join ('tblRoomRate as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                            ->select('a.strRoomTypeID', 
                                     'a.strRoomType', 
                                     'a.intRoomTCapacity', 
                                     'a.intRoomTNoOfBeds', 
                                     'a.intRoomTNoOfBathrooms', 
                                     'a.intRoomTAirconditioned',
                                     'a.intRoomTCategory',
                                     'b.dblRoomRate', 
                                     'a.strRoomDescription',
                                     'a.intRoomTDeleted')
                            ->where([['b.dtmRoomRateAsOf',"=", DB::raw("(SELECT max(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = a.strRoomTypeID)")], ['a.intRoomTCategory', '=', '1']])
                            ->orderBy('strRoomType')
                            ->get();
        }
        else{
            $GeneratedReport = DB::table('tblRoomType as a')
                            ->join ('tblRoomRate as b', 'a.strRoomTypeID', '=' , 'b.strRoomTypeID')
                            ->select('a.strRoomTypeID', 
                                     'a.strRoomType', 
                                     'a.intRoomTCapacity', 
                                     'a.intRoomTNoOfBeds', 
                                     'a.intRoomTNoOfBathrooms', 
                                     'a.intRoomTAirconditioned',
                                     'a.intRoomTCategory',
                                     'b.dblRoomRate', 
                                     'a.strRoomDescription',
                                     'a.intRoomTDeleted')
                            ->where([['b.dtmRoomRateAsOf',"=", DB::raw("(SELECT max(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = a.strRoomTypeID)")], ['a.intRoomTDeleted',"=", "1"], ['a.intRoomTCategory', '=', '1']])
                            ->orderBy('strRoomType')
                            ->get(); 
        }

        foreach ($GeneratedReport as $Accomodation) {
            if($Accomodation->intRoomTAirconditioned == '1'){
                $Accomodation->intRoomTAirconditioned = 'Yes';
            }
            else{
                $Accomodation->intRoomTAirconditioned = 'No';
            }

            if($Accomodation->intRoomTCategory == '1'){
                $Accomodation->intRoomTCategory = 'Room';
            }
            else{
                $Accomodation->intRoomTCategory = 'Cottage';
            }

            if($Accomodation->intRoomTDeleted == '1'){
                $Accomodation->intRoomTDeleted = 'Available';
            }
            else{
                $Accomodation->intRoomTDeleted = 'Deleted';
            }
        }
        
        return $GeneratedReport;
    }
    
}
