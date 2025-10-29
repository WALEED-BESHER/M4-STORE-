<?php
 include "header.php";
  require "../connection.php";
    $ice= $_SESSION['user_id'];
    $checkingsql = "select * from users where ID=:id";
    $chg = $con->prepare($checkingsql);
    $chg->bindValue(':id' , $ice);
    $chg->execute();
    $chs=$chg->fetch(); 
    if( !isset($_SESSION['user']) or  $chs["MASTER"]!=1)
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
                echo "<h3 class='alert alert-danger'> YOU CANT DELETE THIS ADMIN </h3>";
            }else
            {
                $sql="delete from users where ID=:id";
                $q= $con->prepare($sql);
                $q->execute(array("id"=>$_GET['id']));

                if($q->rowcount()==1)
                echo "<script>alert('DELETED SUCCESSFULLAY') </script>";
                else
                echo "<script>alert('coudent delete') </script>";
            }

            
        }
        elseif($_GET['action']=='inactive')
        {
            $id= $_GET['id'];
            $sql = "select * from users where ID=:id";
            $q = $con->prepare($sql);
            $q ->bindValue(':id' , $id);
            $q->execute();
            $ch=$q->fetch();
            if($ch["MASTER"]== 1)
            {
                echo "<h3 class='alert alert-danger'> YOU CANT INACTIVE THIS ADMIN </h3>";
            }else
            {
                $sql="update users set ACTIVE=0 where ID=:id";
                $q= $con->prepare($sql);
                $q->execute(array("id"=>$_GET['id']));
            
                if($q->rowcount()==1)
                echo "<h3 class='alert alert-danger'> INACTIVE SUCCESSFULLAY </h3>";
                else
                echo "<script>alert('INACTIVE FAILED') </script>";
            }
            
        }
        elseif($_GET['action']=='active')
        {
            $sql="update users set ACTIVE=1 where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));
        
            if($q->rowcount()==1)
            echo "<h3 class='alert alert-success'> ACTIVE SUCCESSFULLAY </h3>";
            else
            echo "<script>alert('coudent activeated') </script>";
        }
        elseif($_GET['action']=='admin')
        {
            $sql="update users set Role='admin',DELIVERY=1 where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));
        
            if($q->rowcount()==1)
            echo "<h3 class='alert alert-success'>  ONE ADMIN ADD SUCCESSFULLAY </h3>";
            else
            echo "<script>alert('coudent add admin') </script>";
        }
        elseif($_GET['action']=='tadmin')
        {
            $id= $_GET['id'];
            $sql = "select * from users where ID=:id";
            $q = $con->prepare($sql);
            $q ->bindValue(':id' , $id);
            $q->execute();
            $ch=$q->fetch();
            if($ch["MASTER"]== 1)
            {
                echo "<h3 class='alert alert-danger'> YOU CANT EDIT THIS ADMIN </h3>";
            }
            else{
                $sql="update users set Role='user',DELIVERY=0 where ID=:id";
                $q= $con->prepare($sql);
                $q->execute(array("id"=>$_GET['id']));
            
                if($q->rowcount()==1)
                echo "<h3 class='alert alert-success'> ONE ADMIN IS TAKED  SUCCESSFULLAY </h3>";
                else
                echo "<script>alert('coudent take admin') </script>";
            }
        }
        elseif($_GET['action']=='master')
        {
            $sql="update users set Role='admin' ,MASTER=1,DELIVERY=1 where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));
        
            if($q->rowcount()==1)
            echo "<h3 class='alert alert-success'>  ONE ADMIN ADD SUCCESSFULLAY </h3>";
            else
            echo "<script>alert('coudent add admin') </script>";
        }
        header("Location: admins.php");
        exit();
    }

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa-solid fa-user-tie"></i> ADMINS PAGE</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
   
    <div class="row">
        <div class="col-xs-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fa-solid fa-user-tie"></i> ADMINS
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
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th colspan='' style='text-align:center'>ROLE</th>
                                            <th colspan='' style='text-align:center'>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                     $sql = "select * from users where Role='admin' order by ID desc";
                                     $q = $con->prepare($sql);
                                     $q->execute();
                                     if($q->rowcount())
                                     {
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $name = $row["USERNAME"];
                                            $email = $row["EMAIL"];
                                            $pass = $row["PASSWORD"];
                                            $role = $row["Role"];

                                            echo "<tr>";
                                                echo "<td>$id</td>";
                                                echo "<td>$name</td>";
                                                echo "<td>$email</td>";
                                                echo "<td>$role</td>";

                                                echo "<td>";
                                                if ($row["Role"]=="admin")
                                                echo "<a href='?action=tadmin&id=$id' class='btn btn-danger' title='tadmin'>tadmin </a> ";
                                            else
                                            echo "<a href='?action=admin&id=$id' class='btn btn-success' title='admin'>admin </a> ";
                                                echo "<a href='?action=delete&id=$id' class='btn btn-danger'  title='delete' onclick=\"return confirm('are you sure')\" >delete </a> ";
                                                if ($row["ACTIVE"]==1)
                                                echo "<a href='?action=inactive&id=$id' class='btn btn-warning'  title='Inactive' onclick=\"return confirm('are you sure')\">Inactive </a> ";
                                                else
                                                echo "<a href='?action=active&id=$id' class='btn btn-primary'  title='active' onclick=\"return confirm('are you sure')\"> Active </a> ";
                                                if($row["MASTER"]==0)
                                                echo "<a href='?action=master&id=$id' class='btn btn-success'  title='make master' onclick=\"return confirm('are you sure')\"> MASTER </a> ";
                                                else
                                                echo "";
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