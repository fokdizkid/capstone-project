@extends('layout')

@section('WebpageTitle')
    <title>Checkout</title>
@endsection

@section('scripts')
    <script src="/js/Checkout.js" type="text/javascript"></script>
    <script src="/js/input-validator.js" type="text/javascript"></script>
@endsection

@section('content')
<h5 id="TitlePage">Checkout</h5>

<div class="row">
    <div class="col-lg-5">
        <div class="card card-stats">  
            <div class="card-header" data-background-color="blue">
                <i class="material-icons">perm_identity</i>
            </div>
            <div class="card-content">
                <div class="row">
                    <p class="category"></p>
                    <h4 class="title">Customer</h4>
                    @foreach($ReservationInfo as $Info)
                        <h5 class="descriptionText text-info">{{$Info->Name}}</h5>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card card-stats">  
            <div class="card-header" data-background-color="green">
                <i class="material-icons">payment</i>
            </div>
            <div class="card-content">
                <div class="row">
                    <p class="category"></p>
                    <h4 class="title">Bill Summary</h4>
                    <br><br><br>
                </div>
                <div class="row">
                    @if(sizeof($PackageInfo) > 0)
                        <div class="col-lg-12">
                            <h4 class="title text-success">Availed Package</h4><br>
                            <table id="tblRoomInfo" class="table table-responsive">
                                <thead class="text-success">
                                    <th class="text-center">Package</th>
                                    <th class="text-center">Total Amount</th>
                                </thead>
                                <tbody class="text-center">
                                    @foreach($PackageInfo as $Info)
                                        <tr>
                                            <td>{{$Info->strPackageName}}</td>
                                            <td>{{$Info->intPackagePax}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($RoomInfo) > 0)
                        <div class="col-lg-12">
                            <h4 class="title text-success">Reserved Rooms</h4><br>
                            <table id="tblRoomInfo" class="table table-responsive">
                                <thead class="text-success">
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Rate Per Day</th>
                                </thead>
                                <tbody class="text-center">
                                    @foreach($RoomInfo as $Info)
                                        <tr>
                                            <td>{{$Info->strRoomType}}</td>
                                            <td>{{$Info->strRoomName}}</td>
                                            <td>{{$Info->dblRoomRate}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($ItemInfo) > 0)
                    <div class="col-lg-12">
                        <h4 class="title text-success">Rented Items</h4><br>
                        <table id="tblItemInfo" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Rate per hour</th>
                                <th class="text-center">Hour(s) used</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($ItemInfo as $Info)
                                    <tr>
                                        <td>{{$Info->strItemName}}</td>
                                        <td>{{$Info->intRentedIQuantity}}</td>
                                        <td>{{$Info->dblItemRate}}</td>
                                        <td>{{$Info->intRentedIDuration}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
               <div class="row"> 
                    @if(sizeof($ActivityInfo) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Availed Activities</h4><br>
                        <table id="tblActivityInfo" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Activity</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Rate</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($ActivityInfo as $Info)
                                    <tr>
                                        <td>{{$Info->strBeachAName}}</td>
                                        <td>{{$Info->intAvailBAQuantity}}</td>
                                        <td>{{$Info->dblBeachARate}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
              <div class="row">  
                    @if(sizeof($FeeInfo) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Fees</h4><br>
                        <table id="tblFeeInfo" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Fee</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Rate</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($FeeInfo as $Info)
                                    <tr>
                                        <td>{{$Info->strFeeName}}</td>
                                        <td>{{$Info->intResFQuantity}}</td>
                                        <td>{{$Info->dblFeeAmount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                  </div>
               <div class="row"> 
                    @if((sizeof($MiscellaneousInfo) > 0) && ($TotalPenalties != 0))
                    <div class="col-md-12">
                        <h4 class="title text-success">Miscellaneous</h4><br>
                        <table id="tblMiscellaneousInfo" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Title</th>
                                <th class="text-center">Remarks</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($MiscellaneousInfo as $Info)
                                    <tr>
                                        <td>{{$Info->strPaymentType}}</td>
                                        <td>{{$Info->strPaymentRemarks}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($AdditionalRooms) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Additional Rooms</h4><br>
                        <table id="tblAdditionalRooms" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Type</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Payment Needed</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($AdditionalRooms as $Info)
                                    <tr>
                                        <td>{{$Info->strRoomType}}</td>
                                        <td>{{$Info->strRoomName}}</td>
                                        <td>{{$Info->dblRoomRate}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($UpgradeRooms) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Upgrade Rooms</h4><br>
                        <table id="tblUpgradeRooms" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Room</th>
                                <th class="text-center">Amount</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($UpgradeRooms as $Info)
                                    <tr>
                                        <td>{{$Info->strPaymentRemarks}}</td>
                                        <td>{{$Info->dblPayAmount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($ExtendStay) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Extend Stay</h4><br>
                        <table id="tblExtendStay" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Days Extended</th>
                                <th class="text-center">Amount</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($ExtendStay as $Info)
                                    <tr>
                                        <td>{{$Info->strPaymentRemarks}}</td>
                                        <td>{{$Info->dblPayAmount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($BoatInfo) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Rented Boats</h4><br>
                        <table id="tblBoatInfo" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Boat Name</th>
                                <th class="text-center">Rate</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($BoatInfo as $Info)
                                    <tr>
                                        <td>{{$Info->strBoatName}}</td>
                                        <td>{{$Info->dblBoatRate}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if(sizeof($BillDeductionsInfo) > 0)
                    <div class="col-md-12">
                        <h4 class="title text-success">Deductions</h4><br>
                        <table id="tblDeductions" class="table table-responsive">
                            <thead class="text-success">
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Amount</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($BillDeductionsInfo as $Info)
                                    <tr>
                                        <td>{{$Info->strPaymentRemarks}}</td>
                                        <td>{{$Info->dblPayAmount}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div><br>
                <div class="row">
                    <div class = "col-md-10">
                        <h4 class="title text-success">Grand Total</h4><br>
                        @if(sizeof($PackageInfo) != 0)
                        <p class="paragraphText text-success">Total Package Amount:</p> <p class="paragraphText">{{$PackagePayment}}</p><br>
                        @endif
                        @if($TotalPenalties != 0)
                        <p class="paragraphText text-success">Total Miscellaneous Fee:</p> <p class="paragraphText" id="miscfee">{{$TotalPenalties}}</p><br>
                        @endif
                        @if($TotalFee != 0)
                        <p class="paragraphText text-success">Total Fees:</p> <p class="paragraphText">{{$TotalFee}}</p><br>
                        @endif
                        @if($TotalActivity != 0)
                        <p class="paragraphText text-success">Total Activities:</p> <p class="paragraphText">{{$TotalActivity}}</p><br>
                        @endif
                        @if($TotalItem != 0)
                        <p class="paragraphText text-success">Total Items:</p> <p class="paragraphText">{{$TotalItem}}</p><br>
                        @endif
                        @if($TotalRoom != 0)
                        <p class="paragraphText text-success">Total Rooms:</p> <p class="paragraphText">{{$TotalRoom}}</p><br>
                        @endif
                        @if($TotalBoat != 0)
                        <p class="paragraphText text-success">Total Boats:</p> <p class="paragraphText">{{$TotalBoat}}</p><br>
                        @endif
                        @if($AdditionalRoomAmount != 0)
                        <p class="paragraphText text-success">Total Additional Rooms:</p> <p class="paragraphText">{{$AdditionalRoomAmount}}</p><br>
                        @endif
                        @if($UpgradeRoomAmount != 0)
                        <p class="paragraphText text-success">Total Upgrade Rooms:</p> <p class="paragraphText">{{$UpgradeRoomAmount}}</p><br>
                        @endif
                        @if($ExtendStayAmount != 0)
                        <p class="paragraphText text-success">Total Extend Days:</p> <p class="paragraphText">{{$ExtendStayAmount}}</p><br>
                        @endif
                        @if($TotalDeductionInfo != 0)
                        <p class="paragraphText text-success">Total Deductions:</p> <p class="paragraphText">{{$TotalDeductionInfo}}</p><br>
                        @endif
                        @foreach($ReservationInfo as $Info)
                            <strong><h4 class="paragraphText text-success">Grand Total</h4></strong><strong><h4>{{$Info->TotalBill}}</h4></strong>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-stats">  
            <div class="card-header" data-background-color="green">
                <i class="material-icons">monetization_on</i>
            </div>
            <div class="card-content">
                <div class="row">
                    <p class="category"></p>
                    <h4 class="title">Payment</h4>
                </div>
                <div class="row">
                    <form method="post" action="/Checkout/Pay">
                    {{ csrf_field() }}
                        
                        <div class="col-md-12">
                        @foreach($ReservationInfo as $Info)
                            <br>
                            <input type="hidden" name="s-ReservationID" id="s-ReservationID" value="{{$Info->strReservationID}}">
                            <div class="form-group label-static">
                                <label class="control-label">Total</label>
                                <input type="text" class="form-control" value="{{$Info->TotalBill}}" id="PayTotal" name="PayTotal" readonly>
                            </div>
                        @endforeach 
                            <div class="form-group label-static" id="PaymentError">
                                <label class="control-label">Payment</label>
                                <input type="text" class="form-control" onkeyup="SendInput(this, 'double', '#PaymentError')" onchange="SendInput(this, 'double', '#PaymentError')" id="PayPayment" name="PayPayment">
                            </div>
                            <div class="form-group label-static">
                                <label class="control-label">Change</label>
                                <input type="text" class="form-control" id="PayChange" name="PayChange" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="ErrorLabel"></p>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="col-xs-12">
                                    <input type="button" class="btn btn-success pull-left push-right" value="Print Invoice" onclick="PrintInvoice()" />
                                    <button type="submit" class="btn btn-success pull-right push-right"><i class="material-icons">done</i>Proceed</button>
                                </div> 
                            </div>
                        </div>
                    </form>
                    <form id="InvoiceForm" method="POST" action="/Reservation/Invoice" target="_blank">
                        {{ csrf_field() }}
                        <input type="hidden" name="InvoiceType" value="CheckOut">
                        <input type="hidden" name="tblRoomInfo" id="itblRoomInfo">
                        <input type="hidden" name="tblItemInfo" id="itblItemInfo">
                        <input type="hidden" name="tblActivityInfo" id="itblActivityInfo">
                        <input type="hidden" name="tblFeeInfo" id="itblFeeInfo">
                        <input type="hidden" name="tblMiscellaneousInfo" id="itblMiscellaneousInfo">
                        <input type="hidden" name="tblAdditionalRooms" id="itblAdditionalRooms">
                        <input type="hidden" name="tblUpgradeRooms" id="itblUpgradeRooms">
                        <input type="hidden" name="tblExtendStay" id="itblExtendStay">
                        <input type="hidden" name="tblBoatInfo" id="itblBoatInfo">
                        <input type="hidden" name="tblDeductions" id="itblDeductions">
                        <input type="hidden" name="iReservationID" id="iReservationID">
                        <input type="hidden" name="iAmountTendered" id="checkoutAmountTendered">
                        <input type="hidden" name="mfee" id="mfee">
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('modals')

@endsection