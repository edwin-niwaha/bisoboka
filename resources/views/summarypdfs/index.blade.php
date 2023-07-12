<style>
#watermark {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   3cm;
                opacity: .3;
                left:     9.5cm;

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
<?php echo "<img src='images/$companys->logo' style='position:absolute;left:880px;top:-14px;' width='140px' alt='' />"; ?>
@endforeach
</div>

<center><h3> Statement </h3></center>
<hr>
<table    style="border-collapse:collapse" width="100%">
<tr><td width="100px"><b>Name</b></td>
@foreach($active as $act)
@if($act->savingpdt=='shares' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}} </b></td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@endif

@if($act->savingpdt=='savingpdt2' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@endif

@if($act->savingpdt=='savingpdt3' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@endif

@if($act->savingpdt=='savingpdt4' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@endif

@if($act->savingpdt=='savingpdt5' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@endif
@endforeach

<!-- Loans -->

 </tr>



@foreach($rpt as $rp)

<tr>
<td>{{$rp->name}} </td>
@foreach($active as $act)

@if($act->savingpdt=='shares' && $act->isActive==1)
<td width="70px"> {{$rp->shares}}</td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt1}}</td>
@endif

@if($act->savingpdt=='savingpdt2' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt2}}</td>
@endif

@if($act->savingpdt=='savingpdt3' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt3}}</td>
@endif


@if($act->savingpdt=='savingpdt4' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt4}}</td>
@endif


@if($act->savingpdt=='savingpdt5' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt5}}</td>
@endif

@endforeach
<!-- Loan Products -->

</tr>

@endforeach
<!-- Footers -->



<tr>
@foreach($footer as $foot)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>Total</b> </td>
@foreach($active as $act)
@if($act->savingpdt=='shares' && $act->isActive==1)
<td width="70px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->shares}}</b></td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b> {{$foot->savingpdt1}}</b></td>
@endif

@if($act->savingpdt=='savingpdt2' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt2}}</b></td>
@endif

@if($act->savingpdt=='savingpdt3' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt3}}</b></td>
@endif

@if($act->savingpdt=='savingpdt4' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt4}}</b></td>
@endif


@if($act->savingpdt=='savingpdt5' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt5}}</b></td>
@endif
@endforeach

@endforeach
</tr>

<!--End of Footer -->
</table>
