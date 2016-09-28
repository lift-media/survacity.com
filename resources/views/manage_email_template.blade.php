@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
						<div class="panel-heading">Manage Email Template 
						<span style="float:right;">
							<a href="/add-email-template"><button class="btn btn-sm btn-primary pull-right mtb10" type="button">Add Email Template</button></a>
						</span>
					</div>

                    <div class="panel-body">
                       {{-- @upperName --}}
                         @if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif
                        <table class="table table-bordered table-striped js-dataTable-simple table-hover">
						<thead>
						<tr>
							<th>S.no.</th>
							<th>Template Name</th>
							<th>Template Subject</th>							
							<th></th>
						</tr>
					   </thead>
					   <tbody>
						<?php $i=1;?>   
                       @foreach($email_templates as $email_template)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $email_template['template_name'] }}</td>
								<td>{{ $email_template['template_subject'] }}</td>								
								<td>
									<a href="/edit-email-template/{{$email_template['id']}}">
										<button type="button" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></button>
									</a>
									<a href="#" onclick="return customizedConfirm('{{url('/delete-email-template')}}/{{$email_template['id']}}');">
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
