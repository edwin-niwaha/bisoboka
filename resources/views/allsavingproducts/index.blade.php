@include('layouts/header')
<style>
.datagrid-row-alt{
    background-color: #d9f2e7;

}
.datagrid-row-selected {
  background: #009900;
  color: white;
}
</style>
<table class='easyui-datagrid' striped="true" showFooter='true' rownumbers="true" title='Statement' iconCls='fa fa-table' singleSelect='true' url='allstate' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='date'   width='90'>Transcation Date</th>
<th  hidden field='name' width='90'>Name</th>
<th   field='narration' width='150'>Particular</th>
<th field='recieptno'  width='90'>Reciept</th>
<!-- Start  Dynamic Savings -->
@foreach($result as $results)
<th field="{{$results->savingpdt}}" width='90'>{{$results->productname}}</th>

<th field="{{$results->intpdt}}"  {{$results->hidden}} width='90'>Interest</th>

@endforeach
@foreach($loans as $results)
<th field="{{$results->loanpdt}}" width='90'>{{$results->name}}</th>
<th field="{{$results->intpdt}}"   width='90'>Interest</th>

@endforeach
</tr></thead>
</table>
<div id='customerstoolbar'>
 <label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>
<label>Name</label>
<input class="easyui-combobox" style="width:20%"  id="client" name="client" data-options="url:'customerscombo',valueField:'id',textField:'name',method:'get' "/>
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="preview" name="preview"><i class="fa fa-print"></i> Preview</a>
</div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
    $('#gridcustomers').datagrid({
    rowStyler:function (index, row) {
			if (row.stockavailabe<row.limitlevel) {
				return 'background-color:rgb(111, 179, 224);';//rgb(209, 91, 71)
			}
		}
});
   
$('#preview').click(function(){
    var client=$('#client').val();
    var date1=$('#date1').val();
    var date2=$('#date2').val();
    if(client!='' && date1=="" && date2==""){
    window.open('/allsavingpdf/'+client,'_newtab'); 
    }else if(acode!='' && date1!='' && date2!=''){
        window.open('/ledgerpdf/'+acode+'/'+date1+'/'+date2,'_newtab');   
    }

});
   
//Auto Generated code for New Entry Code
    $('#branche').combobox({
url:'combobranches',
method:'get',
valueField:'id',
textField:'branchname',
onLoadSuccess:function(){
var data=$(this).combobox('getData');
for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						$('#branche').combobox('select', data[i].id);
                      //  $('#product').combobox('reload','/stockscombo/'+data[i].id);
					
					}
}

},

});


   $('#newcustomers').click(function(){
       $('#dlgcustomers').dialog('open').dialog('setTitle','New customers');
url='/savecustomers';
$('#frmcustomers').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editcustomers').click(function(){
       
 var row=$('#gridcustomers').datagrid('getSelected');
       $('#dlgcustomers').dialog('open').dialog('setTitle','Edit customers');

       $('#frmcustomers').form('load',row);
       url='/editcustomers/'+row.id;
       
       
       });


//Auto Generated Code for Saving
$('#savecustomers').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var phone=$('#phone').val();
var address=$('#address').val();
var tel=$('#tel').val();
var email=$('#email').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'phone':phone,'address':address,'tel':tel,'email':email,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgcustomers').dialog('close');
  
$('#gridcustomers').datagrid('reload');
});
//Auto generated code for deleting
$('#deletecustomers').click(function(){

    var a=$('#gridcustomers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridcustomers').datagrid('getSelected');
                $.ajax({
                 url:'/destroycustomers/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridcustomers').datagrid('reload');
            }

});
}
});
// Searching
$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
    var client=$('#client').val();
   
$('#gridcustomers').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,client:client}

});

});
});
</script>