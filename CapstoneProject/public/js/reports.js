function SelectPrintAction() {
	if(document.getElementById("SelectQuery").value == "Reservations"){
		document.getElementById("ReservationReport").style.display = "block";
		document.getElementById("DailyReport").style.display = "block";
		document.getElementById("IncludedDeletedHolder").style.display = "none";
		document.getElementById("AmenitiesReport").style.display = "none";
	}
	else if(document.getElementById("SelectQuery").value == "Availed Amenities"){
		document.getElementById("AmenitiesReport").style.display = "block";
		document.getElementById("DailyAmenity").style.display = "block";
		document.getElementById("ReservationReport").style.display = "none";
		document.getElementById("DailyReport").style.display = "none";
		document.getElementById("IncludedDeletedHolder").style.display = "none";
		GenerateAmenity();
	}
	else{
		document.getElementById("ReservationReport").style.display = "none";
		document.getElementById("DailyReport").style.display = "none";
		document.getElementById("IncludedDeletedHolder").style.display = "block";
		document.getElementById("AmenitiesReport").style.display = "none";

	}

	document.getElementById('PrintSelectedReport').value = document.getElementById("SelectQuery").value;

}

function IncludeDeleted() {
    if(document.getElementById("CheckIncludeDeleted").checked){

        document.getElementById('PrintIncludeDeleted').value = 1;

    }else {

        document.getElementById('PrintIncludeDeleted').value = 0;

    }

    document.getElementById("rReservationReport").value = document.getElementById("SelectReservationReport").value;
    document.getElementById("rReservationMonth").value = document.getElementById("ReservationMonth").value;
    document.getElementById("rReservationYear").value = document.getElementById("SelectMonthlyYear").value;
    document.getElementById("rDailyReservation").value = document.getElementById("DailyReservation").value;
}
