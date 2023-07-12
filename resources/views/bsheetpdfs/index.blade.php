
<style>
#watermark {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   9cm;
                opacity: .3;
                left:     5.5cm;

                /** Change image dimensions**/
                width:    8cm;
                height:   8cm;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
        </style>

<div id="watermark">
@foreach($company as $c)
<?php echo "<img src='images/$c->logo' width='100%' height='100%' alt='' />"; ?>
            @endforeach
        </div>
<div style="font-size:18px;font-weight: bold;">
@foreach($company as $companys)
{{$companys->name}}<br>
{{$companys->location}} {{$companys->boxno}}<br>
Tel :{{$companys->phone}}<br>
Email:{{$companys->email}}<br>
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:580px;top:-16px;' width='140px' alt='' />"; ?>
@endforeach

</div>
<center><h3> Statement of Financial Position  <br>
As of {{$asof}} </h3></center>
<hr>
<div> <b>ASSETS</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Current Assets</b> </div>
<table width="100%">

@foreach($cass as $ass)
<tr>
<td width="100">{{$ass->accountcode}} - {{$ass->accountname}}</td>
<td  align="right" width="20">{{$ass->amount}}</td>
</tr>
@endforeach
<tr>
@foreach($tcass as $t)
<th width="100" >Total Current Assests</th> <th  align="right" width="20">{{$t->amount}} </th>
@endforeach
</tr>
<div> <b></b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fixed Assets</b> </div>
</table>
<!-- Fixed Assests -->
<table width="100%">

@foreach($fass as $ass)
<tr>
<td width="">{{$ass->accountcode}} - {{$ass->accountname}}</td>
<td  align="right" width="">{{$ass->amount}}</td>
</tr>
@endforeach
<tr>
@foreach($tfass as $t)
<th>Total Fixed Assets</th> <th align="right" >{{$t->amount}} </th>
@endforeach
</tr>
<tr>
<th> <br><br>TOTAL ASSETS </th> <th  align="right" style="border-style:solid; border-width:0px 0px 3px 0px" width="185">
@foreach($totalass as $ass)
{{$ass->amount}}
@endforeach
 </th>
</tr>
</table>
<hr>
<div> <b>LIABILITIES & EQUITY</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Liabilities</b> </div>
<table width="100%">

@foreach($liabilites as $ass)
<tr>
<td width="100">{{$ass->accountcode}} - {{$ass->accountname}}</td>
<td align="right"  width="55">{{$ass->amount}}</td>
</tr>
@endforeach
<tr>
@foreach($totallia as $t)
<th>Total Liabilities</th> <th align="right" >{{$t->amount}} </th>
@endforeach
</tr>
<div> <b></b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Equity</b> </div>
</table>
<table width="100%">

@foreach($equity as $ass)
<tr>
<td width="100">{{$ass->accountcode}} - {{$ass->accountname}}</td>
<td  align="right"  width="55">{{$ass->amount}}</td>
</tr>
@endforeach
<tr>

<td>Net Income </td> <td align="right" >
@foreach($totalincome as $income)
@foreach($totalexpense as $expense)
<?php

echo number_format(str_replace( ',', '',$income->amount)-str_replace( ',', '',$expense->amount),0);
//$netincome=$income->amount-$expense->amount;
$netincome= (str_replace( ',', '',$income->amount)-str_replace( ',', '',$expense->amount));

?>
@endforeach
@endforeach
  </td>
</tr>
<tr>
@foreach($totalequity as $t)
<th>Total Equity</th> <th align="right" ><?php  $total=str_replace( ',', '',$t->amount);
echo  number_format(intval($total)+$netincome,0);

?> </th>
@endforeach
</tr>
<tr>
<th><br><br> TOTAL LIABILITIES & EQUITY </th> <th align="right" >
@foreach($totallia as $lia)
<?php  $totalliab=$lia->amount; ?>
@endforeach
@foreach($totalequity as $eq)
<?php $totaleq=$eq->amount; ?>
@endforeach
<?php
//echo $totaleq;
//echo $totalliab;
//echo $netincome;
echo number_format(intval(str_replace( ',', '',$totalliab))+intval(str_replace( ',', '',$totaleq))+intval($netincome));
//echo number_format(str_replace( ',', '',$totaleq)+str_replace( ',', '',$totalliab)+$netincome,0);
?>
 </th>
</tr>
</table>
<hr>

