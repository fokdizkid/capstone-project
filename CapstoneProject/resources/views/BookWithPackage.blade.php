@extends('layout')

@section('WebpageTitle')
    <title>Book Reservations</title>
@endsection

@section('scripts')
    <script src="/js/BookWithPackage.js" type="text/javascript"></script>
    <script src="/js/input-validator.js" type="text/javascript"></script>
@endsection

@section('content')
<h5 id="TitlePage">Book a Reservation</h5>
<div class="row">
                          
  <div class="col-lg-3"></div>    

  <div class="col-md-6">
    <ul class="nav nav-pills nav-pills-success" role="tablist">
        <li id="DateList" class="active">
            <a href="#ReservationDate" role="tab">
                <i class="material-icons">date_range</i>
                Date<br>1
            </a>
        </li>
        <li id="InfoList">
            <a href="#ReservationInfo" role="tab">
                <i class="material-icons">face</i>
                Information<br>2
            </a>
        </li>
        <li id="BillList">
            <a href="#ReservationBill" role="tab">
                <i class="material-icons">monetization_on</i>
                Bill<br>3
            </a>
        </li>
    </ul>
  </div>
  <div class="col-lg-3"></div>
  <div class="row">
    <div class="col-lg-12">
        <a href="/Reservations">
            <button class="btn btn-success btn-round pull-right">
                <i class="material-icons">event</i> View reservations
            </button>
        </a>
    </div>
  </div>
</div>



<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card card-nav-tabs transparent-background">
            <div class="tab-content">
                <div class="tab-pane active" id="ReservationDate">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="card card-stats">

                                <div class="card-header" data-background-color="blue">
                                    <i class="material-icons">bookmark</i>
                                </div>
                                <div class="card-content">
                                    <div class="row">
                                        <p class="category">Please fill out all input</p>
                                        <h5 class="title">Reservation Dates</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="form-group label-static" id="CheckInDateError">
                                                <label class="control-label">Check in Date</label>
                                                <input type="text" class="datepicker form-control" id="CheckInDate" name="CheckInDate">
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group label-static" rel="tooltip" title="Check out date will be based on the package's duration" id="CheckInDateError">
                                                <label class="control-label">Check in Date</label>
                                                <input type="text" class="datepicker form-control" id="CheckOutDate" name="CheckOutDate"  disabled>
                                            </div>
                                        </div>

                                        <div class="col-xs-2">
                                            <p id="time-label">Arrival Time</p>
                                            <div class="selectBox" rel="tooltip" title="Valid time is from 6am to 5pm" >
                                                <select id="PickUpTime">
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
                                        <div class="col-xs-2">
                                            <p id="time-label2">Minute</p>
                                            <div class="selectBox" rel="tooltip" title="Valid time is from 6am to 5pm">
                                                <select id="PickUpMinute">
                                                  <option>00</option>
                                                  <option>15</option>
                                                  <option>30</option>
                                                  <option>45</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <p id="time-label3">Merridean</p>
                                            <div class="selectBox" rel="tooltip" title="Valid time is from 6am to 5pm" >
                                                <select id="PickUpMerridean">
                                                  <option>AM</option>
                                                  <option>PM</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <br><br>
                                    <button type="button" class="btn btn-success pull-left" onclick="ShowModalInoperationalDates()">Show Dates when resort is closed</button>
                                    <button type="button" class="btn btn-success pull-right" onclick="CheckReservationInput()">View Available Packages</button>
                                    <br><br><br><br>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="PackageHolder">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" data-background-color="blue">
                                        <h4 class="title">Packages</h4>
                                        <p class="category"></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group label-floating" style="margin-left:30px;">
                                                <label class="control-label">Search Packages</label>
                                                <input type="text" class="form-control" id="SearchBar" onkeyup="SearchTable('PackageTable' ,'1')" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-content table-responsive" onclick="run(event)">
                                        <table class="table" id="PackageTable">
                                            <thead class="text-primary">
                                                <th onclick="sortTable(0, 'PackageTable', 'string')">ID</th>
                                                <th onclick="sortTable(1, 'PackageTable', 'string')">Name</th>
                                                <th onclick="sortTable(2, 'PackageTable', 'double')">Price</th>
                                                <th onclick="sortTable(3, 'PackageTable', 'int')">Pax</th>
                                                <th onclick="sortTable(4, 'PackageTable', 'int')">Duration</th>
                                                <th onclick="sortTable(5, 'PackageTable', 'string')">Description</th>
                                                <th onclick="sortTable(6, 'PackageTable', 'string')">Transportation Fee</th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="card card-stats">

                                            <div class="card-header" data-background-color="green">
                                                <i class="material-icons">local_hotel</i>
                                            </div>
                                            <div class="card-content">
                                                <p class="category">Package</p>
                                                <h5 class="title">Included Rooms</h5>
                                            </div>
                                            <div class="card-footer">
                                                <table class="table" id="tblIncludedRooms">
                                                    <thead class="text-success">
                                                        <th onclick="sortTable(0, 'tblIncludedRooms', 'string')">Room</th>
                                                        <th onclick="sortTable(1, 'tblIncludedRooms', 'int')">Quantity</th>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card card-stats">

                                            <div class="card-header" data-background-color="green">
                                                <i class="material-icons">local_offer</i>
                                            </div>
                                            <div class="card-content">
                                                <p class="category">Package</p>
                                                <h5 class="title">Included Items</h5>
                                            </div>
                                            <div class="card-footer">
                                                <table class="table" id="tblIncludedItems">
                                                    <thead class="text-success">
                                                        <th onclick="sortTable(0, 'tblIncludedItems', 'string')">Item</th>
                                                        <th onclick="sortTable(1, 'tblIncludedItems', 'int')">Quantity</th>
                                                        <th onclick="sortTable(2, 'tblIncludedItems', 'int')">Duration(hours)</th>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card card-stats">

                                            <div class="card-header" data-background-color="green">
                                                <i class="material-icons">map</i>
                                            </div>
                                            <div class="card-content">
                                                <p class="category">Package</p>
                                                <h5 class="title">Included Activities</h5>
                                            </div>
                                            <div class="card-footer">
                                                <table class="table" id="tblIncludedActivities">
                                                    <thead class="text-success">
                                                        <th onclick="sortTable(0, 'tblIncludedActivities', 'string')">Activity</th>
                                                        <th onclick="sortTable(1, 'tblIncludedActivities', 'int')">Quantity</th>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card card-stats">

                                            <div class="card-header" data-background-color="green">
                                                <i class="material-icons">local_atm</i>
                                            </div>
                                            <div class="card-content">
                                                <p class="category">Package</p>
                                                <h5 class="title">Included Fees</h5>
                                            </div>
                                            <div class="card-footer">
                                                <table class="table" id="tblIncludedFees">
                                                    <thead class="text-success">
                                                        <th>Fee</th>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>

                                </div>
                            </div>

                        </div>

                        <button type="button" class="btn btn-success pull-right" onclick="ChangeClass('#ReservationDate', '#DateList', '#ReservationInfo', '#InfoList', 'continue')">Continue</button>

                    </div>

                </div>


                <div class="tab-pane" id="ReservationInfo">
                    <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-lg-10">
                                <div class="card card-stats">

                                        <div class="card-header" data-background-color="blue">
                                            <i class="material-icons">account_circle</i>
                                        </div>
                                        <div class="card-content">
                                            <p class="category">Please fill out all the fields</p>
                                            <h3 class="title">Guest Information</h3>

                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="form-group label-floating" id="FirstNameError">
                                                            <label class="control-label">First Name</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'string2', '#FirstNameError')" onchange="ValidateInput(this, 'string2', '#FirstNameError')" id="FirstName">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group label-floating" id="MiddleNameError">
                                                            <label class="control-label">Middle Name</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'string2', '#MiddleNameError')" onchange="ValidateInput(this, 'string2', '#MiddleNameError')" id="MiddleName">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group label-floating" id="LastNameError">
                                                            <label class="control-label">Last Name</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'string2', '#LastNameError')" onchange="ValidateInput(this, 'string2', '#LastNameError')" id="LastName">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group label-floating">
                                                            <label class="control-label">Address</label>
                                                            <input type="text" class="form-control" id="Address">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="form-group label-floating">
                                                            <label class="control-label">Email Address</label>
                                                            <input type="email" class="form-control" onkeyup="ValidateInput(this, 'email', '#EmailError')" onchange="ValidateInput(this, 'email', '#EmailError')" id="Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group label-floating" id="ContactError">
                                                            <label class="control-label">Contact Number</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'contact', '#ContactError')" onchange="ValidateInput(this, 'contact', '#ContactError')" id="ContactNumber">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group label-floating" id="NationalityError">
                                                            <label class="control-label">Nationality</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateInput(this, 'string2', '#NationalityError')" onchange="ValidateInput(this, 'string2', '#NationalityError')" id="Nationality">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-xs-6">
                                                        <div class="form-group label-static">
                                                            <label class="control-label">Date of birth</label>
                                                            <input type="text" class="datepicker form-control" id="DateOfBirth"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                       <p id="gender-label">Gender</p>
                                                        <div class="selectBox">
                                                            <select id="Gender">
                                                              <option>Male</option>
                                                              <option>Female</option>
                                                            </select>
                                                        </div> 
                                                    </div>
                                                </div>
                                            
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="form-group label-floating" id="NoOfAdultsError">
                                                            <label class="control-label">Number of Adults</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateGuests(this, 'int', '#NoOfAdultsError')" onchange="ValidateGuests(this, 'int', '#NoOfAdultsError')" id="NoOfAdults">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group label-floating" id="NoOfKidsError">
                                                            <label class="control-label">Number of Children</label>
                                                            <input type="text" class="form-control" onkeyup="ValidateGuests(this, 'int2', '#NoOfKidsError')" onchange="ValidateGuests(this, 'int2', '#NoOfKidsError')" id="NoOfKids">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">

                                                            <div class="form-group label-floating">
                                                                <label class="control-label">Remarks</label>
                                                                <textarea class="form-control" rows="5" id="Remarks"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p class="ErrorLabel"></p>
                                                    </div>
                                                </div>


                                                <button type="button" class="btn btn-info pull-left" onclick="ChangeClass('#ReservationInfo', '#InfoList', '#ReservationDate', '#DateList', 'back')">Back</button>
                                                <button type="button" class="btn btn-success pull-right" onclick="ChangeClass('#ReservationInfo', '#InfoList', '#ReservationBill', '#BillList', 'continue')">Continue</button>

                                        </div>

                                </div>
                            </div>
                    </div>
                </div>

                <div class="tab-pane" id="ReservationBill">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card card-stats">

                                        <div class="card-header" data-background-color="blue">
                                            <i class="material-icons">class</i>
                                        </div>
                                        <div class="card-content">
                                            <div class="row">
                                                <h3 class="title">Reservation Details</h3>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-1"></div>
                                                <div class="col-xs-10">

                                                    <small><h3 class="text-primary">Accomodation</h3></small>
                                                    <p class="paragraphText">Check in date:</p> <p class="paragraphText" id="i-CheckInDate"></p><br>
                                                    <p class="paragraphText">Check out date:</p> <p class="paragraphText" id="i-CheckOutDate"></p><br>
                                                    <p class="paragraphText">Time of arrival:</p> <p class="paragraphText" id="i-PickUpTime"></p><br>
                                                    <p class="paragraphText">Package Availed:</p> <a href="/Maintenance/Package" target="_blank"><p class="paragraphText text-primary" rel="tooltip" title="Show package information" id="i-PackageName"></p></a><br>
                                                    <p class="paragraphText">Package Price:</p> <p class="paragraphText" id="i-PackagePrice"></p><br>

                                                    <small><h3 class="text-primary">Customer Information</h3></small>
                                                    <p class="paragraphText">Customer Name:</p> <p class="paragraphText" id="i-CustomerName"></p><br>
                                                    <p class="paragraphText">Address:</p> <p class="paragraphText" id='i-Address'></p><br>
                                                    <p class="paragraphText">Email:</p> <p class="paragraphText" id="i-Email"></p><br>
                                                    <p class="paragraphText">Contact Number:</p> <p class="paragraphText" id="i-Contact"></p><br>
                                                    <p class="paragraphText">Nationality:</p> <p class="paragraphText" id="i-Nationality"></p><br>
                                                    <p class="paragraphText">Date of birth:</p> <p class="paragraphText" id="i-Birthday"></p><br>
                                                    <p class="paragraphText">Gender:</p> <p class="paragraphText" id="i-Gender"></p><br>
                                                    <p class="paragraphText">Remarks:</p> <p class="paragraphText" id="i-Remarks"></p><br>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                        </div>
                        <div class="col-sm-6">
                                <div class="card card-stats">

                                        <div class="card-header" data-background-color="blue">
                                            <i class="material-icons">monetization_on</i>
                                        </div>
                                        <div class="card-content">
                                            <div class="row">
                                                <h3 class="title">Initial Bill</h3>
                                            </div>

                                            <div class="row">
                                                
                                                <div class="col-xs-1"></div>
                                                <div class="col-xs-10">
                                                    <small><h3 class="text-primary">Accomodation</h3></small>
                                                    <p class="paragraphText">Package Availed:</p><p class="paragraphText" id="p-PackageName"></p><br>
                                                    <strong><p class="paragraphText">Package Price:</p> <p class="paragraphText" id="p-PackagePrice"></p></strong><br>
                                                    <small><h3 class="text-primary">Miscellaneous</h3></small>
                                                    <p class="paragraphText">Number of adult guests:</p><p class="paragraphText" id="p-NoOfAdults"></p><br>
                                                    <p class="paragraphText">Entrance Fee:</p> <p class="paragraphText" id="p-EntranceFee"></p><br>
                                                    <strong><p class="paragraphText">Total Entrance Fee:</p> <p class="paragraphText" id="p-TotalEntranceFee"></p></strong><br>
                                                    
                                                    <small><h3 class="text-primary">Grand Total</h3></small>
                                                    <p class="paragraphText">Accomodation Fee:</p> <p class="paragraphText" id="p-AccomodationFee"></p><br>
                                                    <p class="paragraphText">Miscellaneous Fee:</p> <p class="paragraphText" id="p-MiscellaneousFee"></p><br>
                                                    <strong><h5 class="paragraphText text-primary">Grand Total:</h5> <h5 class="paragraphText" id="p-GrandTotal"></h5></strong><br>

                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                    </div>

                        <button type="button" class="btn btn-info pull-left" onclick="ChangeClass('#ReservationBill', '#BillList', '#ReservationInfo', '#InfoList', 'back')">Back</button>
                        <form method="post" action="/Reservation/Add/Package">
                            {{ csrf_field() }}
                            <input type="hidden" name="s-CheckInDate" id="s-CheckInDate" value = "">
                            <input type="hidden" name="s-CheckOutDate" id="s-CheckOutDate" value = "">
                            <input type="hidden" name="s-PickUpTime" id="s-PickUpTime" value = "">
                            <input type="hidden" name="s-BoatsUsed" id="s-BoatsUsed" value = "">
                            <input type="hidden" name="s-FirstName" id="s-FirstName" value = "">
                            <input type="hidden" name="s-MiddleName" id="s-MiddleName" value = "">
                            <input type="hidden" name="s-LastName" id="s-LastName" value = "">
                            <input type="hidden" name="s-Address" id="s-Address" value = "">
                            <input type="hidden" name="s-Email" id="s-Email" value = "">
                            <input type="hidden" name="s-Contact" id="s-Contact" value = "">
                            <input type="hidden" name="s-Nationality" id="s-Nationality" value = "">
                            <input type="hidden" name="s-DateOfBirth" id="s-DateOfBirth" value = "">
                            <input type="hidden" name="s-Gender" id="s-Gender" value = "">
                            <input type="hidden" name="s-Remarks" id="s-Remarks" value = "">
                            <input type="hidden" name="s-InitialBill" id="s-InitialBill" value = "">
                            <input type="hidden" name="s-PackageID" id="s-PackageID" value = "">
                            <input type="hidden" name="s-NoOfKids" id="s-NoOfKids" value = "">
                            <input type="hidden" name="s-NoOfAdults" id="s-NoOfAdults" value = "">
                            <button type="submit" class="btn btn-success pull-right">Book Reservation</button>
                        </form>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="alert alert-danger hide-on-click" style="display: none;">
                            <div class="container-fluid">
                              <div class="alert-icon">
                                <i class="material-icons">warning</i>
                              </div>
                              <button type="button" class="close" aria-label="Close" onclick="HideAlert()">
                                <span aria-hidden="true"><i class="material-icons">clear</i></span>
                              </button>
                              <p id="ErrorMessage"></p>
                            </div>
                        </div>
                    </div>
                </div>



            </div><!--Tab content-->
        </div>

    </div>
</div>
@endsection

@section('modals')
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
                                    <button type="button" class="btn btn-success btn-sm" id="BtnWithoutBoats2" onclick="switchTab('WithoutBoats')">Continue without reserving boats</button>
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
                                    <button type="button" class="btn btn-success btn-sm" id="BtnWithoutBoats3" onclick="switchTab('WithoutBoats')">Continue without reserving boats</button>
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
                                    <button type="button" class="btn btn-success btn-sm" id="ButtonWithoutBoats1" onclick="switchTab('WithoutBoats')">Continue without reserving boats</button>
                                </div>
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
                        <h4><span class="close" onclick="HideModalExceedGuest()" style="color: black; font-family: Roboto Thin">X</span></h4>
                        <br><br><br>
                        <h5 class="title text-center">The number of guests exceeds the pax of the package</h5>
                        <br><br>
                        <div class = "row">
                                <button type="button" class="btn btn-success pull-left push-left" onclick="HideModalExceedGuest()">Make Changes</button>
                                <button type="button" class="btn btn-success pull-right push-right" onclick="ExceedContinue()">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="DivModalInoperationalDates" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-12">
                    <div class="card card-stats">

                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">create</i>
                            </div>
                            <div class="card-content">
                                <div class="row">
                                    <h3 class="title">Dates<span class="close" onclick="HideModalInoperationalDates()">X</span></h3>
                                    <p class="category paragraphText">Dates when the resort is not accepting reservations/walkins</p>
                                </div>
                                <br>
                                <table class="table table-hover" onclick="run(event)" style="font-family: Roboto" id="tblInoperationalDates">
                                <thead class="text-success">
                                    <th>Date ID</th>
                                    <th>Title</th>   
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th style="display:none">Start Date2</th>
                                    <th style="display:none">End Date2</th>
                                </thead>
                                <tbody>
                                    @foreach($Dates as $Date)
                                        <tr onclick="HighlightRow(this)">
                                            <td>{{$Date->strDateID}}</td>
                                            <td>{{$Date->strDateTitle}}</td>
                                            <td>{{Carbon\Carbon::parse($Date -> dteStartDate)->format('M j, Y')}}</td>
                                            <td>{{Carbon\Carbon::parse($Date -> dteEndDate)->format('M j, Y')}}</td>
                                            <td>{{$Date-> strDateDescription}}</td>
                                            <td style="display: none">{{Carbon\Carbon::parse($Date -> dteStartDate)->format('m/d/Y')}}</td>
                                            <td style="display: none">{{Carbon\Carbon::parse($Date -> dteEndDate)->format('m/d/Y')}}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            </div>

                    </div>
                </div>
        </div>
    </div>
</div>

@endsection