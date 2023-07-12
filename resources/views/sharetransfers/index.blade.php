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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsharetransfers' toolbar='#sharetransfers'><form id='frmsharetransfers'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input type='text' style="height:34px;width:100%" required  class='easyui-datebox form-control' name='date' 
 id='tdate' /></div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>From</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='sharesfrom' 
 id='sharesfrom' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>To</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='sharesto' 
 id='sharesto' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>No of Shares </label></div><input type='text' style="height:34px;width:100%" required class='easyui-numberbox form-control' name='shareno' 
 id='shareno' /></div>
</div>


<div style='padding:5px;' id='sharetransfers' /><a href='javascript:void(0)' class='btn btn-primary'id='savesharetransfers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='Share Transfers' rowNumbers="true" iconCls='fa fa-table' singleSelect='true' url='viewsharetransfers' pagination='true' id='gridsharetransfers' method='get' fitColumns='true' style='width:100%' toolbar='#sharetransferstoolbar'>
<thead><tr>
<th field='id' hidden   width='100'>id</th>
<th field='date'  width='80'>Date</th>
<th field='sharesfrom' hidden width='150'>Shares From</th>
<th field='sharesfromview' width='150'>Shares From</th>
<th field='sharesto' hidden width='150'>Shares To</th>
<th field='sharestoview' width='150'>Shares To</th>
<th field='shareno' width='50'>No of Shares</th>
</tr></thead>
</table>
<div id='sharetransferstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newsharetransfers' iconCls='icon-add' ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editsharetransfers' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletesharetransfers' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> 
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a></div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsharetransfers').click(function(){
       $('#dlgsharetransfers').dialog('open').dialog('setTitle','New sharetransfers');
url='/savesharetransfers';
$('#frmsharetransfers').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsharetransfers').click(function(){
       
 var row=$('#gridsharetransfers').datagrid('getSelected');
       $('#dlgsharetransfers').dialog('open').dialog('setTitle','Edit sharetransfers');

       $('#frmsharetransfers').form('load',row);
       url='/editsharetransfers/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesharetransfers').click(function(){ 
var id=$('#id').val();
var sharesfrom=$('#sharesfrom').val();
var sharesto=$('#sharesto').val();
var shareno=$('#shareno').val();
var tdate=$('#tdate').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$('#frmsharetransfers').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
$.ajax({
url:url,
method:'POST',
data:{'id':id,'tdate':tdate,'sharesfrom':sharesfrom,'sharesto':sharesto,'shareno':shareno,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    if(sharesto==sharesfrom){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Operation Failed.. The Transfering and Recieving Person cannot be the same'});
    }
   else if(data.shares=="notenough"){
       // alert('yes');
        $.messager.alert({title:'Warning',icon:'warning',msg:'Operation Failed.. The Transfering Person doesnot have enough shares, Please reduce and try again.. '});
    }else{
    $.messager.progress('close');
	  $.messager.show({title:'Saving',msg:'Transcation succesfully Saved'});
    $('#gridsharetransfers').datagrid('reload');
    }
}
});
$('#dlgsharetransfers').dialog('close');
                    }
                }
});
  

  

});
//Auto generated code for deleting
$('#deletesharetransfers').click(function(){

    var a=$('#gridsharetransfers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsharetransfers').datagrid('getSelected');
                $.ajax({
                 url:'/destroysharetransfers/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridsharetransfers').datagrid('reload');
                 }
                });
                
            }

});
}
});
$('#tdate').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){

            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d)
            } else {
                return new Date();
            }
        }

    });
    $('#date1').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){

            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d)
            } else {
                return new Date();
            }
        }

    });
    $('#date2').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){

            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d)
            } else {
                return new Date();
            }
        }

    });

    $('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();

$('#gridsharetransfers').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2}

});
});
});
</script>