<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    <!-- CSS -->
    <link href="/css/sweetalert.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="/datatables/jquery.dataTables.min.css">
	<link href="/css/oneui.css" rel="stylesheet">
	<link href="/css/custom.css" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">	
	<!--<link rel="stylesheet" href="{{url('css')}}/styles.css"> Used for muliple email select-->
	<link rel="stylesheet" href="{{url('css/token-input.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{url('css/token-input-fb.css')}}" type="text/css" />
	
	<link rel="stylesheet" href="{{url('css/bootstrap-datetimepicker.min.css')}}" type="text/css" />
	
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="{{asset('js/jquery.tokeninput.js')}}"></script>
    
    <script type="text/javascript" src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    @yield('scripts', '')

    <!-- Global Spark Object -->
    <script>
        window.Spark = <?php echo json_encode(array_merge(
            Spark::scriptVariables(), []
        )); ?>;
    </script>
    <script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
      <script>
	  tinymce.init({
		selector: '#template_body',
		menubar: false
	  });
	  tinymce.init({
		selector: '#template_signature',
		menubar: false
	  });
  </script>

</head>
<body class="with-navbar" v-cloak>
    <div id="spark-app">
        <!-- Navigation -->
        @if (Auth::check())
            @include('spark::nav.user')
        @else
            @include('spark::nav.guest')
        @endif

        <!-- Main Content -->
        @yield('content')

        <!-- Application Level Modals -->
        @if (Auth::check())
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
        @endif

        <!-- JavaScript -->
        <script src="/js/app.js"></script>
        <script src="/js/sweetalert.min.js"></script>
        <script src="/js/custom.js"></script>
        
        
       
        <script src="/datatables/jquery.dataTables.js"></script>

        <!-- Page JS Code -->
        <script src="/datatables/base_tables_datatables.js"></script>
    </div>
</body>
</html>
