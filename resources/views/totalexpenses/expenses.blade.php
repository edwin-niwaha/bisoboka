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
<table class='easyui-datagrid' title='Expenses' rowNumbers="true" iconCls='fa fa-table' singleSelect='true' showFooter="true" url='' pagination='true' id='gridpaynow' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
    <thead><tr>
    <th field='transdates' width='80'>TranscationDate</th>
    <th field='id' width='80'>Ref No.</th>
    <th field='narration' width='150'>Narration</th>
    <th field='amount' width='70'>Amount</th>

    </tr></thead>
    </table>
    <div id='customerstoolbar'>
    &nbsp;<a href="javascript:void()" class="btn btn-primary" id="del"><i class="fa fa-trash"></i> Del</a>
        <label>Date</label>
        <input  class='easyui-datebox'  required  id="date1" name="date1"/>To
        <input  class='easyui-datebox' id="date2" name="date2"  required/>
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="find" name="find"><i class="fa fa-search"></i> Find</a>
         
         &nbsp;<a href="javascript:void()" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Preview</a>

    </div>
 


                       
                           
        </form>
        <div id='toolbaredit'>
      
            </div>

        </div>
        {{csrf_field()}}
    <script>
         function formatAction(value,row,index){
    if (row.editing){
        var s = '<a href="#" onclick="saverow(this)">Save</a> ';
        var c = '<a href="#" onclick="cancelrow(this)">Cancel</a>';
        return s+c;
    } else {
        var e = '<a href="#" onclick="editrow(this)">Edit</a> ';
        var d = '<a href="#" onclick="deleterow(this)">Delete</a>';
        return e+d;
    }
}



$.extend($.fn.datagrid.defaults.editors, {
    numberspinner: {
        init: function(container, options){
            var input = $('<input type="text">').appendTo(container);
            return input.numberspinner(options);
        },
        destroy: function(target){
            $(target).numberspinner('destroy');
        },
        getValue: function(target){
            return $(target).numberspinner('getValue');
        },
        setValue: function(target, value){
            $(target).numberspinner('setValue',value);
        },
        resize: function(target, width){
            $(target).numberspinner('resize',width);
        }
    }
});
$(function(){
    $('#tt').datagrid({
        onBeforeEdit:function(index,row){
            $(this).datagrid('updateRow', {index:index,row:{editing:true}})
        },
        onAfterEdit:function(index,row){
            $(this).datagrid('updateRow', {index:index,row:{editing:false}})
        },
        onCancelEdit:function(index,row){
            $(this).datagrid('updateRow', {index:index,row:{editing:false}})
        }
    });
});
function getRowIndex(target){
    var tr = $(target).closest('tr.datagrid-row');
    return parseInt(tr.attr('datagrid-row-index'));
}
function editrow(target){
    $('#tt').datagrid('beginEdit', getRowIndex(target));
}
function deleterow(target){
    var row=$('#tt').datagrid('getSelected');
    if(row==null){

        $.messager.alert("Message","Please select a record to delete");
    }else{
    $.messager.confirm('Confirm','Are you sure?',function(r){
        if (r){
           
            $('#tt').datagrid('deleteRow', getRowIndex(target));
            $.ajax({
                url:'deletesales',
                method:'post',
                data:{'id':row.id,'_token':$('input[name=_token]').val()},



            });
           
            
        }
    });
}
}
function saverow(target){
    var $row=$(target).closest('tr');
    var rows = $('#tt').datagrid('getRows');
$.each(rows, function(i, row) {
  $('#tt').datagrid('endEdit', i);
  
  var url = row.isNewRecord ? 'test.php?savetest=true' : 'editxpenses';
  $.ajax(url, {
      type:'POST',
      dataType: 'json',
      data:row
  });
});
}
function cancelrow(target){
    $('#tt').datagrid('cancelEdit', getRowIndex(target));
}
$('#tt').edatagrid({
    onClickRow:function(rowIndex){
        if (lastIndex != rowIndex){
            $(this).datagrid('endEdit', lastIndex);
            $(this).datagrid('beginEdit', rowIndex);
        }
        lastIndex = rowIndex;
    },
    
    onBeginEdit:function(rowIndex){
        var editors = $('#tt').datagrid('getEditors', rowIndex);
        var token = $(editors[0].target);
       token.textbox('setValue',$('input[name=_token]').val());
       var accountcode2=$(editors[4].target);
       if(isNaN(parseInt($('#name').val()))){
         accountcode2.numberbox('setValue',$('#code').val()); 
         }else{
         accountcode2.numberbox('setValue',$('#name').val());
        
         }
     

    
           
       

        
    }

    });


var finend = null;
 var finstart = null;

$(document).ready(function(){
   
  
    
    $.ajax({
        async:false,
        url: "activeyear",
        method:"get",
        dataType:"json",
        success: function(data){
            $.each(data, function(index, element) {
            finend=element.endperiod;
            finstart=element.startperiod;

            });
        }
    });
   
//load Table Values
$('#gridpaynow').datagrid({
method:'get',
url:'allexpenses?end='+finend+'&start='+finstart,

});

       //Seting default Branch

          /* $('#branche').combobox({
url:'combobranches',
method:'get',
valueField:'id',
textField:'branchname',
onLoadSuccess:function(){
var data=$(this).combobox('getData');
for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						//$('#branche').combobox('select', data[i].id);
                      //  $('#product').combobox('reload','/stockscombo/'+data[i].id);
					
					}
}

},
onSelect:function(record){

    $('#product').combobox('reload','/stockscombo/'+record.id);
}
});*/
   $('#paynow').click(function(){
    $('#dlgpaynow').dialog('open');
    var row=$('#gridpaynow').datagrid('getSelected');
    $('#frmpaynow').form('load',row);


   });

          $('#edit').click(function(){
 
 var row=$('#gridpaynow').datagrid('getSelected');
 $('#dlgtrans').dialog('open').dialog('setTitle','Edit Expenses Transcations');
  url="/viewexpenses/"+row.id;
  edittranscations();
 $('#edittrans').form('load',row);  

 

 });

$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
$('#gridpaynow').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,}

});

});

$('#del').click(function(){

var a=$('#gridpaynow').datagrid('getSelected');
if(a==null){
    $.messager.alert('Delete','Please select a row to delete');
    
}else{
    $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
        if(r){
            var row=$('#gridpaynow').datagrid('getSelected');
            $.ajax({
             url:'/destroysincome1/'+row.id,
             method:'POST',
             data:{'id':row.id,'_token':$('input[name=_token]').val()},
             success:function(){
                $('#gridpaynow').datagrid('reload');
             }
            });
            
        }

});
}
});

     });

     $('#print').click(function(){
         var date1=$('#date1').val();
         var date2=$('#date2').val();
         if(date1=='' && date2==''){
            window.open('/expensepreview/today/0','_newtab');
         }else if (date1!=''&& date2==''){
            window.open('/expensepreview/'+date1+'/0','_newtab');
         } 
         else if(date1!='' && date2!=''){
            window.open('/expensepreview/'+date1+'/'+date2,'_newtab');
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
     function edittranscations(){

$('#tt').datagrid({
url:url,
onSuccess:function(){

    $('#tt').datagrid('reload');
}


});

    
}
        </script>