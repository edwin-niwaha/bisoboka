@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgstocktrans' toolbar='#stocktrans'><form id='frmstocktrans'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>purchaseheaderid</label></div><input type='text' class='form-control' name='purchaseheaderid' 
 id='purchaseheaderid' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>transdate</label></div><input type='text' class='form-control' name='transdate' 
 id='transdate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>stockid</label></div><input type='text' class='form-control' name='stockid' 
 id='stockid' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>quantity</label></div><input type='text' class='form-control' name='quantity' 
 id='quantity' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>totalpaid</label></div><input type='text' class='form-control' name='totalpaid' 
 id='totalpaid' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>totaldue</label></div><input type='text' class='form-control' name='totaldue' 
 id='totaldue' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>sellingrate</label></div><input type='text' class='form-control' name='sellingrate' 
 id='sellingrate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>created_at</label></div><input type='text' class='form-control' name='created_at' 
 id='created_at' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>updated_at</label></div><input type='text' class='form-control' name='updated_at' 
 id='updated_at' /></div>
</div>
<div style='padding:5px;' id='stocktrans' /><a href='javascript:void(0)' class='btn btn-primary'id='savestocktrans'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='stocktrans' iconCls='fa fa-table' singleSelect='true' url='viewstocktrans' pagination='true' id='gridstocktrans' method='get' fitColumns='true' style='width:100%' toolbar='#stocktranstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='purchaseheaderid' width='100'>purchaseheaderid</th>
<th field='transdate' width='100'>transdate</th>
<th field='stockid' width='100'>stockid</th>
<th field='quantity' width='100'>quantity</th>
<th field='totalpaid' width='100'>totalpaid</th>
<th field='totaldue' width='100'>totaldue</th>
<th field='sellingrate' width='100'>sellingrate</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='stocktranstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newstocktrans' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editstocktrans' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletestocktrans' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newstocktrans').click(function(){
       $('#dlgstocktrans').dialog('open').dialog('setTitle','New stocktrans');
url='/savestocktrans';
$('#frmstocktrans').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editstocktrans').click(function(){
       
 var row=$('#gridstocktrans').datagrid('getSelected');
       $('#dlgstocktrans').dialog('open').dialog('setTitle','Edit stocktrans');

       $('#frmstocktrans').form('load',row);
       url='/editstocktrans/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savestocktrans').click(function(){ 
var id=$('#id').val();
var purchaseheaderid=$('#purchaseheaderid').val();
var transdate=$('#transdate').val();
var stockid=$('#stockid').val();
var quantity=$('#quantity').val();
var totalpaid=$('#totalpaid').val();
var totaldue=$('#totaldue').val();
var sellingrate=$('#sellingrate').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'purchaseheaderid':purchaseheaderid,'transdate':transdate,'stockid':stockid,'quantity':quantity,'totalpaid':totalpaid,'totaldue':totaldue,'sellingrate':sellingrate,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgstocktrans').dialog('close');
  
$('#gridstocktrans').datagrid('reload');
});
//Auto generated code for deleting
$('#deletestocktrans').click(function(){

    var a=$('#gridstocktrans').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridstocktrans').datagrid('getSelected');
                $.ajax({
                 url:'/destroystocktrans/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridstocktrans').datagrid('reload');
            }

});
}
});

});
</script>