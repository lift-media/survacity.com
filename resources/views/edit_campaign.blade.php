@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Campaign</div>

                    <div class="panel-body">
                       {{-- @upperName --}}                      
						  @if (count($errors) > 0)
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
                       <form role="form" class="form-horizontal" method="post" name="schedule_send_email" id="schedule_send_email">
								
							{!! csrf_field() !!}
							
							<div class="form-group">
								<div class="col-md-offset-8 col-md-10">
									<span id="editCampLink" style="display:none;"><a href="#" onClick="return editCampaign();">Edit</a></span>									
								</div>
							</div>

					<!-- Campaign Name -->
						<div class="form-group">
								<label class="col-md-2 control-label">Campaign Name</label>

								<div class="col-md-10">
									
									<input type="text" name="campaign_name" id="campaign_name" class="form-control" value="{{$campaign->campaign_name}}"> 
									<div id="campaign_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>
							
						
							<div class="form-group">
								<div class="col-md-12 "><h5>Campaign Steps</h5></div>
								
							</div>
						<div class="col-md-12 "><hr></div>
						<input type="hidden" name="editCampId" id="editCampId" value="{{$campaign->id}}">
					<?php 
						$i=1;
					?>	
					@foreach($campaign->campaignStep as $step)
					<div id="step{{$i}}">
						<div class="form-group">
								<div class="col-md-10 ">Step {{$i}}</div>
								<div class="col-md-1 " id="saveLink"><a href="#" onClick="return saveStepData({{$i}});">Save</a></div>
								<div class="col-md-1 " id="editLink" style="display:none;"><a href="#" onClick="return editStepData({{$i}});">Edit</a></div>
								
									<input type="hidden" name="editStepId{{$i}}" id="editStepId{{$i}}" value="{{$step['id']}}">
									
								
								
								<div class="col-md-1 " id="deleteLink"><a href="#" onClick="return deleteBlankData({{$i}});">Delete</a></div>
								<div class="col-md-1 " id="deleteDataLink" style="display:none;"><a href="#" onClick="return deleteStepData({{$i}});">Delete</a></div>
						</div>						
						<!-- Template Name -->
						<div class="form-group">
							<input type="hidden" name="step_number" id="step_number{{$i}}" value="{{$i}}">	
								<label class="col-md-2 control-label">Select Template</label>

								<div class="col-md-10">
									<select name="template_name" id="template_name" class="form-control">
										<option value="">Select</option>
										@foreach($email_templates as $template)
											<option value="{{$template->id}}" @if($template->id==$step['template_id']) {{"selected"}} @else {{""}} @endif>{{$template->template_name}}</option>
										@endforeach
									</select>
									<div id="template_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						
						<!-- Team -->
						<div class="form-group" id="teamSelect">
								<label class="col-md-2 control-label">Team</label>

								<div class="col-md-10">
									<select name="group_name" id="group_name" class="form-control">
										@foreach($all_teams as $team)
											<option value="{{$team->id}}" @if($team->id==$step['group_id']) {{"selected"}} @else {{""}} @endif>{{$team->name}}</option>
										@endforeach
									</select>
									<div id="group_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<!-- Step Description -->
						<div class="form-group">
							<label class="col-md-2 control-label">Step Description</label>

							<div class="col-md-10">
								<textarea name="step_description" id="step_description" rows="3" cols="50" class="form-control">{{$step['step_description']}}</textarea>
								<div id="step_descriptionError" style="display:none;"><span class="errorClass">This field is required</span></div>
								<span class="help-block" style="display: none;">
									
								</span>
							</div>
						</div>

						<!-- Email -->
						<div class="form-group">
								<label class="col-md-2 control-label">Schedule Date</label>

								<div class="col-md-10">
									<select name="schedule_date{{$i}}" id="schedule_date{{$i}}" class="form-control">
										<option value="">Select</option>
										@for($ii=1;$ii<=50;$ii++)
											<option value="{{$ii}}" @if($step['scheduled_day']==$ii) {{'selected="selected"'}} @else {{""}} @endif>{{$ii}}</option>
										@endfor
									</select>
									<div id="schedule_dateError{{$i}}" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
								<input type="checkbox"  name="auto_send" id="auto_send" value="1" @if($step['auto_send_status']=="1") {{'checked="checked"'}} @else {{""}} @endif>&nbsp;Auto Send									
								</div>
						</div>
						<div class="col-md-12 "><hr></div>
					</div>
					<?php $i++; ?>
					@endforeach
						<!-- Second Step Start-->
				
						<!---Second Step End -->
            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
<script>
	$( document ).ready(function() {
		loadedEditPage({{$i}});
	});
</script>
@endsection
