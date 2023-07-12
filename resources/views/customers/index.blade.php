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
<div class='easyui-dialog' style='width:80%;padding:5px;' closed='true' id='dlgcustomers' toolbar='#customers'><form id='frmcustomers'>
<center><h4> Personal Information</h4> </center>
<hr>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Name </label></div><input type='text' required class='easyui-textbox form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Sex</label></div><select  class='easyui-combobox form-control' name='sex' 
 id='sex'>
 <option value='F'>Female </option>
 <option value='M'>Male</option>
 <option value='O'>Other</option>
 </select></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Account Number</label></div><input  required type='text' class='easyui-textbox form-control' name='acno' 
 id='acno' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Date of Birth</label></div><input type='text' class='easyui-datebox form-control' name='dob' 
 id='dob' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>address</label></div><input type='text' class='easyui-textbox form-control' name='address' 
 id='address' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Telephone 1</label></div><input type='text' mask="(999) 999-999999" class='easyui-maskedbox form-control' name='telephone1' 
 id='telephone1' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Telephone 2</label></div><input type='text' mask="(999) 999-999999" class='easyui-maskedbox form-control' name='telephone2' 
 id='telephone2' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Email Address</label></div><input type='text' class='easyui-textbox form-control' name='email' 
 id='email' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>District</label></div><input type='text' class='easyui-textbox form-control' name='district' 
 id='district' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Subcounty</label></div><input type='text' class='easyui-textbox form-control' name='subcounty' 
 id='subcounty' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Parish</label></div><input type='text' class='easyui-textbox form-control' name='parish' 
 id='parish' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Village</label></div><input type='text' class='easyui-textbox form-control' name='village' 
 id='village' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Religion</label></div><input type='text' class='easyui-textbox form-control' name='religion' 
 id='religion' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Church</label></div><input type='text' class='easyui-textbox form-control' name='church' 
 id='church' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Education</label></div><input type='text' class='easyui-textbox form-control' name='education' 
 id='education' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Occupation</label></div><input type='text' class='easyui-textbox form-control' name='occupation' 
 id='occupation' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Marital Status</label></div><input type='text' class='easyui-combobox form-control' data-options="url:'combomaritals',method:'get',valueField:'id',textField:'name'" name='marital' 
 id='marital' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Number of children</label></div><input style="height:80px" type='text' multiline="true" class='easyui-textbox form-control' name='nochildren' 
 id='nochildren' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Name of children</label></div><input type='text' style="height:80px" multiline="true" class='easyui-textbox form-control' name='namechildren' 
 id='namechildren' /></div>
</div>
<!--<center><h4>Next of Kin </h4></center>-->

<div class='col-lg-4'>
<div class='form-group'>
<div><label>Next of Kin Name</label></div><input type='text' class='easyui-textbox form-control' name='kinname' 
 id='kinname' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Sex</label></div><select  class='easyui-combobox form-control' name='kinsex' 
 id='kinsex'>
 <option value='F'>Female </option>
 <option value='M'>Male</option>
 <option value='O'>Other</option>
 </select></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Date of Birth </label></div><input type='text' class='easyui-datebox form-control' name='kindob' 
 id='kindob' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Occupation</label></div><input type='text' class='easyui-textbox form-control' name='kinoccupation' 
 id='kinoccupation' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Contact Address </label></div><input type='text' class='easyui-textbox form-control' name='contactadd' 
 id='contactadd' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Email </label></div><input type='text' class='easyui-textbox form-control' name='kinemail' 
 id='kinemail' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Telephone 1</label></div><input type='text' mask="(999) 999-999999" class='easyui-maskedbox form-control' name='kintelephone1' 
 id='kintelephone1' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Telephone 2</label></div><input type='text' mask="(999) 999-999999" class='easyui-maskedbox form-control' name='kintelephone2' 
 id='kintelephone2' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Witness Name</label></div><input type='text' class='easyui-textbox form-control' name='witnessname' 
 id='witnessname' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Witness Date</label></div><input type='text' class='easyui-datebox form-control' name='witnessdate' 
 id='witnessdate' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Registration Date</label></div><input type='text' class='easyui-datebox form-control' name='registrationdate' 
 id='registrationdate' /></div>
</div>




</div>
<div style='padding:5px;' id='customers' /><a href='javascript:void(0)' class='btn btn-primary'id='savecustomers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid'  striped='true' rowNumbers="true" title='Clients' iconCls='fa fa-table' singleSelect='true' url='viewcustomers' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='name' width='260'>Name</th>
<th field='sex' width='40'>sex</th>
<th field='acno' width='100'>Account #</th>
<th field='dob' hidden width='100'>Date of Birth</th>
<th field='address' width='100'>Address</th>
<th field='telephone1' width='120'>Telephone 1</th>
<th field='telephone2' hidden width='100'>Telephone 2</th>
<th field='email' width='180'>Email</th>
<th field='district' width='100'>District</th>
<th field='subcounty' width='100'>Subcounty</th>
<th field='parish' width='100'>Parish</th>
<th field='village' width='100'>Village</th>
<th field='religion' width='100'>Religion</th>
<th field='church' width='100'>Church</th>
<th field='education' width='100'>Education</th>
<th field='occupation' width='100'>Occupation</th>
<th field='marital' hidden width='100'>Marital</th>
<th field='nochildren' hidden width='100'>nochildren</th>
<th field='namechildren' hidden width='100'>namechildren</th>
<th field='kinname' hidden  width='100'>kinname</th>
<th field='kinsex' hidden  width='100'>kinsex</th>
<th field='kindob'hidden  width='100'>kindob</th>
<th field='kinoccupation' hidden  width='100'>kinoccupation</th>
<th field='contactadd' hidden  width='100'>contactadd</th>
<th field='kinemail' hidden  width='100'>kinemail</th>
<th field='kintelephone1' hidden width='100'>kintelephone1</th>
<th field='kintelephone2' hidden  width='100'>kintelephone2</th>
<th field='witnessname' hidden  width='100'>witnessname</th>
<th field='witnessdate' hidden  width='100'>witnessdate</th>
<th field='registrationdate' hidden  width='100'>registrationdate</th>


</tr></thead>
</table>
<div id='customerstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary ' id='newcustomers'  ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary ' id='editcustomers'  ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary ' id='deletecustomers' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a>
&nbsp;&nbsp;
 <input style="height:34px;width:25%" required class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='findname' 
 id='findname' />&nbsp;
 <a href='javascript:void(0)' class='btn btn-primary ' id='findsearch' ><i class="fa fa-search" aria-hidden="true"></i> Search</a>&nbsp;
 <a href='javascript:void(0)' class='btn btn-primary ' id='print' ><i class="fa fa-print" aria-hidden="true"></i> Preview</a>&nbsp;
<input  type="file"  style="float:right;    border: 1px solid #ccc;
    display: inline-block;
    padding: 5px 10px;
    cursor: pointer;" id="files" />
 <a href='javascript:void(0)' style="float:right;" class='btn btn-primary' id='import'  ><i class="fa fa-upload" aria-hidden="true"></i>
 Import</a>  </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
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
    $('#gridcustomers').datagrid('reload');
}
});
     });
     
   $('#newcustomers').click(function(){
    $.extend($.fn.dialog.methods, {
    mymove: function(jq, newposition){
        return jq.each(function(){
            $(this).dialog('move', newposition);
        });
    }
});


$('#dlgcustomers').dialog('move', {
   left: 280,
   top: 290
});
         
$('#dlgcustomers').dialog('open').dialog('setTitle','New Client');
       //$('#dlgcustomers').dialog('open').dialog('setTitle','New customers').dialog({position:'top'});
url='/savecustomers';
$('#frmcustomers').form('clear');
});
$('#gridcustomers').datagrid({
        pageSize:50,
        pageList:[10,20,30,40,50],


    });
       //Auto Generated code for Edit Code
 $('#editcustomers').click(function(){
       
 var row=$('#gridcustomers').datagrid('getSelected');
      // $('#dlgcustomers').dialog('open').dialog('setTitle','Edit customers');
      $.extend($.fn.dialog.methods, {
    mymove: function(jq, newposition){
        return jq.each(function(){
            $(this).dialog('move', newposition);
        });
    }
});


$('#dlgcustomers').dialog('move', {
   left: 280,
   top: 290
});
         
$('#dlgcustomers').dialog('open').dialog('setTitle','Edit Client');

       $('#frmcustomers').form('load',row);
       url='/editcustomers/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savecustomers').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var sex=$('#sex').val();
var dob=$('#dob').val();
var address=$('#address').val();
var telephone1=$('#telephone1').val();
var telephone2=$('#telephone2').val();
var email=$('#email').val();
var district=$('#district').val();
var subcounty=$('#subcounty').val();
var parish=$('#parish').val();
var village=$('#village').val();
var religion=$('#religion').val();
var church=$('#church').val();
var education=$('#education').val();
var occupation=$('#occupation').val();
var marital=$('#marital').val();
var nochildren=$('#nochildren').val();
var namechildren=$('#namechildren').val();
var kinname=$('#kinname').val();
var kinsex=$('#kinsex').val();
var kindob=$('#kindob').val();
var kinoccupation=$('#kinoccupation').val();
var contactadd=$('#contactadd').val();
var kinemail=$('#kinemail').val();
var kintelephone1=$('#kintelephone1').val();
var kintelephone2=$('#kintelephone2').val();
var witnessname=$('#witnessname').val();
var witnessdate=$('#witnessdate').val();
var registrationdate=$('#registrationdate').val();
var acno=$('#acno').val();
var branchnumber=$('#branchnumber').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();

$('#frmcustomers').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){

$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'sex':sex,'dob':dob,'address':address,'telephone1':telephone1,'telephone2':telephone2,'email':email,'district':district,'subcounty':subcounty,'parish':parish,'village':village,'religion':religion,'church':church,'education':education,'occupation':occupation,'marital':marital,'nochildren':nochildren,'namechildren':namechildren,'kinname':kinname,'kinsex':kinsex,'kindob':kindob,'kinoccupation':kinoccupation,'contactadd':contactadd,'kinemail':kinemail,'kintelephone1':kintelephone1,'kintelephone2':kintelephone2,'witnessname':witnessname,'witnessdate':witnessdate,'registrationdate':registrationdate,'acno':acno,'branchnumber':branchnumber,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.checkacno=='exists'){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Operation Failed, Account Number is already in Use. Please it change and try again...'});
    }else{
    $('#gridcustomers').datagrid('reload');
    $.messager.progress('close');
	  $.messager.show({title:'Saving',msg:'Transcation succesfully Saved'});
    }
}
});
$('#dlgcustomers').dialog('close');
}
}
});
  

  

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
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(data){
                    if(data.isdelete=='No'){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Operation Failed,This Account Number or Client  has data associated with it. '});
    }else{
        $('#gridcustomers').datagrid('reload');
    }
                 }
                });
                
            }

});
}
});
$('#dob').datebox({
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
    $('#kindob').datebox({
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
    $('#witnessdate').datebox({
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
    $('#registrationdate').datebox({
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
    $('#print').click(function(){
window.open('/customerPreview/','_newtab');
         

     });
    $('#findsearch').click(function(){
    var findname=$('#findname').val();
$('#gridcustomers').datagrid({
method:'get',
queryParams:{findname:findname}

});

});
});
</script>