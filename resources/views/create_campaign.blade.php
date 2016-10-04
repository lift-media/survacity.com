@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Campaign</div>

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
									
									<input type="text" name="campaign_name" id="campaign_name" class="form-control"> 
									<div id="campaign_nameError" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>
							
						
							<div class="form-group">
								<div class="col-md-12 "><h5>Campaign Steps</h5></div>
								
							</div>
						<div class="col-md-12 "><hr></div>
						<input type="hidden" name="editCampId" id="editCampId" value="">
					<?php 
						for($i=1;$i<=10;$i++){
					?>	
					<div id="step{{$i}}">
						<div class="form-group">
								<div class="col-md-10 ">Step {{$i}}</div>
								<div class="col-md-1 " id="saveLink"><a href="#" onClick="return saveStepData({{$i}});">Save</a></div>
								<div class="col-md-1 " id="editLink" style="display:none;"><a href="#" onClick="return editStepData({{$i}});">Edit</a></div>
								
									<input type="hidden" name="editStepId{{$i}}" id="editStepId{{$i}}" value="">
									
								
								
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
											<option value="{{$template->id}}">{{$template->template_name}}</option>
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
											<option value="{{$team->id}}" @if($team->id==$current_team->id) {{"selected"}} @else {{""}} @endif>{{$team->name}}</option>
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
								<textarea name="step_description" id="step_description" rows="3" cols="50" class="form-control"></textarea>
								<div id="step_descriptionError" style="display:none;"><span class="errorClass">This field is required</span></div>
								<span class="help-block" style="display: none;">
									
								</span>
							</div>
						</div>

						<!-- Email -->
						<div class="form-group">
								<label class="col-md-2 control-label">Day</label>

								<div class="col-md-10">
									<!--<input type="text" name="schedule_date{{$i}}" id="schedule_date{{$i}}" class="form-control" readonly>
									<div id="schedule_dateError{{$i}}" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>-->
									
									<select name="schedule_date{{$i}}" id="schedule_date{{$i}}" class="form-control">
										<option value="">Select</option>
										@for($ii=1;$ii<=50;$ii++)
											<option value="{{$ii}}">{{$ii}}</option>
										@endfor
									</select>
									<div id="schedule_dateError{{$i}}" style="display:none;"><span class="errorClass">This field is required</span></div>
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
								<input type="checkbox" checked="checked" name="auto_send" id="auto_send" value="1">&nbsp;Auto Send									
								</div>
						</div>
						<div class="col-md-12 "><hr></div>
					</div>
					<?php } ?>
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
