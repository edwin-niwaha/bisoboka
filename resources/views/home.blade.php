@include('layouts/header')
<div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-calendar fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
								@foreach($totalsales as $totalsales)
                                    <div class="huge">{{$totalsales->loan}}<b style="font-size:10px">Ushs</b></div>
									@endforeach
                                    <div>Total Daily Loans</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-money fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    @foreach($totalincome as $totalincome)
                                    <div class="huge">{{$totalincome->interest}}<b style="font-size:10px">Ushs</b></div>
									@endforeach
                                    <div>Total Daily Income recieved</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-balance-scale fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
								@foreach($totalexpenses as $totalexpenses)
                                    <div class="huge">{{$totalexpenses->amount}}<b style="font-size:10px">Ushs</b></div>
                                    <div>Total Daily Expenses</div>
									@endforeach
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-bank fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
								@foreach($outstanding as $outstanding)
                                    <div class="huge">{{$outstanding->interest}}<b style="font-size:10px">Ushs</b></div>
									@endforeach
                                    <div>Expected interest</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div><br>
            @if (session('inactive'))
            <div class="alert alert-success" style="color:black;width:23.5%;">
           <a href="/threemonthsview"> <span class="pull-right"> View Details <i class="fa fa-arrow-circle-right"></i></span></a>
            {{ session('inactive') }} 
    </div>
    @endif
    
    @if(session('loandue'))
            <div class="alert alert-danger" style="width:23.5%;" >
           <a href="/loansdue"> <span class="pull-right"> View Details <i class="fa fa-arrow-circle-right"></i></span></a>
           {{ session('loandue') }} 
    </div>
    @endif
    @if(session('fixz'))
            <div class="alert alert-info" style="width:23.5%;" >
           <a href="/loansdue"> </a>
          
          <b> MATURITY OF FIXED DEPOSITS</b><br><br>
           <ol>
         @foreach(session::get('fixz') as $pd)
<li>{{$pd->name}}-{{$pd->acno}}</li>
         @endforeach
         </ol>
    </div>
    @endif
 