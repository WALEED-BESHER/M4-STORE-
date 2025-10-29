<?php
   include "header.php";
    $ice= $_SESSION['user_id'];
    $checkingsql = "select * from users where ID=:id";
    $chg = $con->prepare($checkingsql);
    $chg->bindValue(':id' , $ice);
    $chg->execute();
    $chs=$chg->fetch(); 
    if(!isset($_SESSION['user']) or  $chs["MASTER"]!=1)
    {   header("Location: dashboard.php");
       exit();
    }

    if(isset($_GET['action'],$_GET['id']) and intval($_GET['id'])>0)
    {
        if($_GET['action']=='delete')
        {
            $id= $_GET['id'];
            $sql = "select * from users where ID=:id";
            $q = $con->prepare($sql);
            $q ->bindValue(':id' , $id);
            $q->execute();
            $ch=$q->fetch();
            if($ch["MASTER"]== 1)
            {
                echo "<h3 class='alert alert-danger' style='text-align: center;'> YOU CANT DELETE THIS ADMIN </h3>";
            }else
            {
                $sql="update users set DELIVERY = 0 where ID=:id";
                $q= $con->prepare($sql);
                $q->execute(array("id"=>$_GET['id']));

                if($q->rowcount()==1)
                echo "<script>alert('DELETED SUCCESSFULLAY') </script>";
                else
                echo "<script>alert('coudent delete') </script>";
            }   
        }
        header("Location: delivery_users.php");
        exit();
    }

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fas fa-truck"></i> DELIVERY USERS PAGE</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
   
    <div class="row">
        <div class="col-xs-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fa-solid fa-motorcycle"></i>  DELIVERY USERS
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
                                            <th>FIRST NAME</th>
                                            <th>LAST NAME</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th >Phone number</th>
                                            <th colspan='' style='text-align:center'>ROLE</th>
                                            <th colspan='' style='text-align:center'>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                     $sql = "select * from users where DELIVERY=1 and Role!='admin' order by ID desc";
                                     $q = $con->prepare($sql);
                                     $q->execute();
                                     if($q->rowcount())
                                     {
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $fname = $row["FIRST_NAME"];
                                            $lname = $row["LAST_NAME"];
                                            $name = $row["USERNAME"];
                                            $email = $row["EMAIL"];
                                            $phone_num = $row["PHONE"];
                                            $role = $row["Role"];

                                            echo "<tr>";
                                                echo "<td>$id</td>";
                                                echo "<td>$fname</td>";
                                                echo "<td>$lname</td>";
                                                echo "<td>$name</td>";
                                                echo "<td>$email</td>";
                                                echo "<td>$phone_num</td>";
                                                echo "<td>$role</td>";

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