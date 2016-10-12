@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Email Template</div>

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
						<div class="alert alert-warning">
						  <strong>Short Codes:</strong> &nbsp;&nbsp;&nbsp;{first_name} &nbsp;&nbsp;&nbsp; {last_name} &nbsp;&nbsp; {email}
						</div>
                       <form role="form" class="form-horizontal" method="post" name="add_email_template" id="add_email_template">
							
							{!! csrf_field() !!}
						<!-- Template Name -->
						<div class="form-group">
								<label class="col-md-2 control-label">Template Name</label>

								<div class="col-md-10">
									<input type="text" name="template_name" id="template_name" class="form-control" value="{{$email_templates->template_name}}">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<!-- Subject -->
							<div class="form-group">
								<label class="col-md-2 control-label">Email Subject</label>

								<div class="col-md-10">
									<input type="text" name="template_subject" id="template_subject" class="form-control" value="{{$email_templates->template_subject}}">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
							</div>

							<!-- Template Body -->
							<div class="form-group">
								<label class="col-md-2 control-label">Email Body</label>

								<div class="col-md-10">
																		
									<textarea name="template_body" id="template_body" rows="6" cols="50" class="form-control">{{$email_templates->template_body}}</textarea>	
									
									<span class="help-block" style="display: none;">
										
									</span>
								</div>
							</div>
							
							

							<!-- Update Button -->
							<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
									<button class="btn btn-primary" type="submit">
										Update Template
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
