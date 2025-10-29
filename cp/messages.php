<?php
  include "header.php";

    require "../connection.php";
    
    if( !isset($_SESSION['user']) or  $_SESSION['Role']!="admin")
   {   header("Location:../login.php");
      exit();
    }

    if(isset($_GET['action'],$_GET['id']) and intval($_GET['id'])>0)
    {
        if($_GET['action']=='delete')
        {
            $sql="delete from messages where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));

            if($q->rowcount()==1)
            echo "<script>alert('DELETED SUCCESSFULLAY') </script>";
            else
            echo "<script>alert('coudent delete') </script>";
        }
        header("Location: sales.php");
        exit();
    }

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa-solid fa-comments"></i>MESSAGES</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
   
    <div class="row">
        <div class="col-xs-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fa-regular fa-comments"></i> MESSAGES
                    <div class="pull-right">
                        
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" >
                                    <thead >
                                        <tr >
                                            <th>#</th>
                                            <th>Name</th>
                                            <th >PHONE</th>
                                            <th >EMAIL</th>
                                            <th colspan=''>MESSAGE</th>
                                            <th >CREATE TIME</th>
                                            <th colspan='' style='text-align:center'>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                     $sql = "select * from messages order by ID desc";
                                     $q = $con->prepare($sql);
                                     $q->execute();
                                     if($q->rowcount())
                                     {
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $name = $row["NAME"];
                                            $phone = $row["PHONE"];
                                            $email = $row["EMAIL"];
                                            $message = $row["MESSAGE"];
                                            $time = $row["CREATE_AT"];
                                            

                                            echo "<tr>";
                                                echo "<td>$id</td>";
                                                echo "<td>$name</td>";
                                                echo "<td>$phone</td>";
                                                echo "<td>$email</td>";
                                                echo "<td>$message</td>";
                                                echo "<td>$time</td>";
                                                echo "<td>";
                                                echo "<a href='?action=delete&id=$id' class='btn btn-danger'  title='delete' onclick=\"return confirm('are you sure')\" >delete </a> ";
                                                echo"</td>";
                                            echo "</tr>";
                                        }
                                     }
                                    
                                    ?>   


                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
          
        </div>
        <!-- /.col-lg-8 -->
       
    </div>
    <!-- /.row -->
</div>         
</div>

<script src="../js/jquery-3.1.0.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
<script>
$(function () {
    'use strict';
$('#deletej').click(function(){
    return confirm('Are You Sure!!!');
});
});
</script>
</body>
</html>