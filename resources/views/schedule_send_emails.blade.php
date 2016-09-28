@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Schedule or Send Email</div>

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
									<label class="col-md-2 control-label">Select</label>

									<div class="col-md-10">
										<label class="radio-inline">
										  <input type="radio" name="optradio" checked="checked">Team
										</label>
										<label class="radio-inline">
										  <input type="radio" name="optradio" >Individual
										</label>
									</div>
								</div>
							
						<!-- Team -->
						<div class="form-group">
								<label class="col-md-2 control-label">Team</label>

								<div class="col-md-10">
									<select name="group_name" id="group_name" class="form-control">
										@foreach($all_teams as $team)
											<option value="{{$team->id}}" @if($team->id==$current_team->id) {{"selected"}} @else {{""}} @endif>{{$team->name}}</option>
										@endforeach
									</select>

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
					  	<!-- Individual -->
						<div class="form-group">
								<label class="col-md-2 control-label">Select Email(s)</label>

								<div class="col-md-10">
									<input type="text" name="last_name" id="last_name" class="form-control">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						
						<!-- Template Name -->
						<div class="form-group">
								<label class="col-md-2 control-label">Select Template</label>

								<div class="col-md-10">
									<select name="template_name" id="template_name" class="form-control">
										<option value="">Select</option>
										@foreach($email_templates as $template)
											<option value="{{$template->id}}">{{$template->template_name}}</option>
										@endforeach
									</select>
									

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<!-- Email -->
						<div class="form-group">
								<label class="col-md-2 control-label">Schedule Date</label>

								<div class="col-md-10">
									<input type="text" name="schedule-date" id="schedule-date" class="form-control">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						
							<!-- Update Button -->
							<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
									<button class="btn btn-primary" type="submit" name="schedule">
										Save
									</button>
									
								</div>
							</div>
            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
