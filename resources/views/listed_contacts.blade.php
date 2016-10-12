@extends('spark::layouts.app')

@section('content')
<!--/teams/3/switch-->
<script>
	function switchTeam(id){		
		window.location = "http://survacity.in/teams/"+id+"switch";
	}
</script>
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
						<div class="panel-heading">Listed Contacts
						<span class="text-center" style="margin-left:80px;">
							Current Team <select name="group_name" id="group_name" class="" style="width:200px;margin-left:10px;" onChange="return switchTeam(this.value);" disabled>
								@foreach($all_teams as $team)
									<option value="{{$team->id}}" @if($team->id==$current_team->id) {{"selected"}} @else {{""}} @endif>{{$team->name}}</option>
								@endforeach
							</select>
						</span>
						<span style="float:right;">
							
							<a href="/import-contacts" ><button class="btn btn-sm btn-success mtb10" type="button">Import</button></a>
							<a href="/add-contact"><button class="btn btn-sm btn-primary mtb10" type="button">Add Contact</button></a>
							
							
						</span>
					</div>

                    <div class="panel-body">
                       {{-- @upperName --}}
                         @if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif
                        <table class="responsive table table-bordered table-striped js-dataTable-full table-hover">
						<thead>
						<tr>
							<th>S.no.</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Email</th>	
														
							<th>&nbsp;</th>
						</tr>
					   </thead>
					   <tbody>
						<?php $i=1;?>   
						@if(count($contacts_all)>0)
                       @foreach($contacts_all as $email_template)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $email_template['first_name'] }}</td>
								<td>{{ $email_template['last_name'] }}</td>	
								<td>{{ $email_template['email'] }}</td>														
								<td>
									<a href="/edit-contact/{{$email_template['id']}}">
										<button type="button" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button>
									</a>
									<a href="#" onclick="return customizedConfirm('{{url('/delete-contact')}}/{{$email_template['id']}}');">
										<button type="button" class="btn btn-xs btn-default"><i class="fa fa-times"></i></button>
									</a>
								</td>
							</tr>
						<?php $i++;?>	
                       @endforeach
                       @endif
                      </tbody>
                   </table>    
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
<div class="modal" id="modal-normal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Confirmation</h3>
                </div>
                <div class="block-content">
                    <p>Are you sure?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btnYes' class="btn btn-sm btn-default submit" value="yes" type="button" data-dismiss="modal">Yes</button>
                <button id='btnNo' class="btn btn-sm btn-primary cancel" value="no" type="button" data-dismiss="modal">No</button>
                <input type='hidden' name='popupurl' id='popupurl'/>
            </div>
        </div>
    </div>
</div>
@endsection
