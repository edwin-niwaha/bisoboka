
<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>

    <div id="sidebar" style="background-color:#23282d;font-family: 'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;" class="sidebar                  responsive                    ace-save-state">
        <script type="text/javascript">
            try{ace.settings.loadState('sidebar')}catch(e){}
        </script>

      

        <ul class="nav nav-list" >
            <li class="active" >
                <a href="dashboardhome">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> Dashboard </span>
                </a>

                <b class="arrow"></b>
            </li>
            <!--foreach($sidemenu as $menu)-->
            <?php 
            use Illuminate\Support\Facades\DB;
            if(Auth::check()){
                $admin=auth()->user()->isAdmin;
            }else{
                $admin=0;
            }
            

          // echo "<span style='color:white' > strtotime($today)</span>";
            
            //if(strtotime($today)<$expected){
$result =DB::select("SELECT DISTINCT(name),id FROM modules WHERE isActive=1 AND isAdmin=$admin OR isOthers=$admin order by orders asc");
foreach($result as $row)
{     echo "<li class=''>
                <a href='#' class='dropdown-toggle'>
                    <i class='menu-icon fa fa-folder'></i>
                   
                    <span class='menu-text'>
                      $row->name
                    </span>

                    <b class='arrow fa fa-angle-down'></b>
                </a>

                <b class='arrow'></b>
                
                <ul class='submenu'>";
                if(auth::check()){
                if(auth()->user()->admin==1){
                    $result1 = DB::select("SELECT * FROM requirements WHERE isActive=1 AND module_id=$row->id ");
                }else{
                    $result1 = DB::select("SELECT * FROM requirements WHERE isActive=1 AND module_id=$row->id and admin=0 ");
                }
            }else{
                $result1 = DB::select("SELECT * FROM requirements WHERE isActive=1 AND module_id=0 ");  
            }
    foreach($result1 as $row1)
    {
      // if(auth()->user()->admin==1){
     echo "<li class=''>
                            <a href='$row1->Urls'  >
                                <i class='menu-icon fa fa-caret-right'></i>
    
                               $row1->name
                                
                            </a>
    
                            <b class='arrow'></b>
                        </li>";
        //}
    }
    echo "</li></ul>";
}
//}else{
  // echo "<div style='color:red'> Trial Period is done, Please Consider Renewing, Contact Developer on 0757971100 </div>";;
//} ?>
           
        </ul>
       
<!-- /.nav-list -->

        <div  style="background-color:#23282d;"class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>
                    <li class="active">Dashboard</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div><!-- /.nav-search -->
            </div>
