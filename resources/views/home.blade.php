@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard 
                    
						<span style="float:right">Current Team: {{$currentTeamName}}</span>
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
