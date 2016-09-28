@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Contact Detail</div>

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
                       <form role="form" class="form-horizontal" method="post" name="edit_contact" id="edit_contact">
								
							{!! csrf_field() !!}
						<!-- First Name -->
						<div class="form-group">
								<label class="col-md-2 control-label">First Name</label>

								<div class="col-md-10">
									<input type="text" name="first_name" id="first_name" class="form-control">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<!-- Last Name -->
						<div class="form-group">
								<label class="col-md-2 control-label">Last Name</label>

								<div class="col-md-10">
									<input type="text" name="last_name" id="last_name" class="form-control">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						<!-- Email -->
						<div class="form-group">
								<label class="col-md-2 control-label">Email</label>

								<div class="col-md-10">
									<input type="text" name="email" id="email" class="form-control">

									<span class="help-block" style="display: none;">
										
									</span>
								</div>
						</div>	
						
							<!-- Update Button -->
							<div class="form-group">
								<div class="col-md-offset-2 col-md-10">
									<button class="btn btn-primary" type="submit">
										Add Contact
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
