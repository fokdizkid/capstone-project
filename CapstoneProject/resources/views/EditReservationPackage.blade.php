@extends('layout')

@section('WebpageTitle')
    <title>Edit Reservation</title>
@endsection

@section('scripts')
    <script src="/js/EditReservationPackage.js" type="text/javascript"></script>
    <script src="/js/input-validator.js" type="text/javascript"></script>
@endsection

<!-- Content-->
@section('content')

<h5 id="TitlePage">Edit Reservation</h5>
<div class="row">
    <div class="col-md-12">
        <h4 class="title">Please note that some changes may affect the initial bill of the guest.</h4>
    </div>
</div>

<div class="row">
<div class="col-sm-4">
        <div class="card card-stats">

                <div class="card-header" data-background-color="green">
                    <i class="material-icons">event</i>
                </div>
                <div class="card-content fix-location">
                    <p class="category">Reservation</p>
                    <h3 class="title">Date and Time</h3>
                    <div class="p-holder">
                        @foreach ($ReservationInfo as $Reservation)
                            <input type="hidden" value = "{{$Reservation -> strReservationID}}" id="h-ReservationID" name="h-ReservationID">
                            <input type="hidden" id="h-CheckInDate" value = "{{Carbon\Carbon::parse($Reservation -> dtmResDArrival)->format('Y/m/d')}}">
                            <input type="hidden" id="h-CheckOutDate" value = "{{Carbon\Carbon::parse($Reservation -> dtmResDDeparture)->format('Y/m/d')}}">
                            <input type="hidden" id="h-DateBooked" value = "{{Carbon\Carbon::parse($Reservation -> dteResDBooking)->format('m/d/Y')}}">
                            <p class="p-label">Arrival Date:</p><p class="p-info" id="i-CheckInDate">{{Carbon\Carbon::parse($Reservation -> dtmResDArrival)->format('M j, Y')}}</p><br>
                            <p class="p-label">Departure Date:</p><p class="p-info" id="i-CheckOutDate">{{Carbon\Carbon::parse($Reservation -> dtmResDDeparture)->format('M j, Y')}}</p><br>
                            <p class="p-label">Arrival Time:</p><p class="p-info" id="i-PickUpTime">{{$PickUpTime}}</p><br>
                        @endforeach
                    </div>



                    <button type="button" class="btn btn-success pull-right" onclick="ShowModalEditResDate()">Reschedule</button>
                </div>

        </div>
    </div>

    <div class="col-sm-7">
        <div class="card card-stats">

                <div class="card-header" data-background-color="green">
                    <i class="material-icons">account_circle</i>
                </div>
                <div class="card-content fix-location">
                    <p class="category">Reservation</p>
                    <h3 class="title">Information</h3>
                    @foreach($ReservationInfo as $Reservation)
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">Number of adults</label>
                                    <input type="text" class="form-control" id="i-NoOfAdults" value="{{$Reservation->intResDNoOfAdults}}" disabled>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">number of children</label>
                                    <input type="text" class="form-control" id="i-NoOfKids" value="{{$Reservation->intResDNoOfKids}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Remarks</label>
                                        <textarea class="form-control" rows="5" id="i-Remarks"disabled> {{$Reservation->strResDRemarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <button type="button" class="btn btn-success pull-right" onclick="ShowModalEditResInfo()">Edit</button>
                </div>

        </div>
    </div>


</div>


<div class="row">
<div class="col-md-11">
    <div class="card">
        <div class="card-header" data-background-color="green">
            <h4 class="title">Package Availed</h4>
            <p class="category"></p>
        </div>
        @foreach($PackageInfo as $Info)
                <p style="display:none" id="ip-PackageName">{{$Info -> strPackageName}}</p>
                <p style="display:none" id="ip-PackagePax">{{$Info -> intPackagePax}}</p>
                <p style="display:none" id="ip-PackageDuration">{{$Info -> intPackageDuration}}</p>
                <p style="display:none" id="ip-PackagePrice">{{$Info -> dblPackagePrice}}</p>
                <p style="display:none" id="ip-PackageDescription">{{$Info -> strPackageDescription}}</p>
        @endforeach
        <div class="card-content table-responsive">
            <table class="table" id="tblReservedRooms">
                <thead class="text-success">
                    <th>Package</th>
                    <th>Pax</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Description</th>
                </thead>
                <tbody>
                    @foreach($PackageInfo as $Info)
                        <tr>
                            <td>{{$Info -> strPackageName}}</td>
                            <td>{{$Info -> intPackagePax}}</td>
                            <td>{{$Info -> intPackageDuration}}</td>
                            <td id="OldPackagePrice">{{$Info -> dblPackagePrice}}</td>
                            <td>{{$Info -> strPackageDescription}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-success pull-right" onclick="ShowModalEditResRoom('none')">Edit</button>
        </div>
    </div>
</div>
</div>
@endsection


<!-- Modals -->

@section('modals')
<div id="DivModalEditResDate" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-12">
                    <div class="card card-stats">

                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">event</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Reschedule</p>
                                <h2 class="title">Reservation Dates<span class="close" onclick="HideModalEditResDate()">X</span></h2>
                                <form method="post" action="/Reservation/Date/Edit" id="frmReschedule">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="d-ReservationID" id="d-ReservationID">
                                    <input type="hidden" name="d-BoatsUsed" id="d-BoatsUsed">
                                    <input type="hidden" name="TotalRoomAmount" id="TotalRoomAmount">
                                    <input type="hidden" name="OrigRoomAmount" id="OrigRoomAmount">
                                    <input type="hidden" name="CheckOutDate" id="tempCheckOutDate">
                                    <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group label-static" id="CheckInDateError">
                                                    <label class="control-label">Check in Date</label>
                                                    <input type="text" class="datepicker form-control" name="CheckInDate" id="CheckInDate"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group label-static" id="CheckOutDateError">
                                                    <label class="control-label">Check out Date</label>
                                                    <input type="text" class="datepicker form-control" id="CheckOutDate" disabled/>
                                                </div>
                                            </div>


                                    </div>
                                    <div class="row">
                                            <div class="col-xs-4">
                                                <p id="time-label">Arrival Time</p>
                                                <div class="selectBox" rel="tooltip" title="Valid time is from 6am to 5pm" >
                                                    <select id="SelectHour" name="SelectHour">
                                                      <option>1</option>
                                                      <option>2</option>
                                                      <option>3</option>
                                                      <option>4</option>
                                                      <option>5</option>
                                                      <option>6</option>
                                                      <option>7</option>
                                                      <option>8</option>
                                                      <option>9</option>
                                                      <option>10</option>
                                                      <option>11</option>
                                                      <option>12</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <p id="time-label2">Minute</p>
                                                <div class="selectBox" rel="tooltip" title="Valid time is from 6am to 5pm" >
                                                    <select id="SelectMinute" name="SelectMinute">
                                                      <option>00</option>
                                                      <option>15</option>
                                                      <option>30</option>
                                                      <option>45</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <p id="time-label3">Merridean</p>
                                                <div class="selectBox" rel="tooltip" title="Valid time is from 6am to 5pm" >
                                                    <select id="SelectMerridean" name="SelectMerridean">
                                                      <option>AM</option>
                                                      <option>PM</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="ElementError" id="ErrorMessage"></p>
                                            </div>
                                        </div>
                                    <button type="button" class="btn btn-success pull-left" style="display:none" id="btnEditReschedRooms" onclick="btnEditReschedRoomsListener()">Edit Rooms</button>
                                    <button type="button" class="btn btn-success pull-right" onclick="SaveDate()">Save</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>

                    </div>
                </div>
    </div>
  </div>
</div>

<div id="DivModalEditResRoom" class="modal">
    <div class="Modal-content bigger-modalContent">
            <div class="row">
            <div class="col-sm-12">
                <div class="card card-stats">

                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">bookmark</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Available</p>
                                <h5 class="title">Packages</h5>
                            </div>
                            <div class="card-content">
                                <table class="table" id="tblAvailablePackages" style="font-family: Roboto" onclick="run(event, 'Package')">
                                    <thead class="text-success">
                                        <th>Package</th>
                                        <th>Pax</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                    </thead>
                                    <tbody>
                                        @foreach($newPackage as $Info)
                                            <tr onclick="HighlightRow(this)">
                                                <td>{{$Info -> strPackageName}}</td>
                                                <td>{{$Info -> intPackagePax}}</td>
                                                <td>{{$Info -> intPackageDuration}}</td>
                                                <td>{{$Info -> dblPackagePrice}}</td>
                                                <td>{{$Info -> strPackageDescription}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <p class="ErrorLabel" id="RoomError"></p>
                                    <form method="post" action="/Reservation/Package/Edit" id="frmEditPackage">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="r-Package" id="r-Package">
                                        <input type="hidden" name="ChosenRooms" id="ChosenRooms">
                                        <input type="hidden" name="r-NoOfKids" id="r-NoOfKids">
                                        <input type="hidden" name="r-NoOfAdults" id="r-NoOfAdults">
                                        <input type="hidden" name="r-CheckInDate" id="r-CheckInDate">
                                        <input type="hidden" name="r-CheckOutDate" id="r-CheckOutDate">
                                        <input type="hidden" id="r-ReservationID" name="r-ReservationID">
                                        <input type="hidden" name="r-BoatsUsed" id="r-BoatsUsed">
                                        <input type="hidden" name="r-PickUpTime" id="r-PickUpTime">
                                        <input type="hidden" name="r-OldPackagePrice" id="r-OldPackagePrice">
                                        <input type="hidden" name="r-NewPackagePrice" id="r-NewPackagePrice">
                                        <button type="button" class="btn btn-danger pull-right" style="margin-right: 50px;" onclick="HideModalEditResRoom()">Cancel</button>
                                        <button type="button" class="btn btn-success pull-right" style="margin-right: 50px;" onclick="CheckRooms()">Choose Package</button>    
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                                
                            </div>

                </div>
            </div>
        </div>

    </div>
</div>

<div id="DivModalEditResInfo" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-8">
                <div class="card card-stats">

                        <div class="card-header" data-background-color="blue">
                            <i class="material-icons">create</i>
                        </div>
                        <div class="card-content">
                            <p class="category">Change information</p>
                            <h2 class="title">Edit Info<span class="close" onclick="HideModalEditResInfo()">X</span></h2>
                            <form method="post" action="/Reservation/Info/Edit" id="frmEditReservationInfo">
                            {{ csrf_field() }}
                            @foreach($ReservationInfo as $Reservation)
                                <input type="hidden" value = "{{$Reservation -> strReservationID}}" id="info-ReservationID" name="info-ReservationID">
                                <input type="hidden" name="info-BoatsUsed" id="info-BoatsUsed">
                                <input type="hidden" value = "00:00:00" name="info-PickUpTime" id="info-PickUpTime">
                                <input type="hidden" name="info-CheckInDate" value = "{{Carbon\Carbon::parse($Reservation -> dtmResDArrival)->format('Y/m/d')}}">
                                <input type="hidden" name="info-CheckOutDate" value = "{{Carbon\Carbon::parse($Reservation -> dtmResDDeparture)->format('Y/m/d')}}">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group label-floating" id="NoOfAdultsError">
                                            <label class="control-label">Number of adults</label>
                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'int', '#NoOfAdultsError')" onchange="ValidateInput(this, 'int', '#NoOfAdultsError')" name="NoOfAdults" id="NoOfAdults" value="{{$Reservation->intResDNoOfAdults}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group label-floating" id="NoOfKidsError">
                                            <label class="control-label">number of children</label>
                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'int2', '#NoOfKidsError')" onchange="ValidateInput(this, 'int2', '#NoOfKidsError')" name="NoOfKids" id="NoOfKids" value="{{$Reservation->intResDNoOfKids}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <div class="form-group label-floating">
                                                <label class="control-label">Remarks</label>
                                                <textarea class="form-control" name="Remarks" id="Remarks" rows="5">{{$Reservation->strResDRemarks}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="ErrorLabel"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p id="InfoError" class="ElementError"></p>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success pull-left" style="display:none" id="btnEditRooms" onclick="btnEditRoomsListener()">Edit Package</button>
                                <button type="button" class="btn btn-success pull-right" onclick="CheckInfo()">Save Changes</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!------------------ Boat Modals ----------------------->

<!---- 1st Modal ---->
<div id="DivModalAvailBoat" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-8">
                <div class="card card-stats">
                        <div class="card-content">
                            <br>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <p class="paragraphText">The reserved boat(s) is not available due to the changes made. Would the guest like to avail a new boat?</p>
                                </div>  
                            </div>
                            <div class = "row">
                                <div class="col-md-2"></div>
                                <div class="col-md-4 text-center">
                                    <button type="button" class="btn btn-success" id="BtnAvailBoat">Yes</button>
                                </div>
                                <div class="col-md-4 text-center">
                                    <button type="button" class="btn btn-success" id="BtnAvailNoBoat">No</button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!---- 3rd Modal ---->
<div id="DivModalMultipleBoats" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-8">
                <div class="card card-stats">
                        <div class="card-content">
                            <br>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <p class="paragraphText">There are no boats that can accomodate the total number of guests, would the guest like to avail multiple boats?</p>
                                </div>  
                            </div>
                            <div class = "row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" id="BtnMultipleBoats">Reserve Multiple Boats</button>
                                </div>
                            </div>
                            <div class = "row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" onclick="HideModalMultipleBoats()">Change date and time</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" id="BtnWithoutBoats2">Continue without reserving boats</button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!---- 4th Modal ---->
<div id="DivModalNoMultipleBoats" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-8">
                <div class="card card-stats">
                        <div class="card-content">
                            <br>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <p class="paragraphText">There are still no available boats that can accomodate the total number of guests.</p>
                                </div>  
                            </div>
                            <div class = "row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" onclick="HideModalNoMultipleBoats()">Change date and time</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" id="BtnWithoutBoats3">Continue without reserving boats</button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>
    
<!---- 2nd Modal ---->
<div id="DivModalNoBoats" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-8">
                <div class="card card-stats">
                        <div class="card-content">
                            <br>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <p class="paragraphText">There are no available boats based on the given date and time.</p>
                                </div>  
                            </div>
                            <div class = "row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" onclick="HideModalNoBoats()">Change date and time</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-sm" id="ButtonWithoutBoats1">Continue without reserving boats</button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="DivModalChangePackagePayment" class="modal">
    <div class="Modal-contentChoice">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-stats">
                    <div class="card-header" data-background-color="green">
                        <i class="material-icons">monetization_on</i>
                    </div>
                    <div class="card-content">
                        <h4><span class="close" onclick="HideModalChangePackagePayment()" style="color: black; font-family: Roboto Thin">X</span></h4>
                        <h3 class="title">New Payment</h3>
                        <br>
                        <p class="category text-center" style="font-family: Roboto; color:black" id="ChangePackageAmount"></p>
                        <br><br>
                        <div class = "row">
                            <button type="button" class="btn btn-success pull-right push-right" onclick="#">Continue</button>
                            <button type="button" class="btn btn-success pull-left push-left" onclick="HideModalChangePackagePayment()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="DivModalExceedGuest" class="modal">
    <div class="Modal-contentChoice">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-stats">
                    <div class="card-header" data-background-color="orange">
                        <i class="material-icons">assignment_late</i>
                    </div>
                    <div class="card-content">
                        <h4><span class="close" onclick="HideModalExceedPackage()" style="color: black; font-family: Roboto Thin">X</span></h4>
                        <br><br><br>
                        <h5 class="title text-center">The number of guests exceeds the pax of the package</h5>
                        <br><br>
                        <div class = "row">
                                <button type="button" class="btn btn-success pull-left push-left" onclick="HideModalExceedPackage()">Make Changes</button>
                                <button type="button" class="btn btn-success pull-right push-right" onclick="ExceedContinue()">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="DivModalPackagePayment" class="modal">
    <div class="Modal-contentChoice">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-stats">
                    <div class="card-header" data-background-color="green">
                        <i class="material-icons">monetization_on</i>
                    </div>
                    <div class="card-content">
                        <h4><span class="close" onclick="HideModalPackagePayment()" style="color: black; font-family: Roboto Thin">X</span></h4>
                        <h3 class="title">New Payment</h3>
                        <br>
                        <p class="category text-center" style="font-family: Roboto; color:black" id="NewPackageAmount"></p>
                        <br><br>
                        <div class = "row">
                            <button type="button" class="btn btn-success pull-right push-right" onclick="SubmitEditPackageForm()">Continue</button>
                            <button type="button" class="btn btn-success pull-left push-left" onclick="HideModalPackagePayment()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!----- Modal for reschedule payment ----->
<div id="DivModalReschedulePayment" class="modal">
    <div class="Modal-contentChoice">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-stats">
                    <div class="card-header" data-background-color="green">
                        <i class="material-icons">monetization_on</i>
                    </div>
                    <div class="card-content">
                        <h4><span class="close" onclick="HideModalReschedulePayment()" style="color: black; font-family: Roboto Thin">X</span></h4>
                        <h3 class="title">New Payment</h3>
                        <br>
                        <p class="category text-center" style="font-family: Roboto; color:black" id="RescheduleAmount"></p>
                        <br><br>
                        <div class = "row">
                            <button type="button" class="btn btn-success pull-right push-right" onclick="SubmitRescheduleForm()">Continue</button>
                            <button type="button" class="btn btn-success pull-left push-left" onclick="HideModalReschedulePayment()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection