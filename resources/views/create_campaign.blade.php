@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading create-camp-head">
					   <div class="pull-left">Create New Campaign</div>
				       <div class="pull-right">
						  <a href="/manage-campaigns"> <button type="button" class="btn btn-default">Cancel</button> </a> 
						   <a href="/manage-campaigns"><button type="button" class="btn btn-primary">Finish</button></a>
					  </div>
				    </div>

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
							
							
					<!-- Campaign Name -->
						<div class="form-group">
								<label class="col-md-9">Campaign Name</label>
								<div class="col-md-3 camp-save" id="editCampLink" style="display:none;">
										<a href="#" onClick="return editCampaign();">Edit</a> 
								</div>
								<div class="col-md-12">
									
									<input type="text" name="campaign_name" id="campaign_name" class="form-control"> 
									<div id="campaign_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>
							
						
							<div class="form-group">
								<div class="col-md-12 camp-steps-txt"><h5>Campaign Steps</h5></div>
								
							</div>
								<input type="hidden" name="editCampId" id="editCampId" value="">
						
						<?php for($i=1;$i<=10;$i++){?>
						<div id="step{{$i}}" class="step_cls">
							<div class="form-group">
									<div class="col-md-2  camp-step-lbl">Step {{$i}}</div>
									<div class="col-md-7 ">
										
											  <select name="schedule_date{{$i}}" id="schedule_date{{$i}}" class="form-control select-wd-80">
													<option value="">Select</option>
													@for($ii=1;$ii<=50;$ii++)
														<option value="{{$ii}}">{{$ii}}</option>
													@endfor
												</select>
									    
										<div class="camp-step-lbl">Day</div>
									</div>
									<div class="col-md-3 camp-save-delete" id="changeLinks">
										<a href="#" onClick="return saveStepData({{$i}});">Save</a> 
										<a href="#" onClick="return deleteBlankData({{$i}});">Delete</a>
										
										</div>
									<input type="hidden" name="editStepId{{$i}}" id="editStepId{{$i}}" value="">
							</div>
							<div class="form-group LoadingImage" id="LoadingImage" style="display:none;">
								<img src="/img/loader.gif" />
							</div>
							<!-- Step Description -->
							<div class="form-group">
								<!--<label class="col-md-3 control-label">Step Description</label>-->

								<div class="col-md-10 col-md-offset-2">
									<textarea name="step_description" id="step_description" rows="3" cols="50" placeholder="Enter step description here" class="form-control"></textarea>
									<div id="step_descriptionError" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
							</div>	
								<!-- Template Name -->
						<div class="form-group">
							<input type="hidden" name="step_number" id="step_number{{$i}}" value="{{$i}}">	
								<label class="col-md-2 control-label">Template</label>

								<div class="col-md-4">
									<select name="template_name" id="template_name" class="form-control">
										<option value="">Select</option>
										@foreach($email_templates as $template)
											<option value="{{$template->id}}">{{$template->template_name}}</option>
										@endforeach
									</select>
									<div id="template_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
								
								<label class="col-md-2 control-label">Team</label>

								<div class="col-md-4">
									<select name="group_name" id="group_name" class="form-control">
										@foreach($all_teams as $team)
											<option value="{{$team->id}}" @if($team->id==$current_team->id) {{"selected"}} @else {{""}} @endif>{{$team->name}}</option>
										@endforeach
									</select>
									<div id="group_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>
						
						<div class="form-group">
	
								    <label class="col-md-2 control-label campgn-auto-send">
										 <input type="checkbox" name="auto_send" id="auto_send" value="1" onClick="checkedOrNot({{$i}});">&nbsp;Auto Send	
								    </label>
								    <div class="col-md-4" style="display:none;" id="schedule_pick{{$i}}">
										<select class="form-control" name="schedule_picked_time" id="schedule_picked_time" onChange="showHideTime(this.value,{{$i}});">
											<option value="Immediate">Immediate</option>
											<option value="Best Time">Best Time</option>
											<option value="Morning">Morning</option>
											<option value="Afternoon">Afternoon</option>
											<option value="Evening">Evening</option>
											<option value="Night">Night</option>
											<option value="Custom">Custom</option>
										</select>
									</div>		
								    <div class="col-md-4" style="display:none;" id="sTime">
										<input type="text" class="form-control schedule_time" placeholder="Pick Time" data-date-format="hh:ii" id="schedule_time" name="schedule_time" readonly />
										<div id="schedule_timeError" style="display:none;"><span class="errorClass">This field is required</span></div>
										</div>								
						</div>
			            
			            <div class="form-group">
							<div class="col-md-12 "><div class="step-seprator"></div></div>
						</div>
					</div>
					<?php }?>
					
					
						<!-- Second Step Start-->
				
						<!---Second Step End -->
            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
