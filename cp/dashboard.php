<?php
 require "../connection.php";
 include "header.php";
 if( !isset($_SESSION['user_id']) or $_SESSION['Role']!="admin")
 {   header("Location:../login.php");
    exit();
  }
?>


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class='fa fa-dashboard'></i> Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">

                            <?php
                                    $sql = "select * from users";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>Users</div>
                        </div>
                    </div>
                </div>
                <a href="users.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
						
					<div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-newspaper-o fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">
                            <?php
                                    $sql = "select * from products";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>Add Products</div>
                        </div>
                    </div>
                </div>
                <a href="../cp/posts.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>


        <div class="col-lg-3 col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa-solid fa-stopwatch fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">
                            <?php
                                    $sql = "select * from orders where STATUS='pending'";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>ORDER PENDING</div>
                        </div>
                    </div>
                </div>
                <a href="../cp/order_pending.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>


        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class="fa-solid fa-motorcycle fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">

                            <?php
                                    $sql = "SELECT * FROM orders where STATUS='delevring'";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>DELIVERY</div>
                        </div>
                    </div>
                </div>
                <a href="delivery.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
					

       


        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class="fa-solid fa-user-tie" style="font-size: 5em;"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">
                            <?php
                                    $sql = "select * from users where Role='admin'";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>
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
                            ADMINS </div>
                        </div>
                    </div>
                </div>
                <a href="../cp/admins.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>


        <div class="col-lg-3 col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-newspaper-o fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">
                            <?php
                                    $sql = "select * from orders";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>
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
                            ORDERS</div>
                        </div>
                    </div>
                </div>
                <a href="../cp/orders.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class="fa-solid fa-microphone-lines fa-5x"></i> 
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">
                            <?php
                                     $sql = "select * FROM notifications WHERE STATUSS = 'public' GROUP BY MASSAGE";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>
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
                            RADIO PAGE  </div>
                        </div>
                    </div>
                </div>
                <a href="../cp/radio.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class="fas fa-truck fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">
                            <?php
                                     $sql = "select * from users where DELIVERY=1 and Role!='admin' order by ID desc";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>
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
                            DELIVERY USERS </div>
                        </div>
                    </div>
                </div>
                <a href="../cp/delivery_users.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>


        
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class="fa-solid fa-comments" style="font-size: 5em;"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">

                            <?php
                                    $sql = "select * from messages";
                                    $q= $con->prepare($sql);
                                    $q->execute();
                                    echo  $q->rowcount();
                            ?>
                            </div>
                            <div>MESSAGES</div>
                        </div>
                    </div>
                </div>
                <a href="messages.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

       


						
    </div>
    <!-- /.row -->
  
</div>         
</div>

<script src="../js/jquery-3.1.0.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>