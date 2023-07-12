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
@foreach($client as $cl)
    <b>Name&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{$cl->name}} </b><br>
</br><b>Account Number : {{$cl->acno}}</b>
@endforeach
<br><b>Date &nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  {{$da}}</b>
<hr>
<table    style="border-collapse:collapse" width="100%">
<tr><td width="65px"><b>Date</b></td><td width="150px"><b>Narration</b></td> <td width="30px"><b>Reciept#</b></td> 
@foreach($active as $act)
@if($act->savingpdt=='shares' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}} </b></td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='savingpdt1' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif

@if($act->savingpdt=='savingpdt2' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='savingpdt2' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif

@if($act->savingpdt=='savingpdt3' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='savingpdt3' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif

@if($act->savingpdt=='savingpdt4' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='savingpdt4' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif

@if($act->savingpdt=='savingpdt5' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='savingpdt5' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif

@if($act->savingpdt=='ansub' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='ansub' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif

@if($act->savingpdt=='memship' && $act->isActive==1)
<td width="30px"><b>{{$act->productname}}</b> </td>
@if($act->savingpdt=='memship' && $act->intActive==1)
<td width="30px"><b>{{$act->interest}}</b> </td>
@endif
@endif
@endforeach

<!-- Loans -->
@foreach($loans as $ln)
@if($ln->loanpdt=='loanpdt1' && $ln->isActive==1)
<td width="30px"><b>{{$ln->name}}</b></td>
<td width="30px"><b>Interest</b></td>
@endif
@if($ln->loanpdt=='loanpdt2' && $ln->isActive==1)
<td width="30px"><b>{{$ln->name}}</b></td>
<td width="30px"><b>Interest</b></td>
@endif
@if($ln->loanpdt=='loanpdt3' && $ln->isActive==1)
<td width="30px"><b>{{$ln->name}}</b></td>
<td width="30px"><b>Interest</b></td>
@endif
@if($ln->loanpdt=='loanpdt4' && $ln->isActive==1)
<td width="30px"><b>{{$ln->name}}</b></td>
<td width="30px"><b>Interest</b></td>
@endif
@endforeach
 </tr>



@foreach($rpt as $rp)

<tr>

<td width="65px"> {{$rp->date}}</td>
<td width="150px"> {{$rp->narration}}</td>
<td width="30px"> {{$rp->recieptno}}</td>
@foreach($active as $act)
@if($act->savingpdt=='shares' && $act->isActive==1)
<td width="70px"> {{$rp->shares}}</td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt1}}</td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->intActive==1)
<td width="30px"> {{$rp->intpdt1}}</td>
@endif

@if($act->savingpdt=='savingpdt2' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt2}}</td>
@endif
@if($act->savingpdt=='savingpdt2' && $act->intActive==1)
<td width="30px"> {{$rp->intpdt2}}</td>
@endif

@if($act->savingpdt=='savingpdt3' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt3}}</td>
@endif
@if($act->savingpdt=='savingpdt3' && $act->intActive==1)
<td width="30px"> {{$rp->intpdt3}}</td>
@endif

@if($act->savingpdt=='savingpdt4' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt4}}</td>
@endif
@if($act->savingpdt=='savingpdt4' && $act->intActive==1)
<td width="30px"> {{$rp->intpdt4}}</td>
@endif

@if($act->savingpdt=='savingpdt5' && $act->isActive==1)
<td width="30px"> {{$rp->savingpdt5}}</td>
@endif
@if($act->savingpdt=='savingpdt5' && $act->intActive==1)
<td width="30px"> {{$rp->intpdt5}}</td>
@endif
@if($act->savingpdt=='ansub' && $act->isActive==1)
<td width="30px"> {{$rp->ansub}}</td>
@endif
@if($act->savingpdt=='memship' && $act->isActive==1)
<td width="30px"> {{$rp->memship}}</td>
@endif
@endforeach
<!-- Loan Products -->
@foreach($loans as $ln)
@if($ln->loanpdt=='loanpdt1' && $ln->isActive==1)
<td width="30px">{{$rp->loanpdt1}} </td>
<td width="30px">{{$rp->loanint1}} </td>
@endif

@if($ln->loanpdt=='loanpdt2' && $ln->isActive==1)
<td width="30px">{{$rp->loanpdt2}} </td>
<td width="30px">{{$rp->loanint2}} </td>
@endif

@if($ln->loanpdt=='loanpdt3' && $ln->isActive==1)
<td width="30px">{{$rp->loanpdt3}} </td>
<td width="30px">{{$rp->loanint3}} </td>
@endif

@if($ln->loanpdt=='loanpdt4' && $ln->isActive==1)
<td width="30px">{{$rp->loanpdt4}} </td>
<td width="30px">{{$rp->loanint4}} </td>
@endif
@endforeach
</tr>

@endforeach
<!-- Footers -->



<tr>
@foreach($footer as $foot)
<td width="65px" style="border-style:solid; border-width:2px 0px 0px 0px"> </td>
<td width="150px" style="border-style:solid; border-width:2px 0px 0px 0px">  </td>
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>Total</b> </td>
@foreach($active as $act)
@if($act->savingpdt=='shares' && $act->isActive==1)
<td width="70px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->shares}}</b></td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b> {{$foot->savingpdt1}}</b></td>
@endif
@if($act->savingpdt=='savingpdt1' && $act->intActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->intpdt1}}</b></td>
@endif

@if($act->savingpdt=='savingpdt2' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt2}}</b></td>
@endif
@if($act->savingpdt=='savingpdt2' && $act->intActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b> {{$foot->intpdt2}}</b></td>
@endif

@if($act->savingpdt=='savingpdt3' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt3}}</b></td>
@endif
@if($act->savingpdt=='savingpdt3' && $act->intActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->intpdt3}}</b></td>
@endif

@if($act->savingpdt=='savingpdt4' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt1}}</b></td>
@endif
@if($act->savingpdt=='savingpdt4' && $act->intActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b> {{$foot->intpdt4}}</b></td>
@endif

@if($act->savingpdt=='savingpdt5' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->savingpdt5}}</b></td>
@endif
@if($act->savingpdt=='savingpdt5' && $act->intActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->intpdt5}}</b></td>
@endif
@if($act->savingpdt=='ansub' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->ansub}}</b></td>
@endif
@if($act->savingpdt=='memship' && $act->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"> <b>{{$foot->memship}}</b></td>
@endif
@endforeach
<!-- Loan Products -->
@foreach($loans as $ln)
@if($ln->loanpdt=='loanpdt1' && $ln->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanpdt1}}</b> </td>
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanint1}}</b> </td>
@endif

@if($ln->loanpdt=='loanpdt2' && $ln->isActive==1)
<td width="30px style="border-style:solid; border-width:2px 0px 0px 0px""><b>{{$foot->loanpdt2}}</b> </td>
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanint2}}</b> </td>
@endif

@if($ln->loanpdt=='loanpdt3' && $ln->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanpdt3}}</b> </td>
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanint3}}</b> </td>
@endif

@if($ln->loanpdt=='loanpdt4' && $ln->isActive==1)
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanpdt4}}</b> </td>
<td width="30px" style="border-style:solid; border-width:2px 0px 0px 0px"><b>{{$foot->loanint4}}</b> </td>
@endif
@endforeach
@endforeach
</tr>

<!--End of Footer -->
</table>
