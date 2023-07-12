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
<center><h3> Trial Balance<br>{{$asof}} </h3></center>
<hr>
<table width="100%">
<tr>
<th> Account Name</th>
<th>Account code </th>
<th> Debit</th>
<th> Credit </th>
</tr>
@foreach($trial as $t)
<tr>
<td>{{$t->accountname}}</td>
<td>{{$t->accountcode}}</td>
<td>{{$t->Debits}}</td>
<td>{{$t->Credits}}</td>
</tr>
@endforeach
</table>
<hr>
<table width="100%">
@foreach($footer as $foot)
<tr>
<td > </td>
<td > </td>
<td width="110">{{$foot->Debits}} </td>
<td width="110">{{$foot->Credits}} </td>
</tr>
@endforeach
</table>
