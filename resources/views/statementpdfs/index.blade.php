
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
<center><h3> Statement </h3></center>
@foreach($client as $cl)
    <b>Name  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : {{$cl->name}} </b><br>
</br><b>Account Number &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$cl->acno}}</b>
@endforeach
<br><b>Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$da}} </b>
@foreach($pdt as $t)
<br><b> Saving  Account &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$t->productname}}</b>
@endforeach
<hr>
<table    style="border-collapse:collapse" width="100%">
<tr><td width="50px"><b>Date</b></td><td width="100px"><b>Narration</b></td> <td width="50px"><b>Reciept#</b></td> 
<td width="50px"><b>Money Out</b></td> 
<td width="50px"><b>Money In</b></td> 
<td width="50px"><b>Ledger Bal</b></td> 
</tr>
@foreach($ledger as $led)
<tr>
<td>{{$led->date}} </td>
<td>{{$led->narration}} </td>
<td>{{$led->paydet}} </td>
<td>{{$led->moneyout}} </td>
<td>{{$led->moneyin}} </td>
<td>{{$led->runningbal}} </td>
</tr>
@endforeach
<td style="border-style:solid; border-width:2px 0px 0px 0px"></td>
<td style="border-style:solid; border-width:2px 0px 0px 0px"></td>
<td style="border-style:solid; border-width:2px 0px 0px 0px"></td>
<td style="border-style:solid; border-width:2px 0px 0px 0px"></td>
<td style="border-style:solid; border-width:2px 0px 0px 0px"></td>
<td style="border-style:solid; border-width:2px 0px 0px 0px"></td>
</table>