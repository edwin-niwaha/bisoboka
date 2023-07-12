<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Perpetual Accounting </title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
		<link href="{{asset('assets/css/sb-admin-2.css')}}" rel="stylesheet">
		<link href="{{asset('assets/css/jtheme.css')}}" type="text/css" rel="stylesheet">
		<!--added today 30-09-2018
		<link rel="stylesheet" href="assets/css/icon.css" />
		<link rel="stylesheet" href="assets/css/easyui.css" />
		End of added today-->
		<link rel="stylesheet" href="{{asset('assets/font-awesome/4.5.0/css/font-awesome.min.css')}}" />
		<!--<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />-->
		<link rel="stylesheet" href="{{asset('assets/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
		<script type = "text/javascript" >
   function preventBack(){window.history.forward();}
    setTimeout("preventBack()", 0);
    window.onunload=function(){null};
	function fixComplete(){
    $.getJSON('isComplete',function(data){
    $.each(data, function (index, value) {
        var countresults=value.count;
        if(countresults>0){
            $.messager.alert('Warning','There is an Incomplete Transaction, Click ok to fix this Issue','warning');  
        }
        }); 
    });  
}
</script>

	
	</head>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default          ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>
				
				<div class="navbar-header pull-left">
					<a href="/dashboardhome" class="navbar-brand">
						<small style="font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;">
							<i class="fa fa-laf"></i>
							Perpetual Accounting-<small style="font-size:14px;" >
							@if(Auth::check())
							{{ Auth::user()->branchname }}</small>
							@endif
						</small>
						
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="grey dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-tasks"></i>
								<span class="badge badge-grey"></span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
							

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<li>
											<a href="#">
												<div class="clearfix">
													
												</div>

												<div class="progress progress-mini">
													<div style="width:65%" class="progress-bar"></div>
												</div>
											</a>
										</li>

									

									

							
									</ul>
								</li>

								<li class="dropdown-footer">
								
								</li>
							</ul>
						</li>

						<li class="purple dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span class="badge badge-important">            <?php 
            use Illuminate\Support\Facades\DB;
            $today=date("'Y/m/d'");
$result =DB::select("select if(COUNT(name)>0,COUNT(name),0) as count from loandues inner join loanschedules on loanschedules.loanid=loandues.loanid where loanamount!=0  AND scheduledate=$today");
foreach($result as $result){
	echo $result->count;
}

?>
								

								
						
									</span>
							</a>
							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-exclamation-triangle"></i>
									<?php
									            $today=date("'Y/m/d'");
												$result =DB::select("select if(COUNT(name)>0,COUNT(name),0) as count from loandues inner join loanschedules on loanschedules.loanid=loandues.loanid where loanamount!=0  AND scheduledate=$today");
												foreach($result as $result){
													echo $result->count;
												}

									?>
		 Notifications
									
								</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar navbar-pink">
									

									

									

										<li>
										
										</li>
									</ul>
								</li>

								<li class="dropdown-footer">
									<a href="/loansdue">
										See all notifications
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<li class="green dropdown-modal">
						

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">

										<li>
								
										</li>
									</ul>
								</li>

							
							</ul>
						</li>

						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="{{asset('assets/images/avatars/avatar2.png')}}" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									
									{{ Auth::user()->name }}
									
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>

								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="{{ route('logout') }}" onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
@include('layouts/sidemenu')
@include('layouts/content')
<!--[if !IE]> -->
<script src="{{asset('assets/js/jquery-2.1.4.min.js')}}"></script>

<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>



<!-- ace scripts -->
<script src="{{asset('assets/js/ace-elements.min.js')}}"></script>
<script src="{{asset('assets/js/ace.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/easyui.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/icon.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/color.css')}}">
<!--<link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/demo/demo.css">-->


<script type="text/javascript" src="{{asset('assets/js/jquery-1.9.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/jquery.easyui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/jquery.edatagrid.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/validate.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/fastclick/lib/fastclick.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/datagrid-groupview.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/datagrid-detailview.js')}}"></script>

