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

<center><h3> Member List </h3></center>

<hr>
<table   width="100%">
<tr><td width="80px"><b>Account #</b></td><td width="300px"><b>Name</b></td> <td width="100px"><b>Telephone 1</b></td><td width="100px"><b>Telephone 2</b></td>  </tr>
</table>
<hr>
<table  width="100%">
@foreach($customers as $custo)
<tr><td width="80px">{{$custo->acno}}</td><td width="300px">{{$custo->name}}</td> <td width="100px">{{$custo->telephone1}}</td><td width="100px">{{$custo->telephone2}}</td>  </tr>
@endforeach
</table>



