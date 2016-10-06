var siteUrl = "http://192.34.58.254";
function customizedConfirm(url)
{
	if(url != ''){
		$('#popupurl').val(url);
	}
	$('#modal-normal').modal('show');
	$('#main-container').css('opacity',0.5);
	return false;
	
}
function changedTeamVsIndividual(va)
{
	if(va=="Team"){
		$('#teamSelect').show();
		$('#indEmails').hide();
	}else if(va=="Individual"){
		$('#teamSelect').hide();
		$('#indEmails').show();
	}else{
		$('#teamSelect').show();
		$('#indEmails').hide();
	}
}
function editCampaign()
{
	if($("#campaign_name").prop("disabled")==true){
			$("#campaign_name").attr("disabled", false);
			$("#editCampLink").html('<a href="#" onClick="return editCampaign();">Save</a> ');
			return false;
	}
	if($("#campaign_name").val()=="")
	{
		$("#campaign_name").addClass("errorClassInput");
		$("#campaign_nameError").show();
		return false;
	}
	var camp_id = $("#editCampId").val();
	$.ajax({
	        cache: false,
	        type: 'get',
	        url: siteUrl+'/save-campaign-name/'+camp_id,
	        data: { campaign_name: $("#campaign_name").val(), camp_id: camp_id},
	        success: function(data) 
	        {
				$("#campaign_name").attr("disabled", true); 
				$("#editCampLink").html('<a href="#" onClick="return editCampaign();">Edit</a> ');
				$("#campaign_name").removeClass("errorClassInput");
				$("#campaign_nameError").hide();
			}
		});
}
function saveStepData(step)
{	
	if($("#campaign_name").val()=="")
	{
		$("#campaign_name").addClass("errorClassInput");
		$("#campaign_nameError").show();
		return false;
	}
	var step_no = $("#step_number"+step).val();
	var t_name = $('#step'+step_no +' #template_name').val();
	var group_name = $('#step'+step_no +' #group_name').val();
	var step_description = $('#step'+step_no +' #step_description').val();
	var schedule_date = $('#schedule_date'+step_no).val();
	
	if($('#step'+step_no +' #template_name').val()==""){
		$('#step'+step_no +' #template_name').addClass("errorClassInput");
		$('#step'+step_no +' #template_nameError').show();
		return false;
	}
	if($('#step'+step_no +' #group_name').val()==""){
		$('#step'+step_no +' #group_name').addClass("errorClassInput");
		$('#step'+step_no +' #group_nameError').show();
		return false;
	}
	if($('#step'+step_no +' #step_description').val()==""){
		$('#step'+step_no +' #step_description').addClass("errorClassInput");
		$('#step'+step_no +' #step_descriptionError').show();
		return false;
	}
	if($('#schedule_date'+step_no).val()==""){
		$('#schedule_date'+step_no).addClass("errorClassInput");
		$('#schedule_dateError'+step_no).show();
		return false;
	}
	
	var auto_send = "0";
	var stime = "";
	var spick = "";
	if($('#step'+step_no +' #auto_send').prop("checked") == true){
		auto_send = "1";
		spick = $('#step'+step_no +' #schedule_picked_time').val();
		if(spick=="Custom"){
			stime = $('#step'+step_no +' #schedule_time').val();
			if(stime=="")
			{
				$('#step'+step_no +' #schedule_time').addClass("errorClassInput");
				$('#step'+step_no +' #schedule_timeError').show();
				return false;
			}
		}
	}
	$('#step'+step_no +' #LoadingImage').show();
	$.ajax({
	        cache: false,
	        type: 'get',
	        url: siteUrl+'/save-steps/'+step_no,
	        data: { campaign_name: $("#campaign_name").val(), step_no: step_no, t_name: t_name, group_name: group_name, step_description: step_description,  schedule_date: schedule_date, auto_send: auto_send, spick: spick, stime: stime},
	        success: function(data) 
	        {
	            var obj = $.parseJSON(data);
	           // alert(obj.camp_id+"Step"+obj.step_id);
	            
	            $("#editCampId").val(obj.camp_id);
	            $("#editStepId"+step_no).val(obj.step_id);
	            if(obj.camp_id!=""){	
					$("#editCampLink").show();
					$("#step"+step_no+" #changeLinks").html('<a href="#" onClick="return editStepData('+step_no+');">Edit</a> <a href="#" onClick="return deleteStepData('+step_no+');">Delete</a>');
					$("#campaign_name").attr("disabled", true); 
				}
	            $("#step"+step_no+" :input").attr("disabled", true);
	            $("#step"+step_no+" :input").removeClass("errorClassInput");	            
	            $("#campaign_nameError").hide();
	            $("#campaign_name").removeClass("errorClassInput");
	            $('#step'+step_no +' #template_nameError').hide();
	            $('#step'+step_no +' #group_nameError').hide();
	            $('#step'+step_no +' #step_descriptionError').hide();
	            $('#schedule_dateError'+step_no).hide();
	            $('#step'+step_no +' #schedule_timeError').hide();
	             var element = $("#step"+step_no);
					element.css('outline', 'none !important')
						   .attr("tabindex", -1)
						   .focus();
				$('#step'+step_no +' #LoadingImage').hide();
	        }
	   });
	
	
}
function editStepData(step)
{	
	var step_no = $("#step_number"+step).val();
	if($("#step"+step_no+" :input").prop("disabled")==true){
			$("#step"+step_no+" :input").attr("disabled", false);
			$("#step"+step_no+" #changeLinks").html('<a href="#" onClick="return editStepData('+step_no+');">Save</a> <a href="#" onClick="return deleteStepData('+step_no+');">Delete</a>');
			return false;
	}
	
	if($("#campaign_name").val()=="")
	{
		$("#campaign_name").addClass("errorClassInput");
		$("#campaign_nameError").show();
		return false;
	}
	
	var t_name = $('#step'+step_no +' #template_name').val();
	var group_name = $('#step'+step_no +' #group_name').val();
	var step_description = $('#step'+step_no +' #step_description').val();
	var schedule_date = $('#schedule_date'+step_no).val();
	
	var camp_id = $("#editCampId").val();
	var step_id = $("#editStepId"+step_no).val();
	
	if($('#step'+step_no +' #template_name').val()==""){
		$('#step'+step_no +' #template_name').addClass("errorClassInput");
		$('#step'+step_no +' #template_nameError').show();
		return false;
	}
	if($('#step'+step_no +' #group_name').val()==""){
		$('#step'+step_no +' #group_name').addClass("errorClassInput");
		$('#step'+step_no +' #group_nameError').show();
		return false;
	}
	if($('#step'+step_no +' #step_description').val()==""){
		$('#step'+step_no +' #step_description').addClass("errorClassInput");
		$('#step'+step_no +' #step_descriptionError').show();
		return false;
	}
	if($('#schedule_date'+step_no).val()==""){
		$('#schedule_date'+step_no).addClass("errorClassInput");
		$('#schedule_dateError'+step_no).show();
		return false;
	}
	
	var auto_send = "0";
	var stime = "";
	var spick = "";
	if($('#step'+step_no +' #auto_send').prop("checked") == true){
		auto_send = "1";
		spick = $('#step'+step_no +' #schedule_picked_time').val();
		if(spick=="Custom"){
			stime = $('#step'+step_no +' #schedule_time').val();
			if(stime=="")
			{
				$('#step'+step_no +' #schedule_time').addClass("errorClassInput");
				$('#step'+step_no +' #schedule_timeError').show();
				return false;
			}
		}
	}
	$('#step'+step_no +' #LoadingImage').show();
	$.ajax({
	        cache: false,
	        type: 'get',
	        url: siteUrl+'/edit-step/'+step_no,
	        data: { campaign_name: $("#campaign_name").val(), step_no: step_no, t_name: t_name, group_name: group_name, step_description: step_description,  schedule_date: schedule_date, auto_send: auto_send, camp_id: camp_id, step_id: step_id, spick: spick, stime: stime},
	        success: function(data) 
	        {
	            var obj = $.parseJSON(data);
	           // alert(obj.camp_id+"Step"+obj.step_id);
	            
	            $("#editCampId").val(obj.camp_id);
	            $("#editStepId"+step_no).val(obj.step_id);
	            if(obj.camp_id!=""){
					$("#editCampLink").show();	  
					$("#step"+step_no+" #changeLinks").html('<a href="#" onClick="return editStepData('+step_no+');">Edit</a> <a href="#" onClick="return deleteStepData('+step_no+');">Delete</a>');	
					$("#campaign_name").attr("disabled", true); 
				}
	            $("#step"+step_no+" :input").attr("disabled", true);	            
	             
	                   
	            $("#step"+step_no+" :input").removeClass("errorClassInput");
	            
	            $("#campaign_nameError").hide();
	            $("#campaign_name").removeClass("errorClassInput");
	            $('#step'+step_no +' #template_nameError').hide();
	            $('#step'+step_no +' #group_nameError').hide();
	            $('#step'+step_no +' #step_descriptionError').hide();
	            $('#schedule_dateError'+step_no).hide();
	            $('#step'+step_no +' #schedule_timeError').hide();
	            
	            
	            var element = $("#step"+step_no);
					element.css('outline', 'none !important')
						   .attr("tabindex", -1)
						   .focus();
				$('#step'+step_no +' #LoadingImage').hide();
	            
	        }
	   });
	
	
}
function deleteBlankData(step)
{	
	var stepLength = $("input[name^= 'schedule_date']").length;
	var step_no = $("#step_number"+step).val();
	$("#step"+step_no).remove();
	if(stepLength=="1"){
		location.href = siteUrl+"/manage-campaigns";
	}
}
function deleteStepData(step)
{	
	var step_no = $("#step_number"+step).val();
	var camp_id = $("#editCampId").val();
	var step_id = $("#editStepId"+step_no).val();
	$("#step"+step_no).remove();
	$.ajax({
	        cache: false,
	        type: 'get',
	        url: siteUrl+'/delete-step/'+step_no,
	        data: {camp_id: camp_id, step_id: step_id},
	        success: function(data) 
	        {
	            console.log("Deleted successfully");
	            if(data=="")
	            {
					location.href = siteUrl+"/manage-campaigns";
				}
	           
	        }
	   });
}
function loadedEditPage(totalSteps)
{
	$("#editCampLink").show();	
    $("#campaign_name").attr("disabled", true);
    
	for(var i=1;i<totalSteps;i++)
	{
		step_no = i;
		$("#step"+step_no+" :input").attr("disabled", true);	            
		
		$("#step"+step_no+" #changeLinks").html('<a href="#" onClick="return editStepData('+step_no+');">Edit</a> <a href="#" onClick="return deleteStepData('+step_no+');">Delete</a>'); 
	}
	
	//alert("HERE"+i);
	return false;
}
function checkedOrNot(step_no)
{	
	if($('#step'+step_no +' #auto_send').prop("checked") == true){
		$("#schedule_pick"+step_no).show();	
			
	}else{
		$("#schedule_pick"+step_no).hide();
		$('#step'+step_no +' #sTime').hide();
	}
}
function showHideTime(val,step_no)
{
	if(val=="Custom")
	{
		$('#step'+step_no +' #sTime').show();
	}else{
		$('#step'+step_no +' #sTime').hide();
	}
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
	$('.schedule_time').datetimepicker({
		language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
		});
	
	/*$('#schedule_date1').datetimepicker();
	$('#schedule_date2').datetimepicker();
	$('#schedule_date3').datetimepicker();
	$('#schedule_date4').datetimepicker();
	$('#schedule_date5').datetimepicker();
	$('#schedule_date6').datetimepicker();
	$('#schedule_date7').datetimepicker();*/
	
});
