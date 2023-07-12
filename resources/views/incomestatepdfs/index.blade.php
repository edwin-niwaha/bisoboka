
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
<center><h3> Profit & Loss <br>{{$asof}}</h3></center><br>

<hr>
<table width="100%">
<tr>
<th width="300"> Account Name</th>
<th width="100">Account code </th>
<th width="100" align="right"> Amount</th>
</tr>
@foreach($income as $t)
<tr>
<td>{{$t->accountname}}</td>
<td>{{$t->accountcode}}</td>
<td align="right">{{$t->amount}}</td>
</tr>
@endforeach
@foreach($totalincome as $incomes)
<tr><th >Total Income  </th><th></th><th align="right">{{$incomes->amount}}</th></tr>
@endforeach
</table>
<hr>
<table width="100%">
@foreach($expense as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>
<td align="right" width="100">{{$t->amount}}</td>
@endforeach
</tr>

<tr><th>Total Expense  </th><th></th><th align="right">@foreach($totalexpense as $expense)
{{$expense->amount}}</th></tr>
@endforeach

</table>
<hr>
<table width="100%">
<tr>
<th width="300">Net Profits </th> <th  width="100"> </th> <th align="right" width="100"> 
@foreach($totalincome as $income)
@foreach($totalexpense as $expense)
<?php

echo number_format(str_replace( ',', '',$income->amount)-str_replace( ',', '',$expense->amount),0);
?>
@endforeach
@endforeach

 </th>
</tr>
</table>
