@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgitems' toolbar='#items'><form id='frmitems'>
<!--<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>-->
<div class='col-lg-6'>
<div class='form-group'>
<div><label>itemname</label></div><input type='text' class='form-control' name='itemname' 
 id='itemname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>barcodename</label></div><input type='text' class='form-control' name='barcodename' 
 id='barcodename' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>groups</label></div><input type='text' class='form-control' name='groups' 
 id='groups' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>category</label></div><input type='text' class='form-control' name='category' 
 id='category' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>uom</label></div><input type='text' class='form-control' name='uom' 
 id='uom' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>mfgdate</label></div><input type='text' class='form-control' name='mfgdate' 
 id='mfgdate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>expirydate</label></div><input type='text' class='form-control' name='expirydate' 
 id='expirydate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>rate</label></div><input type='text' class='form-control' name='rate' 
 id='rate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>vat</label></div><input type='text' class='form-control' name='vat' 
 id='vat' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>openingbal</label></div><input type='text' class='form-control' name='openingbal' 
 id='openingbal' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>mvm</label></div><input type='text' class='form-control' name='mvm' 
 id='mvm' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
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
<div style='padding:5px;' id='items' /><a href='javascript:void(0)' class='btn btn-primary'id='saveitems'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' singleSelect='true' url='viewitems' pagination='true' id='griditems' method='get' fitColumns='true' style='width:100%' toolbar='#itemstoolbar'>
<thead><tr>
<th field='id' width='50'>id</th>
<th field='itemname' width='50'>Itemname</th>
<th field='barcodename' width='50'>BarCode</th>
<th field='groups' width='50'>Groups</th>
<th field='category' width='50'>Category</th>
<th field='uom' width='50'>uom</th>
<th field='mfgdate' width='50'>Mfgdate</th>
<th field='expirydate' width='50'>Expdate</th>
<th field='rate' width='50'>Rate</th>
<th field='vat' width='50'>Vat</th>
<th field='openingbal' width='50'>openingbal</th>
<th field='mvm' width='50'>Mvm</th>
<th field='isActive' width='50'>isActive</th>

</tr></thead>
</table>
<div id='itemstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newitems' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='edititems' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteitems' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newitems').click(function(){
       $('#dlgitems').dialog('open');
url='/saveitems';
$('#frmitems').form('clear');
});

       //Auto Generated code for Edit Code
 $('#edititems').click(function(){
       
 var row=$('#griditems').datagrid('getSelected');
       $('#dlgitems').dialog('open');
       $('#frmitems').form('load',row);
       url='/edititems/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveitems').click(function(){ 
var id=$('#id').val();
var itemname=$('#itemname').val();
var barcodename=$('#barcodename').val();
var groups=$('#groups').val();
var category=$('#category').val();
var uom=$('#uom').val();
var mfgdate=$('#mfgdate').val();
var expirydate=$('#expirydate').val();
var rate=$('#rate').val();
var vat=$('#vat').val();
var openingbal=$('#openingbal').val();
var mvm=$('#mvm').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'itemname':itemname,'barcodename':barcodename,'groups':groups,'category':category,'uom':uom,'mfgdate':mfgdate,'expirydate':expirydate,'rate':rate,'vat':vat,'openingbal':openingbal,'mvm':mvm,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
$('#griditems').datagrid('reload');
  
$('#dlgitems').dialog('close');
});
//Auto generated code for deleting
$('#deleteitems').click(function(){

    var a=$('#griditems').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#griditems').datagrid('getSelected');
                $.ajax({
                 url:'/destroyitems/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#griditems').datagrid('reload');
            }

});
}
});

});
</script>