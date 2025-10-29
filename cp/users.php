<?php
 include "header.php";
  require "../connection.php";
   if( !isset($_SESSION['user']) or  $_SESSION['Role']!="admin")
   {   header("Location:../login.php");
      exit();
    }
    
        // معالجة عملية البحث
        $searchQuery = "";
        $searchParams = [];

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchValue = "%" . $_GET['search'] . "%"; // البحث باستخدام % لتوسيع البحث ليشمل أي تطابق جزئي
            $searchQuery = "WHERE ID LIKE :search OR USERNAME LIKE :search OR EMAIL LIKE :search OR PHONE LIKE :search";
            $searchParams = ['search' => $searchValue];
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
                echo "<h3 class='alert alert-danger'  style='text-align: center;'> YOU CANT INACTIVE THIS ADMIN </h3>";
            }else
            {
                $sql="update users set ACTIVE=0 where ID=:id";
                $q= $con->prepare($sql);
                $q->execute(array("id"=>$_GET['id']));
                if($q->rowcount()==1)
                echo "<h3 class='alert alert-success'  style='text-align: center;'> INACTIVE SUCCESSFULLAY </h3>";
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
            echo "<h3 class='alert alert-success' style='text-align: center;'> ACTIVE SUCCESSFULLAY </h3>";
            else
            echo "<script>alert('coudent activeated') </script>";
        }
        elseif($_GET['action']=='admin')
        {
            $sql="update users set Role='admin',DELIVERY=1 where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));
        
            if($q->rowcount()==1)
            echo "<h3 class='alert alert-success' style='text-align: center;'>  ONE ADMIN ADD SUCCESSFULLAY </h3>";
            else
            echo "<script>alert('coudent add admin') </script>";
        }
        elseif($_GET['action']=='delivery')
        {
            $sql="update users set DELIVERY = 1 where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));

            if($q->rowcount()==1)
            echo "<script>alert('DELETED SUCCESSFULLAY') </script>";
            else
            echo "<script>alert('coudent delete') </script>";

              
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
                echo "<h3 class='alert alert-danger' style='text-align: center;'> YOU CANT EDIT THIS ADMIN </h3>";
            }
            else{
                $sql="update users set Role='user',DELIVERY=0 where ID=:id";
                $q= $con->prepare($sql);
                $q->execute(array("id"=>$_GET['id']));
            
                if($q->rowcount()==1)
                echo "<h3 class='alert alert-success'  style='text-align: center;'> ONE ADMIN IS TAKED  SUCCESSFULLAY </h3>";
                else
                echo "<script>alert('coudent take admin') </script>";
            }
        }
        header("Location: users.php");
     exit();

    }
   
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class='fa fa-users'></i> USERS</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
   
    <div class="row">
        <div class="col-xs-12">


            <!-- نموذج البحث -->
            <form method="get" action="users.php">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by ID, Name, Email, or Phone" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
            <br>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-users fa-fw"></i> Users
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
                                            <th>PHONE</th>
                                            <th>EMAIL</th>
                                            <th>User Name</th>
                                            <th colspan='' style='text-align:center'>ROLE</th>
                                            <th colspan='' style='text-align:center'>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                       $sql = "SELECT * FROM users $searchQuery ORDER BY ID DESC";
                                       $q = $con->prepare($sql);
                                       $q->execute($searchParams);
                                     if($q->rowcount())
                                     {
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $FNAME = $row["FIRST_NAME"];
                                            $LNAME = $row["LAST_NAME"];
                                            $PHONE = $row["PHONE"];
                                            $name = $row["USERNAME"];
                                            $EMA = $row["EMAIL"];
                                            $role = $row["Role"];

                                            echo "<tr>";
                                                echo "<td>$id</td>";
                                                echo "<td>$FNAME</td>";
                                                echo "<td>$LNAME</td>";
                                                echo "<td>$PHONE</td>";
                                                echo "<td>$EMA</td>";
                                                echo "<td>$name</td>";
                                                echo "<td>$role</td>";
                                                echo "<td>";
                                                $currentUserId=$_SESSION['user_id'];
                                                $sqlMaster="SELECT * FROM users WHERE ID=:id";
                                                $m4=$con->prepare($sqlMaster);
                                                $m4->bindValue(':id', $currentUserId);
                                                $m4->execute();
                                                $masterUser = $m4->fetch();
                                                if($masterUser['MASTER'] == 1)
                                                {
                                                  if ($row["Role"]=="admin")
                                                   echo "<a href='?action=tadmin&id=$id' class='btn btn-danger' title='tadmin'>tadmin </a> ";
                                                  else
                                                    echo "<a href='?action=admin&id=$id' class='btn btn-success' title='admin'>admin </a> ";
                                                }
                                                if ($row["ACTIVE"]==1)
                                                    echo "<a href='?action=inactive&id=$id' class='btn btn-warning'  title='Inactive' onclick=\"return confirm('are you sure')\">Inactive </a> ";
                                                 else
                                                    echo "<a href='?action=active&id=$id' class='btn btn-primary'  title='active' onclick=\"return confirm('are you sure')\"> Active </a> ";   
                                                if($row["DELIVERY"]==0)
                                                    echo "<a href='?action=delivery&id=$id' class='btn btn-primary'  title='make delivery' >Delivery </a> ";
                                                if($masterUser['MASTER'] == 1)
                                                {
                                                    echo "<a href='?action=delete&id=$id' class='btn btn-danger'  title='delete' onclick=\"return confirm('are you sure')\" >delete </a> ";
                                                }
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