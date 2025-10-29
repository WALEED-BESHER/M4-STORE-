<?php 
 require "../connection.php";
?>
<!DOCTYPE html>
<html>
<head> 
 <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/font-awesome.min.css" rel="stylesheet" />
	<link href="css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div id="wrapper" class="active">
    <?php 
    session_start();
    if(isset($_SESSION['user_id']) and $_SESSION['Role']=="admin"):
    ?>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="../index.php">Dashboard</a>
    </div>
    <!-- /.navbar-header -->

    <div class="navbar-default sidebar" role="navigation">                  
        <div class="sidebar-nav">
            <ul class="nav" id="side-menu">   
                <li>
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i> <span class="ttspan-fill">Dashboard</span></a>
                </li>
				<li>
                    <a href="users.php"><i class="fa fa-users fa-fw"></i> <span class="ttspan-fill">Users</span></a>
                </li>
				
				<li>
                    <a href="posts.php"><i class="fa fa-newspaper-o fa-fw"></i> <span class="ttspan-fill">Add Products</span></a>
                </li>
                <li>
                    <a href="order_pending.php"><i class="fa-solid fa-stopwatch"></i><span class="ttspan-fill"> ORDERS PENDING</span></a>
                </li>
                <li>
                    <a href="delivery.php"><i class="fa-solid fa-motorcycle"></i><span class="ttspan-fill"> DELIVERY</span></a>
                </li>
                <li>
                    <a href="admins.php"><i class="fa-solid fa-user-tie"></i><span class="ttspan-fill"> Admins 
                    <?php      
                            $id= $_SESSION["user_id"];
                            $sql = "select * from users where ID=:id";
                            $q = $con->prepare($sql);
                            $q ->bindValue(':id' , $id);
                            $q->execute();
                            $ch=$q->fetch();
                             if($ch["MASTER"]==0)
                             {
                                echo "<i class='fa-solid fa-lock'></i>";
                             }
                        ?>   
                    </span></a>
                </li>
                <li>
                    <a href="orders.php"><i class="fas fa-clipboard-list"></i><span class="ttspan-fill"> ORDERS
                    <?php      
                            $id= $_SESSION["user_id"];
                            $sql = "select * from users where ID=:id";
                            $q = $con->prepare($sql);
                            $q ->bindValue(':id' , $id);
                            $q->execute();
                            $ch=$q->fetch();
                             if($ch["MASTER"]==0)
                             {
                                echo "<i class='fa-solid fa-lock'></i>";
                             }
                        ?>   
                    </span></a>
                </li>
                <li>
                    <a href="radio.php"><i class="fa-solid fa-microphone-lines"></i><span class="ttspan-fill">  RADIO PAGE  
                    <?php      
                            $id= $_SESSION["user_id"];
                            $sql = "select * from users where ID=:id";
                            $q = $con->prepare($sql);
                            $q ->bindValue(':id' , $id);
                            $q->execute();
                            $ch=$q->fetch();
                             if($ch["MASTER"]==0)
                             {
                                echo "<i class='fa-solid fa-lock'></i>";
                             }
                        ?>   
                    </span></a>
                </li>
                <li>
                    <a href="delivery_users.php"><i class="fas fa-truck"></i><span class="ttspan-fill"> DELIVERY USERS  
                    <?php      
                            $id= $_SESSION["user_id"];
                            $sql = "select * from users where ID=:id";
                            $q = $con->prepare($sql);
                            $q ->bindValue(':id' , $id);
                            $q->execute();
                            $ch=$q->fetch();
                             if($ch["MASTER"]==0)
                             {
                                echo "<i class='fa-solid fa-lock'></i>";
                             }
                        ?>   
                    </span></a>
                </li>
                <li>
                    <a href="messages.php"><i class="fa-solid fa-comments"></i> <span class="ttspan-fill">messages</span></a>
                </li>
				<li>
                    <a href="../index.php"><i class="fa-solid fa-house"></i><span class="ttspan-fill">home</span></a>
                </li>
                
            </ul>
        </div>
    </div>                             
</nav>  
<?php endif; ?>