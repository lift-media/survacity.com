function customizedConfirm(url)
{
	if(url != ''){
		$('#popupurl').val(url);
	}
	$('#modal-normal').modal('show');
	$('#main-container').css('opacity',0.5);
	return false;
	
}
$( document ).ready(function() {
	 $('#btnYes').click(function(event) {
		var popupurl = $('#popupurl').val(); 
		if(popupurl != ''){
			location.href = popupurl;
		}
    	return true;
        //event.preventDefault();
    });
	$('#btnNo').click(function(event) {
		$('#modal-normal').modal('hide');
		$('#main-container').css('opacity',1);
    	return false;
        //event.preventDefault();
    });   
   /* $( "#schedule-date" ).datepicker({
		dateFormat: "mm/dd/yy",
		changeMonth: true,
		changeYear: true		 
	});*/
	$('#schedule-date').datetimepicker({
				icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }

		});
});
