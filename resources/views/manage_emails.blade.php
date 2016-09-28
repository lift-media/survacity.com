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
							<a href="/schedule-send-emails"><button class="btn btn-sm btn-primary pull-right mtb10" type="button">Schedule or Send Email</button></a>
						</span>
					 </div>
                    <div class="panel-body">
                       @{{ upperName }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
