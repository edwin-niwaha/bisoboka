@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgitemdetails' toolbar='#itemdetails'>
    <form id='frmitemdetails'  method="POST" enctype="multipart/form-data">
<input type='hidden' class='form-control' name='id' 
 id='id' readonly />
<div class='col-lg-6'>
<div class='form-group'>
<div><label>name</label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>phone</label></div><input type="text" class="form-control"  name='phone' 
 id='phone' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>address</label></div><input type='text' class='form-control' name='address' 
 id='address' /></div>
</div>
 <div class='col-lg-6'>
<div class='form-group'>
<div><label>image</label></div>
 <div id="form-attachmen">
   <input name="image"  id="image" class="easyui-filebox form-control" />
</div>




</div>
</form>

</div>

</div>



<div style='padding:5px;' id='itemdetails' /><a href='javascript:void(0)' class='btn btn-primary'id='saveitemdetails'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>

<table class='easyui-datagrid' title='itemdetails' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='griditemdetails'  fitColumns='true' style='width:100%' toolbar='#itemdetailstoolbar'>

</table>
<div id='itemdetailstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newitemdetails' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='edititemdetails' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteitemdetails' iconCls='icon-remove' > Delete</a> 
<a href='javascript:void(0)' class='easyui-linkbutton' id='addimages' iconCls='icon-add' >Add Images</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){

     //Loading data for the data

     $('#griditemdetails').datagrid({
    title:'Clients Personal Data',
    remoteSort:false,
    singleSelect:true,
    nowrap:false,
    rownumbers:true,
    fitColumns:true,
    url:'viewcustomers',
    method:'get',
    columns:[[
        {field:'id' ,hidden:true,width:100},
        {field:'name',width:100,title:'Name'},
        {field:'phone',title:'Phone',width:100},
        {field:'address',title:'Address',width:100,sortable:true},
        {field:'image',title:'Image',width:100,align:'right',sortable:true},
     
       
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
                 url:'/destroyitemdetails/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(data){
                    $('#griditemdetails').datagrid('reload');
                 }
                });
                
            }

});
}
});
//Deleteing an image 
$("#saveitemdetails").click(function(e){
    var oFile = document.getElementById("image").files[0];
    
   
    if (oFile.size > 2097152) // 2 mb for bytes.
            {
                $.messager.alert("info","Image size must under 2mb!");
                
            }else{
                 
  var file_data = $('#image').prop('files')[0];
var form_data = new FormData();
form_data.append('image', file_data);
form_data.append('_token', $('input[name=_token]').val());
form_data.append('name', $('#name').val());
form_data.append('address', $('#address').val());
form_data.append('phone', $('#phone').val());
form_data.append('id', $('#id').val());


  $.ajax({
         url:url,
   type: "POST",
   data:  form_data,
   contentType: false,
         cache: false,
   processData:false,  
   success:function(data){
    $('#griditemdetails').datagrid('reload');
   }        
    });
    $('#dlgitemdetails').dialog('close');

            }

 });

});



</script>