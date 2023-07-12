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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgitemdetails' toolbar='#itemdetails'>
    <form id='frmitemdetails'  method="POST" enctype="multipart/form-data">
<input type='hidden' class='form-control' name='id' 
 id='id' readonly />
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>AC/ Number</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control'  name='acno' 
 id='acno' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Phone</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control'  name='phone' 
 id='phone' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Identification No</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control'  name='identityno' 
 id='identityno' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Home address</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='address' 
 id='address' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Nature of work</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='natureofwork' 
 id='natureofwork' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Place of Work</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='placeofwork' 
 id='placeofwork' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Next of Kin</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='nextofkin' 
 id='nextofkin' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Contact of Kin</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='nextofkinconc' 
 id='nextofkinconc' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>R/ship Next of Kin</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='rshipkin' 
 id='rshipkin' /></div>
</div>
 <div class='col-lg-6'>
<div class='form-group'>
<div><label>image</label></div>
 <div id="form-attachmen">
   <input  name="image" id="image" style="height:34px;width:100%" class="easyui-filebox form-control" />
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<div><label>Status</label></div>
<div><select style="height:34px;width:100%" id="act" name="act" class="easyui-combobox form-control" >
<option value="1">Yes</option>
<option value="0">No</option>
 </select>
</div>
</div>



</div>
</form>

</div>

</div>



<div style='padding:5px;' id='itemdetails' /><a href='javascript:void(0)' class='btn btn-primary'id='saveitemdetails'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>

<table class='easyui-datagrid' title='' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='griditemdetails'  fitColumns='true' style='width:100%' toolbar='#itemdetailstoolbar'>

</table>
<div id='itemdetailstoolbar'>
 <a href='javascript:void(0)'  id='newitemdetails' class='btn btn-primary' ><i class="fa fa-plus-circle" aria-hidden="true"></i>
New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='edititemdetails'  ><i class="fa fa-pencil" aria-hidden="true"></i>
 Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deleteitemdetails'  ><i class="fa fa-minus-circle" aria-hidden="true"></i>
 Delete</a> 

 <input  type="file"  style="float:right;    border: 1px solid #ccc;
    display: inline-block;
    padding: 5px 10px;
    cursor: pointer;" id="files" />
 <a href='javascript:void(0)' style="float:right;" class='btn btn-primary' id='import'  ><i class="fa fa-upload" aria-hidden="true"></i>
 Import</a>
 
 

 

   





 </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
     $('#import').click(function(){
        var file_data =$('#files').prop('files')[0]; //$('#file')[0].files;
var form_data = new FormData();
form_data.append('files', file_data);
form_data.append('_token', $('input[name=_token]').val());
//$.messager.progress({title:'Importing',msg:"Importing ...."});
//alert(file_data);
$.ajax({
    dataType:'Text',
    cache:false,
url:'importnames',
data:form_data,
contentType:false,
processData:false,
method:'post',
success:function(data){
    $('#griditemdetails').datagrid('reload');
}
});
     });

     //Loading data for the data

     $('#griditemdetails').datagrid({
    title:'Clients Personal Data',
    remoteSort:false,
    singleSelect:true,
    nowrap:false,
    rownumbers:true,
    fitColumns:true,
    url:'viewcustomers',
    striped:true,
    method:'get',
    columns:[[
        {field:'id' ,hidden:true,width:100},
        {field:'name',width:210,title:'Name'},
        {field:'acno',width:110,title:'AC/NO'},
        {field:'phone',title:'Phone',width:80},
        {field:'identityno',title:'Identification No',width:90},
        {field:'address',title:'Address',width:190,sortable:true},
        {field:'natureofwork',title:'Nature of Work',width:100},
        {field:'placeofwork',title:'Place of work',width:100},
        {field:'nextofkin',title:'Next of Kin',width:100},
        {field:'nextofkinconc',title:'Contact Kin',width:80},
        {field:'rshipkin',title:'R/Ship',width:100},
        {field:'isActive',title:'Status', 'hidden':true,width:100},
        {field:'act',title:'Status', width:100},
        {field:'image',title:'Image',width:100,align:'right','hidden':true,sortable:true},
     
       
    ]],
    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<table><tr>' +
							'<td rowspan=2 style="border:0"><img src="images/' + rowData.image + '" style="height:200px;width:200px;"></td>' +
							'<td style="border:0">' +
							'<p>Name: ' + rowData.name + '</p>' +
							'<p>Address: ' + rowData.address + '</p>' +
							'</td>' +
							'</tr></table>';
				}
});
//Images
$('#gridimages').datagrid({
    title:'Images',
    remoteSort:false,
    singleSelect:true,
    nowrap:false,
    rownumbers:true,
    fitColumns:true,
    //url:'viewitemdetails',
   // method:'get',
    columns:[[
        {field:'img',width:100,title:'Image'},
        {field:'id',hidden:true},
      
       
    ]],
    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<table><tr>' +
							'<td rowspan=2 style="border:0"><img src="images/' + rowData.img + '" style="height:70px;width:70px;"></td>' +
							'<td style="border:0">' +
							'<p>Name: ' + rowData.name + '</p>' +
							
							'</td>' +
							'</tr></table>';
				}
});
//Auto Generated code for New Entry Code
    
   $('#newitemdetails').click(function(){
       $('#dlgitemdetails').dialog('open').dialog('setTitle','New Client');
url='/savecustomers';
$('#frmitemdetails').form('clear');
});

       //Auto Generated code for Edit Code
       $('#edititemdetails').click(function(){
       
       var row=$('#griditemdetails').datagrid('getSelected');
       url='/editcustomers/'+row.id;
             $('#dlgitemdetails').dialog('open').dialog('setTitle','Edit itemdetails');
      
             $('#frmitemdetails').form('load',row);
             
             
             
             });
//Auto Generated Code for Saving

//Auto generated code for deleting
$('#deleteitemdetails').click(function(){

var a=$('#griditemdetails').datagrid('getSelected');
if(a==null){
    $.messager.alert('Delete','Please select a row to delete');
    
}else{
    $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
        if(r){
            var row=$('#griditemdetails').datagrid('getSelected');
            $.ajax({
             url:'/destroycustomers/'+row.id,
             method:'POST',
             data:{'id':row.id,'_token':$('input[name=_token]').val()},
              success:function(data){
                 if(data.deleted=="no"){
                     $.messager.alert("Info","No privileges !!. You cannot delete this Record");

                 }else{
                $('#griditemdetails').datagrid('reload');
                 }

             }
            });
            
        }

});
}
});
//Deleteing an image 
$("#saveitemdetails").click(function(e){
 
    var f = $('#image').next().find('.textbox-value');
var name=$('#name').val();
var phone=$('#phone').val();
var address=$('#address').val();
var identityno=$('#identityno').val();
var nextofkin=$('#nextofkin').val();
var nextofkinconc=$('#nextofkinconc').val();
var rshipkin=$('#rshipkin').val();
var placeofwork=$('#placeofwork').val();
var natureofwork=$('#natureofwork').val();
var isActive=$('#act').val();
var acno=$('#acno').val();
var form_data = new FormData();
form_data.append('image', f[0].files[0]);
form_data.append('address', address);
form_data.append('name', name);
form_data.append('phone', phone);
form_data.append('identityno', identityno);
form_data.append('nextofkin', nextofkin);
form_data.append('nextofkinconc', nextofkinconc);
form_data.append('rshipkin', rshipkin);
form_data.append('placeofwork', placeofwork);
form_data.append('natureofwork', natureofwork);
form_data.append('isActive', isActive);
form_data.append('acno',acno);
form_data.append('_token', $('input[name=_token]').val());
$.ajax({
    dataType:'Text',
    cache:false,
url:url,
data:form_data,
contentType:false,
processData:false,
method:'post',
success:function(data){
    $('#griditemdetails').datagrid('reload');
}
});
$('#dlgitemdetails').dialog('close');
        

 });

});



</script>