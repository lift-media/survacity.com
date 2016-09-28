@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Import Contacts 
                    </div>

                    <div class="panel-body">
						 @if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif
						  @if (count($errors) > 0)
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
                      <form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
						  {!! csrf_field() !!}
						<!-- Team Name -->
							<div class="form-group">
								<label class="col-md-2 control-label">Team Name</label>

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

							<!-- File Name -->
							<div class="form-group">
								<label class="col-md-2 control-label">Contacts File</label>

								<div class="col-md-10">
									<label class="btn btn-default btn-file">
										<input type="file"  name="csv" id="csv">
									</label>									
								</div>
							</div>

							<!-- Import Button -->
							<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
									<button class="btn btn-primary" type="submit">

										Import 
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
