
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

<center><h3> <u>Expenses  {{$asof}}</u>  </h3></center>
<hr>
<table   width="100%">
<tr><td width="80px"><b>Date</b></td><td width="350px"><b>Narration</b></td> <td width="50px"><b>Amount</b></td>  </tr>
</table>
<hr>
<table  width="100%">
@foreach($expense as $led)
<tr>
<td width="80px"> {{$led->transdate}}</td>
<td width="350px"> {{$led->narration}}</td>
<td width="50px"> {{$led->amount}}</td>
</tr>
@endforeach
</table>
<hr>
<table  width="100%">
@foreach($expensetotal as $led)
<tr>
<td width="80px"> <b>Total</b></td>
<td width="350px"> </td>
<td width="50px"> <b>{{$led->amount}}<b></td>
</tr>
@endforeach
</table>