@extends('layout')

@section('WebpageTitle')
    <title>Choose Rooms</title>
@endsection

@section('scripts')
    <script src="/js/ChooseRooms.js"></script>
    <script src="/js/UpgradeRoom.js"></script>
@endsection

@section('content')

@if(Session::has('ReservationID'))
    <input type="hidden" id="ReservationID" name="ReservationID" value = "{{ Session::get('ReservationID') }}">
@endif

<div class="row">
    <div class="col-md-3 dropdown">
        <h5 id="TitlePage">Upgrade Room</h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="card card-stats">
            <div class="card-header" data-background-color="green">
                <i class="material-icons">file_upload</i>
            </div>
            <div class="card-content">
                <p class="category">Room to Upgrade</p>
            </div>
            <div class="card-content">
                <p class="paragraphText text-primary">Room Type</p> <p class="paragraphText" id="i-ReservationID"></p><br>
                <p class="paragraphText text-primary">Room Name</p> <p class="paragraphText" id="i-ReservationID"></p><br>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="card card-stats">

                    <div class="card-header" data-background-color="blue">
                        <i class="material-icons">bookmark</i>
                    </div>
                    <div class="card-content">
                        <p class="category">Available</p>
                        <h5 class="title">Rooms</h5>
                    </div>
                    <div class="card-content">
                        <table class="table" style="font-family: 'Roboto'" id="tblChosenRoomTypes" onclick="run(event, 'ChosenRoomTypes')">
                            <thead class="text-info">
                                <th>Room Type</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
      
                    </div>

        </div>
    </div>

    <div class="col-sm-6">
        <div class="card card-stats">

                    <div class="card-header" data-background-color="blue">
                        <i class="material-icons">beenhere</i>
                    </div>
                    <div class="card-content">
                        <p class="category">Pre-selected</p>
                        <h5 class="title">Rooms</h5>
                    </div>
                    <div class="card-content table-responsive">
                        <table class="table" style="font-family: 'Roboto'" id="tblChosenRooms" onclick="run(event, 'ChosenRooms')">
                            <thead class="text-info">
                                <th>Room Name</th>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger pull-right" onclick="ShowModalReplaceRoom()">Replace</button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

        </div>
    </div>

</div>

<div class="row">
    <form method="post" action="/CheckIn/SaveRooms" onsubmit="return SaveRooms()">
        {{ csrf_field() }}
        <input type="hidden" name="s-ReservationID" id="s-ReservationID">
        <input type="hidden" name="s-ChosenRooms" id="s-ChosenRooms">
        <button type="submit" class="btn btn-success pull-right">Continue</button>
    </form>
    
</div>

@endsection

@section('modals')

<!---- 2nd Modal ---->
<div id="DivModalReplaceRoom" class="modal">
    <div class="Modal-content">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-8">
                <div class="card card-stats">
                    <div class="card-header" data-background-color="blue">
                        <i class="material-icons">bookmark</i>
                    </div>
                    <div class="card-content">
                        <p class="category">Available</p>
                        <h5 class="title">Rooms</h5>
                    </div>
                    <div class="card-content">
                        <table class="table" style="font-family: 'Roboto'" id="tblAvailableRooms" onclick="run(event, 'AvailableRooms')">
                            <thead class="text-info">
                                <th>Room</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="row">
                            <p class="ElementError" id="EmptyTableError"></p>
                        </div>
                        <button type="button" class="btn btn-danger pull-left" onclick="HideModalReplaceRoom()">Cancel</button>
                        <button type="button" class="btn btn-success pull-right" onclick="ReplaceRoom()">Choose</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection