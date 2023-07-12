<html>
<head>
<style>
table{
    width:100%;
}
table {
  border: 1px solid black;
  border-collapse: collapse;
  text-align:center;
  border-left: none;
  border-right: none;
  border-bottom:none;

}
</style>
</head>
<body>
<div style="width:100%;height:150px;background-color:;">
<div class='name'>
<p>COIN WORTH SAVINGS UGANDA</p>
Tel: +256 779 179 810<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+256 702 540 938<br>
<table>

@foreach($name as $name)
<tr><td>ACCOUNT NAME : </td><td>{{$name->name}}</td></tr>
<tr><td>ACCOUNT NUMBER: </td><td>{{$name->id}} </td></tr>
@endforeach

</table>
</div>
<div class='address'>

</div>
</div>

<table border="1">
<tr><td padding="4px"> Transcation </td></tr>
<tr><td>Trans Date</td><td>Description</td><td>Payment</td><td>Loan</td><td>Interest</td><td>End Date</td></tr>
</table>
<table border="1">
@foreach($results as $res)
<tr  cellspacing="7px"><td >{{$res->date}} </td><td>{{$res->narration}} </td><td>{{$res->loancredit}} </td><td>{{$res->loan}} </td>
<td>{{$res->interestcredit}} </td><td>{{$res->expecteddate}} </td></tr>
@endforeach
</table>
</body>
</html