
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
Email:{{$companys->email}}<br><br>
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:580px;top:-14px;' width='140px' alt='' />"; ?>
@endforeach
</div>
<center><h4>Loan Balance Report</h4> </center>
<hr>
<table class="loandata" border="0" width="100%" >

<tr><th height="20">Name</th><th>Loan Bal</th><th>Interest</th><th>Total Amt</th></tr>
@foreach($arrears as $arrears)

<tr><td>{{$arrears->name}}</td><td>{{$arrears->loan}}</td><td>{{$arrears->interest}}</td><td>{{$arrears->total}}</td></tr>
@endforeach
<tr>

@foreach($total as $total)
<td style="border-style:solid; border-width:2px 0px 0px 0px">Total </td> <td style="border-style:solid; border-width:2px 0px 0px 0px">{{$total->loan}}</td><td style="border-style:solid; border-width:2px 0px 0px 0px">{{$total->interest}}</td><td style="border-style:solid; border-width:2px 0px 0px 0px">{{$total->total}}</td>

@endforeach

</tr>

</table>


</table>