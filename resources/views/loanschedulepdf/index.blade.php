<title>Loan Schedule </title>
<head>

<style>
.loandata{
  border-collapse:collapse;
   tr:nth-child(even) td{background-color:red;}
}
</style>

</head>
<body>

<div style="font-size:18px;font-weight: bold;">
@foreach($company as $companys)
{{$companys->name}}<br>
{{$companys->location}} {{$companys->boxno}}<br>
Tel :{{$companys->phone}}<br>
Email:{{$companys->email}}<br><br>
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:580px;top:-14px;' width='140px' alt='' />"; ?>
@endforeach
</div>
<center><b> Loan Repayment Schedule</b> </center>
<div class="loaninfo">
<fieldset>
<legend>Loan Information </legend>
<table  width="100%" >
@foreach($loaninfo as $loaninfo)
<tr><th>Loan Amount </th><th>{{$loaninfo->loan}} </th> <th>Loan  Date</th><th>{{$loaninfo->date}} </th></tr>
<tr><th>Interest Rate </th><th>{{$loaninfo->loaninterest}}%</th><th>Interest Amount </th><th>{{$loaninfo->interest}}</th></tr>
<tr><th>Repayment period </th> <th>{{$loaninfo->loanrepay}} Month (s) </th><th>Loan Applicant </th><th>{{$loaninfo->name}} </th></tr>
@endforeach
</table>
</fieldset>
</div>
<br><br>


<table class="loandata" border="0" width="100%" >

<tr style="text-align:center;background-color:black;color:white;"><th height="20">#</th><th height="20">PAYMENT DATE</th><th>PRINCIPLE</th><th>INTEREST</th><th height="40px">SCHEDULED PAYMENT</th><th>BALANCE</th></tr>
@foreach($schedule as $schedule)
<tr><td style="text-align:center;">{{$schedule->nopayments}}</td><td style="text-align:center;">{{$schedule->date}}</td><td style="text-align:center;">{{$schedule->loanamount}}</td><td style="text-align:center;">{{$schedule->interest}}</td><td style="text-align:center;">{{$schedule->total}}</td><td style="text-align:center;">{{$schedule->runningbal}}</td></tr>

@endforeach

</table>


</table>
</body>
</html