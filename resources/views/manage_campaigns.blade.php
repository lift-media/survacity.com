@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Emails
						<span style="float:right;">
							<a href="/create-campaign"><button class="btn btn-sm btn-primary pull-right mtb10" type="button">Create Campaign</button></a>
						</span>
					 </div>
                    <div class="panel-body">
                        @if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif
                        <table class="responsive table table-bordered table-striped js-dataTable-full table-hover">
						<thead>
						<tr>
							<th>S.no.</th>
							<th>Campaign Name</th>
							<th>Total Step</th>	
							<th>Status</th>							
							<th></th>
						</tr>
					   </thead>
					   <tbody>
						<?php $i=1;?>   
                       @foreach($campaign as $email_template)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $email_template['campaign_name'] }}</td>
								<td>{{ $email_template['total_steps'] }}</td>
								<td>@if($email_template['status']=="0") {{"Pending"}} @else {{"Sent"}} @endif</td>								
								<td>
									<a href="/edit-campaign/{{$email_template['id']}}">
										<button type="button" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button>
									</a>
									<a href="#" onclick="return customizedConfirm('{{url('/delete-campaign')}}/{{$email_template['id']}}');">
										<button type="button" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button>
									</a>
								</td>
							</tr>
						<?php $i++;?>	
                       @endforeach
                      </tbody>
                   </table>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
