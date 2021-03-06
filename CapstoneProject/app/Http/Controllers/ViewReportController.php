<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use PDF;

class ViewReportController extends Controller
{
    public function getSalesReport(Request $request) {

        $ReportType = $request->input('ReportType');

        if($ReportType == 'Payment Summary') {

            $SalesReportTimeRange = $request->input('SalesReportTimeRange');
            $GeneratedSalesReport;
            $data = array();
            $PaymentTypes = array(2, 3, 25, 23, 21, 9, 19, 12, 15, 13, 17, 28);

            if($SalesReportTimeRange == 'Daily') {

                $dtmDailyDate = $request->input('dtmDailyDate');
                $date = explode('/', $dtmDailyDate);
                $dtmDailyDate = $date[2] . '-' . $date[0] . '-' . $date[1];

                for($i = 0; $i < count($PaymentTypes) ; $i++) {

                    $sales = $this->getDailySales($dtmDailyDate, $PaymentTypes[$i]);
                    array_push($data, (object) ["quantity" => $sales->quantity, "total" => $sales->total]);

                }

            }else if($SalesReportTimeRange == 'Monthly') {

                $intMonth = $request->input('intMonthlyMonth');
                $intYear = $request->input('intMonthlyYear');

                for($i = 0; $i < count($PaymentTypes) ; $i++) {

                    $sales = $this->getMonthlySales($intMonth, $intYear, $PaymentTypes[$i]);
                    array_push($data, (object) ["quantity" => $sales->quantity, "total" => $sales->total]);

                }

            }else if($SalesReportTimeRange == 'Quarterly') {

                $intQuarter = $request->input('intQuarter');
                $intQuarterYear = $request->input('intQuarterYear');

                for($i = 0; $i < count($PaymentTypes) ; $i++) {

                    $sales = $this->getQuarterlySales($intQuarter, $intQuarterYear, $PaymentTypes[$i]);
                    array_push($data, (object) ["quantity" => $sales->quantity, "total" => $sales->total]);

                }

            }else if($SalesReportTimeRange == 'Annually') {

                $intYearlyYear = $request->input('intYearlyYear');

                for($i = 0; $i < count($PaymentTypes) ; $i++) {

                    $sales = $this->getAnnualSales($intYearlyYear, $PaymentTypes[$i]);
                    array_push($data, (object) ["quantity" => $sales->quantity, "total" => $sales->total]);

                }

            }

            return $data;

        }else if($ReportType == 'Breakdown') {

            $SalesReportTimeRange = $request->input('SalesReportTimeRange');
            $GeneratedSalesReport;

            if($SalesReportTimeRange == 'Annually') {

                $intYearlyYear = $request->input('intYearlyYear');

                $roomtype = $this->getRoomTypeAnnualSales($intYearlyYear);
                $item = $this->getItemAnnualSales($intYearlyYear);
                $beachactivity = $this->getBeachActivityAnnualSales($intYearlyYear);
                $boat = $this->getBoatAnnualSales($intYearlyYear);

                return [$roomtype, $item, $beachactivity, $boat];

            }else if($SalesReportTimeRange == 'Quarterly') {

                $intQuarter = $request->input('intQuarter');
                $intQuarterYear = $request->input('intQuarterYear');

                $start;
                $end;

                if($intQuarter == 1) {

                    $start = 1;
                    $end = 3;

                }if($intQuarter == 2) {

                    $start = 4;
                    $end = 6;

                }if($intQuarter == 3) {

                    $start = 7;
                    $end = 9;

                }if($intQuarter == 4) {

                    $start = 10;
                    $end = 12;

                }

                $roomtype = $this->getRoomTypeQuarterlySales($intQuarterYear, $start, $end);
                $item = $this->getItemQuarterlySales($intQuarterYear, $start, $end);
                $beachactivity = $this->getBeachActivityQuarterlySales($intQuarterYear, $start, $end);
                $boat = $this->getBoatQuarterlySales($intQuarterYear, $start, $end);

                return [$roomtype, $item, $beachactivity, $boat];

            }else if($SalesReportTimeRange == 'Monthly') {

                $intMonth = $request->input('intMonthlyMonth');
                $intYear = $request->input('intMonthlyYear');

                $roomtype = $this->getRoomTypeMonthlySales($intMonth, $intYear);
                $item = $this->getItemMonthlySales($intMonth, $intYear);
                $beachactivity = $this->getBeachActivityMonthlySales($intMonth, $intYear);
                $boat = $this->getBoatMonthlySales($intMonth, $intYear);

                return [$roomtype, $item, $beachactivity, $boat];

            }else if($SalesReportTimeRange == 'Daily') {

                $dtmDailyDate = $request->input('dtmDailyDate');
                $date = explode('/', $dtmDailyDate);
                $dtmDailyDate = $date[2] . '-' . $date[0] . '-' . $date[1];

                $roomtype = $this->getRoomTypeDailySales($dtmDailyDate);
                $item = $this->getItemDailySales($dtmDailyDate);
                $beachactivity = $this->getBeachActivityDailySales($dtmDailyDate);
                $boat = $this->getBoatDailySales($dtmDailyDate);

                return [$roomtype, $item, $beachactivity, $boat];

            }

        }

    }

    public function getBoatDailySales($date) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblBoatSchedule as b', 'a.strReservationID', '=', 'b.strBoatSReservationID')
                ->join('tblBoat as c', 'b.strBoatSBoatID', '=', 'c.strBoatID')
                ->join('tblBoatRate as d', 'c.strBoatID', '=', 'd.strBoatID')
                ->select(
                    'c.strBoatName as description',
                    DB::raw("COUNT(c.strBoatName) as quantity"),
                    'd.dblBoatRate as rate',
                    DB::raw("(COUNT(c.strBoatName) * d.dblBoatRate) as total")
                )
                ->where([
                    ['d.dtmBoatRateAsOf', '=', DB::raw("(SELECT MAX(dtmBoatRateAsOf) FROM tblBoatRate WHERE strBoatID = d.strBoatID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("DATE(dteResDBooking)"), '=', $date]
                ])
                ->groupBy('c.strBoatName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;
        
    }

    public function getBeachActivityDailySales($date) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblAvailBeachActivity as b', 'a.strReservationID', '=', 'b.strAvailBAReservationID')
                ->join('tblBeachActivity as c', 'b.strAvailBABeachActivityID', '=', 'c.strBeachActivityID')
                ->join('tblBeachActivityRate as d', 'c.strBeachActivityID', '=', 'd.strBeachActivityID')
                ->select(
                    'c.strBeachAName as description',
                    DB::raw("COUNT(c.strBeachAName) as quantity"),
                    'd.dblBeachARate as rate',
                    DB::raw("(COUNT(c.strBeachAName) * d.dblBeachARate) as total")
                )
                ->where([
                    ['d.dtmBeachARateAsOf', '=', DB::raw("(SELECT MAX(dtmBeachARateAsOf) FROM tblBeachActivityRate WHERE strBeachActivityID = d.strBeachActivityID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("DATE(dteResDBooking)"), '=', $date]
                ])
                ->groupBy('c.strBeachAName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;
        
    }

    public function getItemDailySales($date) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblRentedItem as b', 'a.strReservationID', '=', 'b.strRentedIReservationID')
                ->join('tblItem as c', 'b.strRentedIItemID', '=', 'c.strItemID')
                ->join('tblItemRate as d', 'c.strItemID', '=', 'd.strItemID')
                ->select(
                    'c.strItemName as description',
                    DB::raw("COUNT(c.strItemName) as quantity"),
                    'd.dblItemRate as rate',
                    DB::raw("(COUNT(c.strItemName) * d.dblItemRate) as total")
                )
                ->where([
                    ['d.dtmItemRateAsOf', '=', DB::raw("(SELECT MAX(dtmItemRateAsOf) FROM tblItemRate WHERE strItemID = d.strItemID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("DATE(dteResDBooking)"), '=', $date]
                ])
                ->groupBy('c.strItemName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;
        
    }

    public function getRoomTypeDailySales($date) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblReservationRoom as b', 'a.strReservationID', '=', 'b.strResRReservationID')
                ->join('tblRoom as c', 'b.strResRRoomID', '=', 'c.strRoomID')
                ->join('tblRoomType as d', 'c.strRoomTypeID', '=', 'd.strRoomTypeID')
                ->join('tblRoomRate as e', 'd.strRoomTypeID', '=', 'e.strRoomTypeID')
                ->select(
                    'd.strRoomType as description',
                    DB::raw("COUNT(d.strRoomType) as quantity"),
                    'e.dblRoomRate as rate',
                    DB::raw("(COUNT(d.strRoomType) * e.dblRoomRate) as total")
                )
                ->where([
                    ['e.dtmRoomRateAsOf', '=', DB::raw("(SELECT MAX(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = e.strRoomTypeID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("DATE(dteResDBooking)"), '=', $date]
                ])
                ->groupBy('d.strRoomType')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getBoatMonthlySales($month, $year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblBoatSchedule as b', 'a.strReservationID', '=', 'b.strBoatSReservationID')
                ->join('tblBoat as c', 'b.strBoatSBoatID', '=', 'c.strBoatID')
                ->join('tblBoatRate as d', 'c.strBoatID', '=', 'd.strBoatID')
                ->select(
                    'c.strBoatName as description',
                    DB::raw("COUNT(c.strBoatName) as quantity"),
                    'd.dblBoatRate as rate',
                    DB::raw("(COUNT(c.strBoatName) * d.dblBoatRate) as total")
                )
                ->where([
                    ['d.dtmBoatRateAsOf', '=', DB::raw("(SELECT MAX(dtmBoatRateAsOf) FROM tblBoatRate WHERE strBoatID = d.strBoatID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year],
                    [DB::raw("MONTH(dteResDBooking)"), '=', $month]
                ])
                ->groupBy('c.strBoatName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;
        
    }

    public function getBeachActivityMonthlySales($month, $year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblAvailBeachActivity as b', 'a.strReservationID', '=', 'b.strAvailBAReservationID')
                ->join('tblBeachActivity as c', 'b.strAvailBABeachActivityID', '=', 'c.strBeachActivityID')
                ->join('tblBeachActivityRate as d', 'c.strBeachActivityID', '=', 'd.strBeachActivityID')
                ->select(
                    'c.strBeachAName as description',
                    DB::raw("COUNT(c.strBeachAName) as quantity"),
                    'd.dblBeachARate as rate',
                    DB::raw("(COUNT(c.strBeachAName) * d.dblBeachARate) as total")
                )
                ->where([
                    ['d.dtmBeachARateAsOf', '=', DB::raw("(SELECT MAX(dtmBeachARateAsOf) FROM tblBeachActivityRate WHERE strBeachActivityID = d.strBeachActivityID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year],
                    [DB::raw("MONTH(dteResDBooking)"), '=', $month]
                ])
                ->groupBy('c.strBeachAName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;
        
    }

    public function getItemMonthlySales($month, $year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblRentedItem as b', 'a.strReservationID', '=', 'b.strRentedIReservationID')
                ->join('tblItem as c', 'b.strRentedIItemID', '=', 'c.strItemID')
                ->join('tblItemRate as d', 'c.strItemID', '=', 'd.strItemID')
                ->select(
                    'c.strItemName as description',
                    DB::raw("COUNT(c.strItemName) as quantity"),
                    'd.dblItemRate as rate',
                    DB::raw("(COUNT(c.strItemName) * d.dblItemRate) as total")
                )
                ->where([
                    ['d.dtmItemRateAsOf', '=', DB::raw("(SELECT MAX(dtmItemRateAsOf) FROM tblItemRate WHERE strItemID = d.strItemID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year],
                    [DB::raw("MONTH(dteResDBooking)"), '=', $month]
                ])
                ->groupBy('c.strItemName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;
        
    }

    public function getRoomTypeMonthlySales($month, $year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblReservationRoom as b', 'a.strReservationID', '=', 'b.strResRReservationID')
                ->join('tblRoom as c', 'b.strResRRoomID', '=', 'c.strRoomID')
                ->join('tblRoomType as d', 'c.strRoomTypeID', '=', 'd.strRoomTypeID')
                ->join('tblRoomRate as e', 'd.strRoomTypeID', '=', 'e.strRoomTypeID')
                ->select(
                    'd.strRoomType as description',
                    DB::raw("COUNT(d.strRoomType) as quantity"),
                    'e.dblRoomRate as rate',
                    DB::raw("(COUNT(d.strRoomType) * e.dblRoomRate) as total")
                )
                ->where([
                    ['e.dtmRoomRateAsOf', '=', DB::raw("(SELECT MAX(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = e.strRoomTypeID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year],
                    [DB::raw("MONTH(dteResDBooking)"), '=', $month]
                ])
                ->groupBy('d.strRoomType')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getBoatQuarterlySales($year, $start, $end) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblBoatSchedule as b', 'a.strReservationID', '=', 'b.strBoatSReservationID')
                ->join('tblBoat as c', 'b.strBoatSBoatID', '=', 'c.strBoatID')
                ->join('tblBoatRate as d', 'c.strBoatID', '=', 'd.strBoatID')
                ->select(
                    'c.strBoatName as description',
                    DB::raw("COUNT(c.strBoatName) as quantity"),
                    'd.dblBoatRate as rate',
                    DB::raw("(COUNT(c.strBoatName) * d.dblBoatRate) as total")
                )
                ->where([
                    ['d.dtmBoatRateAsOf', '=', DB::raw("(SELECT MAX(dtmBoatRateAsOf) FROM tblBoatRate WHERE strBoatID = d.strBoatID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->whereBetween(DB::raw("MONTH(dteResDBooking)"), [$start, $end])
                ->groupBy('c.strBoatName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getBeachActivityQuarterlySales($year, $start, $end) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblAvailBeachActivity as b', 'a.strReservationID', '=', 'b.strAvailBAReservationID')
                ->join('tblBeachActivity as c', 'b.strAvailBABeachActivityID', '=', 'c.strBeachActivityID')
                ->join('tblBeachActivityRate as d', 'c.strBeachActivityID', '=', 'd.strBeachActivityID')
                ->select(
                    'c.strBeachAName as description',
                    DB::raw("COUNT(c.strBeachAName) as quantity"),
                    'd.dblBeachARate as rate',
                    DB::raw("(COUNT(c.strBeachAName) * d.dblBeachARate) as total")
                )
                ->where([
                    ['d.dtmBeachARateAsOf', '=', DB::raw("(SELECT MAX(dtmBeachARateAsOf) FROM tblBeachActivityRate WHERE strBeachActivityID = d.strBeachActivityID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->whereBetween(DB::raw("MONTH(dteResDBooking)"), [$start, $end])
                ->groupBy('c.strBeachAName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getItemQuarterlySales($year, $start, $end) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblRentedItem as b', 'a.strReservationID', '=', 'b.strRentedIReservationID')
                ->join('tblItem as c', 'b.strRentedIItemID', '=', 'c.strItemID')
                ->join('tblItemRate as d', 'c.strItemID', '=', 'd.strItemID')
                ->select(
                    'c.strItemName as description',
                    DB::raw("COUNT(c.strItemName) as quantity"),
                    'd.dblItemRate as rate',
                    DB::raw("(COUNT(c.strItemName) * d.dblItemRate) as total")
                )
                ->where([
                    ['d.dtmItemRateAsOf', '=', DB::raw("(SELECT MAX(dtmItemRateAsOf) FROM tblItemRate WHERE strItemID = d.strItemID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->whereBetween(DB::raw("MONTH(dteResDBooking)"), [$start, $end])
                ->groupBy('c.strItemName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getRoomTypeQuarterlySales($year, $start, $end) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblReservationRoom as b', 'a.strReservationID', '=', 'b.strResRReservationID')
                ->join('tblRoom as c', 'b.strResRRoomID', '=', 'c.strRoomID')
                ->join('tblRoomType as d', 'c.strRoomTypeID', '=', 'd.strRoomTypeID')
                ->join('tblRoomRate as e', 'd.strRoomTypeID', '=', 'e.strRoomTypeID')
                ->select(
                    'd.strRoomType as description',
                    DB::raw("COUNT(d.strRoomType) as quantity"),
                    'e.dblRoomRate as rate',
                    DB::raw("(COUNT(d.strRoomType) * e.dblRoomRate) as total")
                )
                ->where([
                    ['e.dtmRoomRateAsOf', '=', DB::raw("(SELECT MAX(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = e.strRoomTypeID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->whereBetween(DB::raw("MONTH(dteResDBooking)"), [$start, $end])
                ->groupBy('d.strRoomType')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getRoomTypeAnnualSales($year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblReservationRoom as b', 'a.strReservationID', '=', 'b.strResRReservationID')
                ->join('tblRoom as c', 'b.strResRRoomID', '=', 'c.strRoomID')
                ->join('tblRoomType as d', 'c.strRoomTypeID', '=', 'd.strRoomTypeID')
                ->join('tblRoomRate as e', 'd.strRoomTypeID', '=', 'e.strRoomTypeID')
                ->select(
                    'd.strRoomType as description',
                    DB::raw("COUNT(d.strRoomType) as quantity"),
                    'e.dblRoomRate as rate',
                    DB::raw("(COUNT(d.strRoomType) * e.dblRoomRate) as total")
                )
                ->where([
                    ['e.dtmRoomRateAsOf', '=', DB::raw("(SELECT MAX(dtmRoomRateAsOf) FROM tblRoomRate WHERE strRoomTypeID = e.strRoomTypeID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->groupBy('d.strRoomType')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getItemAnnualSales($year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblRentedItem as b', 'a.strReservationID', '=', 'b.strRentedIReservationID')
                ->join('tblItem as c', 'b.strRentedIItemID', '=', 'c.strItemID')
                ->join('tblItemRate as d', 'c.strItemID', '=', 'd.strItemID')
                ->select(
                    'c.strItemName as description',
                    DB::raw("COUNT(c.strItemName) as quantity"),
                    'd.dblItemRate as rate',
                    DB::raw("(COUNT(c.strItemName) * d.dblItemRate) as total")
                )
                ->where([
                    ['d.dtmItemRateAsOf', '=', DB::raw("(SELECT MAX(dtmItemRateAsOf) FROM tblItemRate WHERE strItemID = d.strItemID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->groupBy('c.strItemName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getBeachActivityAnnualSales($year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblAvailBeachActivity as b', 'a.strReservationID', '=', 'b.strAvailBAReservationID')
                ->join('tblBeachActivity as c', 'b.strAvailBABeachActivityID', '=', 'c.strBeachActivityID')
                ->join('tblBeachActivityRate as d', 'c.strBeachActivityID', '=', 'd.strBeachActivityID')
                ->select(
                    'c.strBeachAName as description',
                    DB::raw("COUNT(c.strBeachAName) as quantity"),
                    'd.dblBeachARate as rate',
                    DB::raw("(COUNT(c.strBeachAName) * d.dblBeachARate) as total")
                )
                ->where([
                    ['d.dtmBeachARateAsOf', '=', DB::raw("(SELECT MAX(dtmBeachARateAsOf) FROM tblBeachActivityRate WHERE strBeachActivityID = d.strBeachActivityID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->groupBy('c.strBeachAName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getBoatAnnualSales($year) {

        try {

            $result = DB::table('tblReservationDetail as a')
                ->join('tblBoatSchedule as b', 'a.strReservationID', '=', 'b.strBoatSReservationID')
                ->join('tblBoat as c', 'b.strBoatSBoatID', '=', 'c.strBoatID')
                ->join('tblBoatRate as d', 'c.strBoatID', '=', 'd.strBoatID')
                ->select(
                    'c.strBoatName as description',
                    DB::raw("COUNT(c.strBoatName) as quantity"),
                    'd.dblBoatRate as rate',
                    DB::raw("(COUNT(c.strBoatName) * d.dblBoatRate) as total")
                )
                ->where([
                    ['d.dtmBoatRateAsOf', '=', DB::raw("(SELECT MAX(dtmBoatRateAsOf) FROM tblBoatRate WHERE strBoatID = d.strBoatID)")],
                    ['a.intResDStatus', '!=', 3],
                    [DB::raw("YEAR(dteResDBooking)"), '=', $year]
                ])
                ->groupBy('c.strBoatName')
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){ 
            return $ex->getMessage();
        }

        return $result;

    }

    public function getDailySales($date, $PaymentType) {

        $result = DB::table('tblPayment')
            ->select(
                DB::raw("COUNT(strPayReservationID) as quantity"),
                DB::raw("SUM(dblPayAmount) as total"))
            ->where([['strPayTypeID', '=', $PaymentType], [DB::raw("DATE(tmsCreated)"), '=', $date]])
            ->first();

        return $result;

    }

    public function getMonthlySales($month, $year, $PaymentType) {

        $result = DB::table('tblPayment')
            ->select(
                DB::raw("COUNT(strPayReservationID) as quantity"),
                DB::raw("SUM(dblPayAmount) as total"))
            ->where([['strPayTypeID', '=', $PaymentType], [DB::raw("MONTH(tmsCreated)"), '=', $month], [DB::raw("YEAR(tmsCreated)"), '=', $year]])
            ->first();

        return $result;

    }

    public function getQuarterlySales($quarter, $year, $PaymentType) {

        if($quarter == 1) {

            $start = 1;
            $end = 3;

        }if($quarter == 2) {

            $start = 4;
            $end = 6;

        }if($quarter == 3) {

            $start = 7;
            $end = 9;

        }if($quarter == 4) {

            $start = 10;
            $end = 12;

        }

        $result = DB::table('tblPayment')
            ->select(
                DB::raw("COUNT(strPayReservationID) as quantity"),
                DB::raw("SUM(dblPayAmount) as total"))
            ->where([['strPayTypeID', '=', $PaymentType], [DB::raw("YEAR(tmsCreated)"), '=', $year]])
            ->whereBetween(DB::raw("MONTH(tmsCreated)"), [$start, $end])
            ->first();

        return $result;

    }

    public function getAnnualSales($year, $PaymentType) {

        $result = DB::table('tblPayment')
            ->select(
                DB::raw("COUNT(strPayReservationID) as quantity"),
                DB::raw("SUM(dblPayAmount) as total"))
            ->where([['strPayTypeID', '=', $PaymentType], [DB::raw("YEAR(tmsCreated)"), '=', $year]])
            ->first();

        return $result;

    }
    
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

    public function PrintSalesReport(Request $request) {

        $SalesReportType = $request->input('SalesReportType');

        $sSalesReportTimeRange = $request->input('sSalesReportTimeRange');
        $SalesReportDateInfo;

        if($sSalesReportTimeRange == 'Daily') {

            $SalesReportDateInfo = "Daily Sales Report: " . $request->input('sDailyDate');

        }else if($sSalesReportTimeRange == 'Monthly') {

            $SalesReportDateInfo = "Monthly Sales Report: " . $request->input('sMonthlyMonth') . ' ' . $request->input('sMonthlyYear');

        }else if($sSalesReportTimeRange == 'Quarterly') {

            $SalesReportDateInfo = "Quarterly Sales Report: " . $request->input('sQuarterlyQuarter') . ' ' . $request->input('sQuarterlyYear');

        }else if($sSalesReportTimeRange == 'Annually') {

            $SalesReportDateInfo = "Yearly Sales Report: " . $request->input('sAnnualYear');

        }

        $dtmNow = Carbon::now('Asia/Manila');
        $dateNow = $dtmNow->toFormattedDateString();

        $dblTotalSales = 0;

        if($SalesReportType == 1) {

            $tblPaymentSummary = $request->input('PaymentSummary');
            $tblPaymentSummary = json_decode($tblPaymentSummary);

            $table = array();

            for ($i = 1; $i < count($tblPaymentSummary); $i++) {

                array_push($table, (object) ['category' => $tblPaymentSummary[$i][0], 'quantity' => $tblPaymentSummary[$i][1], 'amount' => $tblPaymentSummary[$i][2]]);

                $dblTotalSales += $tblPaymentSummary[$i][2];

            }

            $pdf = PDF::loadview('pdf.sales_report', ['mode' => $SalesReportType, 'salesreportdate' => $SalesReportDateInfo, 'date' => $dateNow, 'total' => $dblTotalSales, 'sales' => $table])->setPaper('letter', 'landscape');
            return $pdf->stream();

        }else {

            $tblRoomInfo = json_decode($request->input('tblRoomInfo'));
            $tblItemInfo = json_decode($request->input('tblItemInfo'));
            $tblBeachActivityInfo = json_decode($request->input('tblBeachActivityInfo'));
            $tblBoatInfo = json_decode($request->input('tblBoatInfo'));

            $rooms = array();
            $items = array();
            $beachactivities = array();
            $boats = array();

            for ($i = 1; $i < count($tblRoomInfo); $i++) {

                array_push($rooms, (object) ['name' => $tblRoomInfo[$i][0], 'quantity' => $tblRoomInfo[$i][1], 'rate' => $tblRoomInfo[$i][2], 'amount' => $tblRoomInfo[$i][3]]);

                $dblTotalSales += $tblRoomInfo[$i][3];

            }

            for ($i = 1; $i < count($tblItemInfo); $i++) {

                array_push($items, (object) ['name' => $tblItemInfo[$i][0], 'quantity' => $tblItemInfo[$i][1], 'rate' => $tblItemInfo[$i][2], 'amount' => $tblItemInfo[$i][3]]);

                $dblTotalSales += $tblItemInfo[$i][3];

            }

            for ($i = 1; $i < count($tblBeachActivityInfo); $i++) {

                array_push($beachactivities, (object) ['name' => $tblBeachActivityInfo[$i][0], 'quantity' => $tblBeachActivityInfo[$i][1], 'rate' => $tblBeachActivityInfo[$i][2], 'amount' => $tblBeachActivityInfo[$i][3]]);

                $dblTotalSales += $tblBeachActivityInfo[$i][3];

            }

            for ($i = 1; $i < count($tblBoatInfo); $i++) {

                array_push($boats, (object) ['name' => $tblBoatInfo[$i][0], 'quantity' => $tblBoatInfo[$i][1], 'rate' => $tblBoatInfo[$i][2], 'amount' => $tblBoatInfo[$i][3]]);

                $dblTotalSales += $tblBoatInfo[$i][3];

            }

            $pdf = PDF::loadview('pdf.sales_report', ['mode' => $SalesReportType, 'salesreportdate' => $SalesReportDateInfo, 'date' => $dateNow, 'total' => $dblTotalSales, 'rooms' => $rooms, 'items' => $items, 'beachactivities' => $beachactivities, 'boats' => $boats])->setPaper('letter', 'landscape');
            return $pdf->stream();

        }

    }

    public function getAmenities(Request $req){
        $SelectedAmenity = trim($req->input('selectedAmenity'));
        if($SelectedAmenity == "Rooms/Cottages"){
            $GeneratedReport = DB::table('tblRoom')
                               ->select(DB::raw('strRoomName as AmenityName'))
                               ->where('strRoomStatus', '!=', 'deleted')
                               ->get();
        }
        else if($SelectedAmenity == "Room/Cottage Type"){
            $GeneratedReport = DB::table('tblRoomType')
                               ->select(DB::raw('strRoomType as AmenityName'))
                               ->where('intRoomTDeleted', '=', '1')
                               ->get();
        }
        else if($SelectedAmenity == "Items"){
            $GeneratedReport = DB::table('tblItem')
                               ->select(DB::raw('strItemName as AmenityName'))
                               ->where('intItemDeleted', '=', '1')
                               ->get();
        }
        else if($SelectedAmenity == "Activities"){
            $GeneratedReport = DB::table('tblBeachActivity')
                               ->select(DB::raw('strBeachAName as AmenityName'))
                               ->where('strBeachAStatus', '!=', 'deleted')
                               ->get();
            
        }
        else if($SelectedAmenity == "Fees"){
            $GeneratedReport = DB::table('tblFee')
                               ->select(DB::raw('strFeeName as AmenityName'))
                               ->where('strFeeStatus', '!=', 'deleted')
                               ->get();
            
        }
        else if($SelectedAmenity == "Packages"){
            $GeneratedReport = DB::table('tblPackage')
                               ->select(DB::raw('strPackageName as AmenityName'))
                               ->where('strPackageStatus', '!=', 'deleted')
                               ->get();
        }
        else if($SelectedAmenity == "Boats"){
            $GeneratedReport = DB::table('tblBoat')
                               ->select(DB::raw('strBoatName as AmenityName'))
                               ->where('strBoatStatus', '!=', 'deleted')
                               ->get();
        }

        return response()->json($GeneratedReport);
    }

    public function getAmenityReport(Request $req){
        $SelectedAmenity = trim($req->input('AmenityType'));
        $ChosenAmenity = trim($req->input('ChosenAmenity'));
        $ReportAmenity = trim($req->input('ReportAmenity'));
        $ChosenDate = trim($req->input('ChosenDate'));
        $ChosenMonth = trim($req->input('ChosenMonth'));
        $ChosenYear = trim($req->input('ChosenYear'));
        $ChosenDate = Carbon::parse($ChosenDate)->format('Y-m-d');
        $ChosenMonth = Carbon::parse($ChosenMonth)->format('m');
        $Accommodation = false;
        if($ReportAmenity == "Daily"){

            if($SelectedAmenity == "Rooms/Cottages"){
                $Accommodation = true;
                $RoomID = DB::table('tblRoom')->where([['strRoomStatus', '!=', 'deleted'],['strRoomName', '=', $ChosenAmenity]])->pluck('strRoomID')->first();
                $GeneratedReport = DB::table('tblReservationRoom as a')
                                   ->join('tblReservationDetail as b', 'a.strResRReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblRoom as d', 'd.strRoomID', '=', 'a.strResRRoomID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         'b.dtmResDArrival',
                                         'b.dtmResDDeparture',
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where([['a.strResRRoomID', '=', $RoomID],[DB::raw('Date(b.dtmResDArrival)'), '<=', $ChosenDate],[DB::raw('Date(b.dtmResDDeparture)'), '>=', $ChosenDate]])
                                   ->orderBy('Name')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Items"){
                $ItemID = DB::table('tblItem')->where([['intItemDeleted', '=', '1'],['strItemName', '=', $ChosenAmenity]])->pluck('strItemID')->first();

                $GeneratedReport = DB::table('tblRentedItem as a')
                                   ->join('tblReservationDetail as b', 'a.strRentedIReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblItem as d', 'd.strItemID', '=', 'a.strRentedIItemID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         DB::raw('a.tmsCreated as DateAvailed'),
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where([['a.strRentedIItemID', '=', $ItemID],[DB::raw('Date(a.tmsCreated)'), '=', $ChosenDate]])
                                   ->orderBy('Name')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Activities"){
                $ActivityID = DB::table('tblBeachActivity')->where([['strBeachAStatus', '!=', 'deleted'],['strBeachAName', '=', $ChosenAmenity]])->pluck('strBeachActivityID')->first();

                $GeneratedReport = DB::table('tblAvailBeachActivity as a')
                                   ->join('tblReservationDetail as b', 'a.strAvailBAReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblBeachActivity as d', 'd.strBeachActivityID', '=', 'a.strAvailBABeachActivityID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         DB::raw('a.tmsCreated as DateAvailed'),
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where([['a.strAvailBABeachActivityID', '=', $ActivityID],[DB::raw('Date(a.tmsCreated)'), '=', $ChosenDate]])
                                   ->orderBy('Name')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Packages"){
                $Accommodation = true;
                $PackageID = DB::table('tblPackage')->where([['strPackageStatus', '!=', 'deleted'],['strPackageName', '=', $ChosenAmenity]])->pluck('strPackageID')->first();

                $GeneratedReport = DB::table('tblAvailPackage as a')
                                   ->join('tblReservationDetail as b', 'a.strAvailPReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblPackage as d', 'd.strPackageID', '=', 'a.strAvailPackageID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         'b.dtmResDArrival',
                                         'b.dtmResDDeparture',
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where([['a.strAvailPackageID', '=', $PackageID],[DB::raw('Date(b.dtmResDArrival)'), '=', $ChosenDate]])
                                   ->orderBy('Name')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Boats"){
                $BoatID = DB::table('tblBoat')->where([['strBoatStatus', '!=', 'deleted'],['strBoatName', '=', $ChosenAmenity]])->pluck('strBoatID')->first();

                $GeneratedReport = DB::table('tblBoatSchedule as a')
                                   ->join('tblReservationDetail as b', 'a.strBoatSReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblBoat as d', 'd.strBoatID', '=', 'a.strBoatSBoatID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         DB::raw('a.dtmBoatSPickUp as DateAvailed'),
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where([['a.strBoatSBoatID', '=', $BoatID],[DB::raw('Date(a.dtmBoatSPickUp)'), '=', $ChosenDate]])
                                   ->orderBy('Name')
                                   ->groupBy('Name')
                                   ->get();
            }

        }
        else if($ReportAmenity == "Monthly"){
            if($SelectedAmenity == "Rooms/Cottages"){
                $Accommodation = true;
                $RoomID = DB::table('tblRoom')->where([['strRoomStatus', '!=', 'deleted'],['strRoomName', '=', $ChosenAmenity]])->pluck('strRoomID')->first();
                $GeneratedReport = DB::table('tblReservationRoom as a')
                                   ->join('tblReservationDetail as b', 'a.strResRReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblRoom as d', 'd.strRoomID', '=', 'a.strResRRoomID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         'b.dtmResDArrival',
                                         'b.dtmResDDeparture',
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where('a.strResRRoomID', '=', $RoomID)
                                   ->whereMonth('dtmResDArrival', '=', $ChosenMonth)
                                   ->whereYear('dtmResDArrival', '=', $ChosenYear)
                                   ->orderBy('dtmResDArrival')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Items"){
                $ItemID = DB::table('tblItem')->where([['intItemDeleted', '=', '1'],['strItemName', '=', $ChosenAmenity]])->pluck('strItemID')->first();

                $GeneratedReport = DB::table('tblRentedItem as a')
                                   ->join('tblReservationDetail as b', 'a.strRentedIReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblItem as d', 'd.strItemID', '=', 'a.strRentedIItemID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         DB::raw('a.tmsCreated as DateAvailed'),
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where('a.strRentedIItemID', '=', $ItemID)
                                   ->whereMonth('a.tmsCreated', '=', $ChosenMonth)
                                   ->whereYear('a.tmsCreated', '=', $ChosenYear)
                                   ->orderBy('a.tmsCreated')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Activities"){
                $ActivityID = DB::table('tblBeachActivity')->where([['strBeachAStatus', '!=', 'deleted'],['strBeachAName', '=', $ChosenAmenity]])->pluck('strBeachActivityID')->first();

                $GeneratedReport = DB::table('tblAvailBeachActivity as a')
                                   ->join('tblReservationDetail as b', 'a.strAvailBAReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblBeachActivity as d', 'd.strBeachActivityID', '=', 'a.strAvailBABeachActivityID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         DB::raw('a.tmsCreated as DateAvailed'),
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where('a.strAvailBABeachActivityID', '=', $ActivityID)
                                   ->whereMonth('a.tmsCreated', '=', $ChosenMonth)
                                   ->whereYear('a.tmsCreated', '=', $ChosenYear)
                                   ->orderBy('a.tmsCreated')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Packages"){
                $Accommodation = true;
                $PackageID = DB::table('tblPackage')->where([['strPackageStatus', '!=', 'deleted'],['strPackageName', '=', $ChosenAmenity]])->pluck('strPackageID')->first();

                $GeneratedReport = DB::table('tblAvailPackage as a')
                                   ->join('tblReservationDetail as b', 'a.strAvailPReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblPackage as d', 'd.strPackageID', '=', 'a.strAvailPackageID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         'b.dtmResDArrival',
                                         'b.dtmResDDeparture',
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where('a.strAvailPackageID', '=', $PackageID)
                                   ->whereMonth('dtmResDArrival', '=', $ChosenMonth)
                                   ->whereYear('dtmResDArrival', '=', $ChosenYear)
                                   ->orderBy('dtmResDArrival')
                                   ->groupBy('Name')
                                   ->get();
            }
            else if($SelectedAmenity == "Boats"){
                $BoatID = DB::table('tblBoat')->where([['strBoatStatus', '!=', 'deleted'],['strBoatName', '=', $ChosenAmenity]])->pluck('strBoatID')->first();

                $GeneratedReport = DB::table('tblBoatSchedule as a')
                                   ->join('tblReservationDetail as b', 'a.strBoatSReservationID', '=', 'b.strReservationID')
                                   ->join('tblCustomer as c', 'c.strCustomerID', '=', 'b.strResDCustomerID')
                                   ->join('tblBoat as d', 'd.strBoatID', '=', 'a.strBoatSBoatID')
                                   ->select(DB::raw('CONCAT(c.strCustFirstName , " " , c.strCustMiddleName , " " , c.strCustLastName) AS Name'),
                                         DB::raw('a.dtmBoatSPickUp as DateAvailed'),
                                         'c.strCustContact',
                                         'c.strCustEmail')
                                   ->where('a.strBoatSBoatID', '=', $BoatID)
                                   ->whereMonth('dtmBoatSPickUp', '=', $ChosenMonth)
                                   ->whereYear('dtmBoatSPickUp', '=', $ChosenYear)
                                   ->orderBy('dtmBoatSPickUp')
                                   ->groupBy('Name')
                                   ->get();
            }
        }

        if($Accommodation == true){
            foreach($GeneratedReport as $Report){
                $Report->dtmResDArrival = Carbon::parse($Report->dtmResDArrival)->format('M j,Y');
                $Report->dtmResDDeparture = Carbon::parse($Report->dtmResDDeparture)->format('M j,Y');
            }
        }
        else{
            foreach($GeneratedReport as $Report){
                $Report->DateAvailed = Carbon::parse($Report->DateAvailed)->format('M j,Y');
            }
        }
        return response()->json($GeneratedReport);

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
