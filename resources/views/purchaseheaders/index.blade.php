@include('layouts/header')
<script type="text/javascript">


<center><h4>Purchase Stock </h4></center>
<div style='padding:5px;' id='purchaseheaders' /><a href='javascript:void(0)' class='btn btn-primary'id='savepurchaseheaders'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
<form style='width:100%;padding:5px;'  id='dlgpurchaseheaders' toolbar='#purchaseheaders'><form id='frmpurchaseheaders'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input type='date' class='form-control' name='transdate' 
 id='transdate'  /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Mode of Payment</label></div><input  class='form-control easyui-combobox' style="width:100%;" data-options="url:'combomodeofpayments',valueField:'id',textField:'name',method:'get'" name='mode' 
 id='mode' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Supplier</label></div><input  class='form-control easyui-combobox' style="width:100%;" data-options="url:'supplierscombo',valueField:'id',textField:'companyName',method:'get'" name='supplier_id' 
 id='supplier_id' /></div>
</div>

</form>

</div></div>
<table class='easyui-datagrid' title='stockitem' rownumbers='true' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='gridstocktrans'  fitColumns='true' style='width:100%' toolbar='#stocktranstoolbar'>
    <thead><tr>
   <!-- <th field='id' width='100'>id</th>-->
    <th field='stockid'  width='100'>PdtName</th>
    <th field='sellingrate' width='100'>Sellingrate</th>
    <th field='quantity' editor="numberbox" width='100'>Quantity</th>
    <th field='totalpaid' width='100'>Totalpaid</th>
    <th field='totaldue' width='100'>Totaldue</th>
    
    
    </tr></thead>
    </table>
    

    <div id='stocktranstoolbar'>
     <a href='javascript:void(0)' class='easyui-linkbutton' id='newstocktrans'onclick="javascript:$('#gridstocktrans').edatagrid('addRow')" iconCls='icon-add' >Add</a>
    <a href='javascript:void(0)' class='easyui-linkbutton' id='editstocktrans' iconCls='icon-edit' > Edit</a>
    <a href='javascript:void(0)' class='easyui-linkbutton' id='deletestocktrans' iconCls='icon-remove' > Delete</a> </div>
    
{{csrf_field()}}
<script>

 $(document).ready(function(){
//Auto Generated code for New Entry Code


//Auto Generated Code for Saving
$('#savepurchaseheaders').click(function(){ 
var id=$('#id').val();
var transdate=$('#transdate').val();
var mode=$('#mode').val();
var supplier_id=$('#supplier_id').val();
var customer_id=$('#customer_id').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:'/savepurchaseheaders',
method:'POST',
data:{'id':id,'transdate':transdate,'mode':mode,'supplier_id':supplier_id,'customer_id':customer_id,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgpurchaseheaders').dialog('close');
  
$('#gridpurchaseheaders').datagrid('reload');
});
//Auto generated code for deleting
$('#deletepurchaseheaders').click(function(){

    var a=$('#gridpurchaseheaders').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridpurchaseheaders').datagrid('getSelected');
                $.ajax({
                 url:'/destroypurchaseheaders/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridpurchaseheaders').datagrid('reload');
            }

});
}
});


// adding a new row to the grid


});




</script>

