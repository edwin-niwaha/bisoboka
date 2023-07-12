@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgstocks' toolbar='#stocks'><form id='frmstocks'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>name</label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>description</label></div><input type='text' class='form-control' name='description' 
 id='description' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>category</label></div><select  class='easyui-combobox form-control' style="width:100%;height:34px;"  name='category' id='category' data-options="url:'categoriescombo',valueField:'id',textField:'name',method:'get'" /></select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>subcategory</label></div><select class='form-control easyui-combobox'  style="width:100%;height:32px;" name='subcategory' data-options="url:'subcategoriescombo',valueField:'id',textField:'subname',method:'get'" 
 id='subcategory' ></select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>openingstock</label></div><input type='text' class='form-control' name='openingstock' 
 id='openingstock' /></div>
</div>
<div class='col-lg-6'>
<div class="form-group">
    <div><label>Branch</label></div>
    <div><input class="easyui-combobox" id="branch" name="branch" style="width:100%;33px;"></div>

</div>

</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>limitlevel</label></div><input type='text' class='form-control' name='limitlevel' 
 id='limitlevel' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>buyingrate</label></div><input type='text' class='form-control' name='buyingrate' 
 id='buyingrate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>sellingrate</label></div><input type='text' class='form-control' name='sellingrate' 
 id='sellingrate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>unitofmeasure</label></div><input type='text' class='form-control easyui-combobox'  name='unitofmeasure' 
 id='unitofmeasure' style="width:100%" data-options="url:'uomscombo',valueField:'id',textField:'name',method:'get'"/></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /><input type="text" id="purchaseid" hidden  /></div>
</div>

<div style='padding:5px;' id='stocks' /><a href='javascript:void(0)' class='btn btn-primary'id='savestocks'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid'  rownumbers="true" title='stocks' iconCls='fa fa-table' singleSelect='true' url='viewstocksbranch' pagination='true' id='gridstocks' method='get' fitColumns='true' style='width:100%' toolbar='#stockstoolbar'>
<thead><tr>
<th field='id' width='35' hidden>id</th>
<th field='name' width='100'>Name</th>
<th field='description' width='100'>Description</th>
<th field='category' width='100'>Category</th>
<th field='subcategory' width='100'>Subcategory</th>
<th field='openingstock' width='100'>Available</th>
<th field='limitlevel' width='70'>limitlevel</th>
<th field='buyingrate' width='100'>Buyingrate</th>
<th field='sellingrate' width='100'>Sellingrate</th>
<th field='unitofmeasure' width='100'>Unitofmeasure</th>
<th field='isActive' width='60'>isActive</th>
<!--<th field='branch_id' width='60'>isActive</th>-->
</tr></thead>
</table>
<div id='stockstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newstocks' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editstocks' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletestocks' iconCls='icon-remove' > Delete</a>
Branch&nbsp;<input class="easyui-combobox" id='branch1' class='branch1' /> 
<a href="javascript:void(0)" class="easyui-linkbutton" id="search"
><i class="fa fa-search"></i> Find</a><form id="formupload" method="POST" enctype="multipart/form-data">
<!--<div id="form-attachments">-->
    <input id="file" style="display:inline;" type="file" name= data-options="prompt:'Choose a file...'" style="width:30%">
    <a href="javascript:void(0)" id="import" class="easyui-linkbutton"><i class="fa fa-file"></i>Import</a>

</form>
<!--</div>-->
</div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){

//Auto Generated code for New Entry Code
$('#branch1').combobox({
url:'combobranches',
method:'get',
valueField:'id',
textField:'branchname',
onLoadSuccess:function(){
var data=$(this).combobox('getData');
for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						$('#branch1').combobox('select', data[i].id);
					
					}
}

}

});

  
   $('#newstocks').click(function(){
    var url2 = "maxnumber";

$.getJSON(url2, function (data) {
	$.each(data, function (index, value) {
	  
	$('#purchaseid').val(value.id);
	});
});
       $('#dlgstocks').dialog('open').dialog('setTitle','New stocks');
       $('#branch').combobox({
url:'combobranches',
method:'get',
valueField:'id',
textField:'branchname',
onLoadSuccess:function(){
var data=$(this).combobox('getData');
for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						$('#branch').combobox('select', data[i].id);
					
					}
}

}

});
url='/savestocks';
$('#frmstocks').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editstocks').click(function(){
       
 var row=$('#gridstocks').datagrid('getSelected');
       $('#dlgstocks').dialog('open').dialog('setTitle','Edit stocks');

       $('#frmstocks').form('load',row);
       url='/editstocks/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savestocks').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var description=$('#description').val();
var category=$('#category').val();
var subcategory=$('#subcategory').val();
var branch_id=$('#branch').val();
var openingstock=$('#openingstock').val();
var limitlevel=$('#limitlevel').val();
var buyingrate=$('#buyingrate').val();
var sellingrate=$('#sellingrate').val();
var unitofmeasure=$('#unitofmeasure').val();
var isActive=$('#isActive').val();
var purchaseid=$('#purchaseid').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'description':description,'category':category,'branch_id':branch_id,'subcategory':subcategory,'openingstock':openingstock,'limitlevel':limitlevel,'buyingrate':buyingrate,'sellingrate':sellingrate,'unitofmeasure':unitofmeasure,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'purchaseno':purchaseid,'_token':$('input[name=_token]').val()}
});
  
$('#dlgstocks').dialog('close');
  
$('#gridstocks').datagrid('reload');
});
//Auto generated code for deleting
$('#deletestocks').click(function(){

    var a=$('#gridstocks').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridstocks').datagrid('getSelected');
                $.ajax({
                 url:'/destroystocks/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridstocks').datagrid('reload');
            }

});
}
});

//Search Button
$('#search').click(function(){
    var branch=$('#branch1').val();
    var token=$('input[name=_token]').val();
    $('#gridstocks').datagrid({
method:'get',
queryParams:{branch_id:branch}

    });


});

//Importing stock items
$('#import').click(function(){
    //var reg = /(.*?)\.(jpg|bmp|jpeg|png)$/;
       
var file_data =$('#file').prop('files')[0]; //$('#file')[0].files;
var form_data = new FormData();
form_data.append('files', file_data);
form_data.append('_token', $('input[name=_token]').val());
$.messager.progress({title:'Importing',msg:"Importing ...."});
$.ajax({
         url:'importstock',
   type: "POST",
   data:  form_data,
   contentType: false,
         cache: false,
   processData:false, 
   success:function(data){

      $.messager.progress('close');
	  $.messager.show({title:'Info',msg:'Import Complete!!!'});
      $('#gridstocks').datagrid('reload');
   }         
    });
    

    
//}  
});

});
</script>