
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
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:580px;top:-14px;' width='140px' alt='' />"; ?>
@endforeach
</div>
<center><h3> Chart of Accounts </h3></center>
<hr>
<table width="100%">
<tr>
<th width="300"> Account Name</th>
<th width="100">Account code </th>
</tr>
</table>
<hr>
<!-- Current Assests -->
<table width="100%">
<caption> Current Assets </caption>

@foreach($cassets as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>
<!-- Fixed Assests -->
<hr>
<table width="100%">
<caption> Fixed Assets </caption>

@foreach($fassets as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>
<!-- Current Liablities -->
<hr>
<table width="100%">
<caption> Current Liabilities </caption>

@foreach($cliabilities as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>
<!-- Long Term Liablities -->
<hr>
<table width="100%">
<caption> Long Term Liabilities </caption>

@foreach($ltliabilities as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>
<!-- Equity -->
<hr>
<table width="100%">
<caption> Equity </caption>

@foreach($equity as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>
<!-- Income -->
<hr>
<table width="100%">
<caption> Income </caption>

@foreach($income as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>
<!-- Expense -->
<hr>
<table width="100%">
<caption> Expense </caption>

@foreach($expense as $t)
<tr>
<td width="300">{{$t->accountname}}</td>
<td width="100">{{$t->accountcode}}</td>

</tr>
@endforeach
</table>

