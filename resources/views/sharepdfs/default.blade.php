
<div style="font-size:18px;font-weight: bold;">
@foreach($company as $companys)
{{$companys->name}}<br>
{{$companys->location}} {{$companys->boxno}}<br>
Tel :{{$companys->phone}}<br>
Email:{{$companys->email}}<br>
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:580px;top:-16px;' width='140px' alt='' />"; ?>
@endforeach

</div>
<br>
<center><h4> <u>Share Report</u> </h4> </center>
<hr>
<table width="100%">
<tr><th> Name </th><th> No of Shares </th> <th> Share Amount </th></tr>
@foreach($ledger as $ledger)
<tr>
<td>{{$ledger->name}}</td>
<td>{{$ledger->noshares}}</td>
<td>{{$ledger->shares}}</td>
</tr>
@endforeach
<tr>
@foreach($foot as $f)
<td><b>Total</b></td>
<td><b>{{$f->noshares}}</b> </td>
<td><b>{{$f->shares}}</b> </td>

</tr>
@endforeach
</table>
<hr>
