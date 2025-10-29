<?php 
 require "../connection.php";
 include "header.php";
 $ice= $_SESSION['user_id'];
    $checkingsql = "select * from users where ID=:id";
    $chg = $con->prepare($checkingsql);
    $chg->bindValue(':id' ,$ice);
    $chg->execute();
    $chs=$chg->fetch(); 
    if(!isset($_SESSION['user_id']) or $chs["DELIVERY"]==0)
    {   header("Location: ../index.php");
       exit();
    }


 $order_id = $_GET['id'];


?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa-solid fa-newspaper"></i> ORDER-ITEMS </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
   
    <div class="row">
        <div class="col-xs-12">
            
            <div class="panel panel-default">
                <div class="panel-heading" >
                <?php 
                    echo '<h1 style="margin-left: 60%;">تفاصيل الفاتورة رقم : ' . $order_id . ' <i class="fa-solid fa-money-check-dollar"></i></h1>';

                
                ?>
                    <div class="pull-right">
                        
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" >
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">السعر</th>
                                            <th style="text-align: center;">تاريخ الشراء</th>
                                            <th style="text-align: center;">الكميه</th>
                                            <th style="text-align: center;">اسم المنتج</th>
                                            <th style="text-align: center;">رقم المنتج</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 

                                      $total=0;
                                      $sql= "SELECT p.ID AS product_id, p.TITLE AS product_name, oi.QUANTITY AS quantity, o.CREATED_AT AS purchase_date, (oi.QUANTITY * oi.PRICE) AS total_price FROM order_items oi JOIN products p ON oi.PRODUCT_ID = p.ID JOIN orders o ON oi.ORDER_ID = o.ID WHERE oi.ORDER_ID = :ordid;";
                                      $q= $con->prepare($sql);
                                      $q ->bindValue(':ordid' , $order_id);
                                      $q ->execute();     
                                      $result = $q->fetchall();                              
                                        foreach($result as $row)
                                        {
                                            $price= $row["total_price"] ;
                                            $madein= $row["purchase_date"];
                                            $quan= $row["quantity"];
                                            $proname= $row["product_name"];
                                            $proid= $row["product_id"];

                                            echo "<tr>";
                                            echo "<td style='text-align: center;'>$price</td>";                                            
                                            echo "<td style='text-align: center;'>$madein</td>";                                             
                                            echo "<td style='text-align: center;'>$quan</td>";                                             
                                            echo "<td style='text-align: center;'>$proname</td>";
                                            echo "<td style='text-align: center;'>$proid</td>";
                                            echo "</tr>";
                                            $total+= $price;
                                        }
                                        echo "<tr>";
                                        echo "<td colspan='5' style='text-align:center;'><h2>$ إجمالي المبلغ:  ".$total.  "</h2></td>"; 

                                        echo "</tr>";
                                      
                                        
                                     
                                    ?>   

                                    </tbody>
                                </table>
                            </div>
                            <div class="d-grid">
                            <a href="delivery.php" class="btn btn-primary btn-block"> العوده للقاىمه الريسيه</a>
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