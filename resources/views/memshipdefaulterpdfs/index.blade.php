
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

<center><h3> <u>{{$heading}} </u>  </h3></center>
<hr>
<table   width="100%">
<tr><td width="230px"><b>Name</b></td><td width="80px"><b>Account No</b></td> <td width="50px"><b>Paid</b></td> <td width="50px"><b>Balance</b></td> </tr>
</table>
<hr>
<table  width="100%">
@foreach($details as $led)
<tr>
<td width="230px"> {{$led->name}}</td>
<td width="80px"> {{$led->acno}}</td>
<td width="50px"> {{$led->paid}}</td>
<td width="50px"> {{$led->bal}}</td>
</tr>
@endforeach
</table>
<hr>
<table  width="100%">
@foreach($total as $led)
<tr>
<td width="230px"> <b>Total</b></td>
<td width="80px"> </td>
<td width="50px"> <b>{{$led->paid}}<b></td>
<td width="50px"> <b>{{$led->bal}}<b></td>
</tr>
@endforeach
</table>