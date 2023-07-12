
<div style="font-size:18px;font-weight: bold;">
@foreach($company as $companys)
{{$companys->name}}<br>
{{$companys->location}} {{$companys->boxno}}<br>
Tel :{{$companys->phone}}<br>
Email:{{$companys->email}}<br>
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:580px;top:-16px;' width='140px' alt='' />"; ?>
@endforeach
</div>
<center><h4> <u>CERTIFICATE OF DEPOSIT</u></h4></center>
Dear Customer <b>@foreach($name as $nam)
{{$nam->name}}
@endforeach </b>,<br><br>
We here by confirm, that your deposit has been placed under the following terms and conditions :-<br><br>
<table width="80%" style="margin-left:80px;">
@foreach($dep as $dep)
<tr><td >Reference No </td> <td>: {{$dep->id}} </td></tr>
<tr><td>Customer No </td> <td>: {{$dep->client_id}} </td></tr>
<tr><td>Principle Amount </td> <td>: {{$dep->fixamount}} </td></tr>
<tr><td>Interest Rate </td> <td>: {{$dep->fixinterest }} % </td></tr>
<tr><td>Fix Date </td> <td>: {{$dep->fixdate }} </td></tr>
<tr><td>Maturity Date </td> <td>: {{$dep->maturitydate}} </td></tr>
@endforeach
</table>
<center><h5> <u>PAYMENT AT MATURITY</u></h5></center>
<table width="80%" style="margin-left:80px;">
@foreach($deposit as $dep)
<tr><td >Principle Amount </td> <td>: {{$dep->fixamount}} </td></tr>
<tr><td>Interest </td> <td>: {{$dep->maturityinterest}} </td></tr>
<tr><td>Net Amount Payable </td> <td>: {{$dep->payable}} </td></tr>

@endforeach
</table><br><br>
Kindly be advised that incase of premature liquidation , No Interest shall be earned.

