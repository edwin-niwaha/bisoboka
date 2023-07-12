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

<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmembership' toolbar='#memberships'>
<form id="frmmemberships" >
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Membership Fee</label></div><input  style="height:34px;width:100%" type='text' class='easyui-textbox form-control' name='mfees' 
 id='mfees' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Checking Account</label></div><input  style="height:34px;width:100%" class='easyui-combobox form-control' name='disbursingac' 
 id='checkingac' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Status</label></div>
<select class='easyui-combobox form-control' id="status" style="width:100%;height:36px;">
<option value='1'>Yes</option>
<option value='0'>No</option>
</select>

</div>
</div>
</form>
</div>


<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgcompanys' toolbar='#companys'><form id='frmcompanys' method="POST" enctype="multipart/form-data">
<!--<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>-->
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>location</label></div><input type='text' class='form-control' name='location' 
 id='location' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Boxno</label></div><input type='text' class='form-control' name='boxno' 
 id='boxno' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Phone</label></div><input type='text' class='form-control' name='phone' 
 id='phone' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Email</label></div><input type='text' class='form-control' name='email' 
 id='email' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Company Logo</label></div>
 <div id="form-attachmen">
   <input  name="logo" id="logo" style="height:34px;width:100%" class="easyui-filebox form-control" />
</div>
</div>
</div>
<div style='padding:5px;' id='companys' /><a href='javascript:void(0)' class='btn btn-primary'id='savecompanys'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' rowNumbers="true" title='My Company' iconCls='fa fa-table' singleSelect='true' url='' pagination='true' id='gridcompanys'  fitColumns='true' style='width:100%' toolbar='#companystoolbar'>
<!--<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='name' width='100'>Company Name</th>
<th field='location' width='100'>Location</th>
<th field='boxno' width='100'>Boxno</th>
<th field='phone' width='100'>Phone</th>
<th field='email' width='100'>Email</th>
</tr></thead>-->
</table>
<div id='companystoolbar'><a href='javascript:void(0)' class='btn btn-primary' id='editcompanys' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;
<a href='javascript:void(0)' class='btn btn-primary' id='membershipbtn' iconCls='icon-edit' ><i class="fa fa-user-plus" aria-hidden="true"></i> Membership</a>
<input  type="file"  style="float:right;    border: 1px solid #ccc;
    display: inline-block;
    padding: 5px 10px;
    cursor: pointer;" id="files" />
 <a href='javascript:void(0)' style="float:right;" class='btn btn-primary' id='import'  ><i class="fa fa-upload" aria-hidden="true"></i>
 Upload </a>

<!-- <a href='javascript:void(0)' class='btn btn-primary' id='newcompanys' iconCls='icon-add' > <i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>

<a href='javascript:void(0)' class='btn btn-primary' id='deletecompanys' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a>--> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
    $('#gridcompanys').datagrid({
    title:'My Company',
    remoteSort:false,
    singleSelect:true,
    nowrap:false,
    rownumbers:true,
    fitColumns:true,
    url:'viewcompanys',
    striped:true,
    method:'get',
    columns:[[
        {field:'id' ,hidden:true,width:100},
        {field:'name',width:210,title:'Company Name'},
        {field:'location',width:110,title:'Location'},
        {field:'boxno',title:'Box No',width:80},
        {field:'phone',title:'Telephone',width:90},
        {field:'email',title:'Email Address',width:190,sortable:true},
     
       
    ]],
    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<table><tr>' +
							'<td rowspan=2 style="border:0"><img src="images/' + rowData.logo + '" style="height:200px;width:200px;"></td>' +
							'<td style="border:0">' +
							 
							'</td>' +
							'</tr></table>';
				}
});


//Auto Generated code for New Entry Code
    
   $('#newcompanys').click(function(){
       $('#dlgcompanys').dialog('open').dialog('setTitle','New companys');
url='/savecompanys';
$('#frmcompanys').form('clear');
});

$('#membershipbtn').click(function(){
$('#dlgmembership').dialog('open').dialog('setTitle','Membership Configuration');

});
       //Auto Generated code for Edit Code
 $('#editcompanys').click(function(){
       
 var row=$('#gridcompanys').datagrid('getSelected');
       $('#dlgcompanys').dialog('open').dialog('setTitle','Edit companys');

       $('#frmcompanys').form('load',row);
       url='/editcompanys/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savecompanys').click(function(){ 
var f = $('#logo').next().find('.textbox-value');
var id=$('#id').val();
var name=$('#name').val();
var location=$('#location').val();
var boxno=$('#boxno').val();
var phone=$('#phone').val();
var email=$('#email').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var form_data = new FormData();
form_data.append('logo', f[0].files[0]);
form_data.append('name', name);
form_data.append('location', location);
form_data.append('phone', phone);
form_data.append('email', email);
form_data.append('boxno', boxno);
form_data.append('_token', $('input[name=_token]').val());
$.ajax({
    dataType:'Text',
    cache:false,
url:url,
data:form_data,
contentType:false,
processData:false,
method:'post',
success:function(){
    $('#gridcompanys').datagrid('reload');
}
});

  
$('#dlgcompanys').dialog('close');
  

});
//Auto generated code for deleting
$('#deletecompanys').click(function(){

    var a=$('#gridcompanys').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridcompanys').datagrid('getSelected');
                $.ajax({
                 url:'/destroycompanys/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridcompanys').datagrid('reload');
            }

});
}
});

});
</script>