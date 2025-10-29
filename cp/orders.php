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
        $ord_id =$_GET['id'];
        $sql="delete from sales where ORDERS_ID=:id";
        $q= $con->prepare($sql);
        $q ->bindValue(':id' ,$ord_id);
        $q->execute();       
        $sql="delete from order_items where ORDER_ID=:id";
        $q= $con->prepare($sql);
        $q ->bindValue(':id' ,$ord_id);
        $q->execute();
        $sql="delete from notifications where ORDER_ID=:id";
        $q= $con->prepare($sql);
        $q ->bindValue(':id' ,$ord_id);
        $q->execute();
        $sql="delete from orders where ID=:id";
        $q= $con->prepare($sql);
        $q ->bindValue(':id' ,$ord_id);
        $q->execute();
        if($q->rowcount()==1)
        echo "<script>alert('DELETED SUCCESSFULLAY') </script>";
        else
        echo "<script>alert('coudent delete') </script>";
    }
    header("Location: orders.php");
    exit();
}
 
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        <h1 class="page-header"><i class="fas fa-clipboard-list"></i> ORDERS</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
   
    <div class="row">
        <div class="col-xs-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                <i class="fas fa-receipt"></i> ORDERS
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
                                            <th style="text-align: center;">التحكم</th>
                                            <th style="text-align: center;">تفاصيل الفاتوره</th>
                                            <th style="text-align: center;">توقيت التوصيل</th>
                                            <th style="text-align: center;">حاله الطلب</th>
                                            <th style="text-align: center;">الاجمالي</th>
                                            <th style="text-align: center;">رقم العمليه</th>
                                            <th style="text-align: center;">اسم المحفظه</th>
                                            <th style="text-align: center;">رقم الحواله</th>
                                            <th style="text-align: center;">اسم شبكه التحويل</th>
                                            <th style="text-align: center;">طريقه الدفع</th>
                                            <th style="text-align: center;">توقيت الطلب</th>                                          
                                            <th style="text-align: center;">عنوان التوصيل</th>
                                            <th style="text-align: center;">رقم الهاتف</th>                                                            
                                            <th style="text-align: center;">اسم العميل</th>                                                                                            
                                            <th style="text-align: center;">رقم العميل</th>                                  
                                            <th style="text-align: center;">رقم الفاتوره</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                     $sql= "SELECT * FROM orders where STATUS='done' ORDER BY CREATED_AT DESC";
                                     $q= $con->prepare($sql);
                                     $q ->execute();
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $user_id = $row["USER_ID"];
                                            $full_name = $row["USER_FULL_NAME"];
                                            $phone_num = $row["PHONE"];
                                            $delvery_adress = $row["ADDRESS"];
                                            $creating_time = $row["CREATED_AT"]; 
                                            $pay_method = $row["PAYMENT_METHOD"];
                                            if($pay_method =='cod') 
                                            $pay_method ="الدفع عند الاستلام";                                      
                                            elseif($pay_method =='transfer') 
                                            $pay_method ="حواله محليه";                                      
                                            elseif($pay_method =='wallet') 
                                            $pay_method ="محففظه اكترونيه";                                      
                                            $trans_net = $row["TRANSFER_NETWORK"];
                                            $trans_id = $row["TRANSFER_ID"];
                                            $wallet_type = $row["WALLET_TYPE"];               
                                            $wallet_id = $row["WALLET_ID"];       
                                            $total = $row["TOTAL_AMOUNT"];
                                            $status = $row["STATUS"];
                                            if($status =='pending') 
                                            $status =" قيد المراجعه";                                      
                                            elseif($status =='delevring') 
                                            $status ="في الطريق اليك";                                      
                                            elseif($status =='done') 
                                            $status ="تم التوصيل"; 
                                            $time_deliv=$row["DELIVERED_AT"];


                                            echo "<tr>";
                                            echo "<td style='text-align: center;'><a href='?action=delete&id=$id' class='btn btn-danger'  title='delete' onclick=\"return confirm('are you sure')\" >delete </a></td>";  
                                            echo "<td style='text-align: center;'><a href='order-items.php?id=".$id."'> تفاصيل</a></td>";                                            
                                            echo "<td style='text-align: center;'>$time_deliv</td>";                                             
                                            echo "<td style='text-align: center;'>$status</td>";                                             
                                            echo "<td style='text-align: center;'>$total</td>";                                             
                                            echo "<td style='text-align: center;'>$wallet_id</td>"; 
                                            echo "<td style='text-align: center;'>$wallet_type</td>"; 
                                            echo "<td style='text-align: center;'>$trans_id</td>"; 
                                            echo "<td style='text-align: center;'>$trans_net</td>";  
                                            echo "<td style='text-align: center;'>$pay_method</td>";  
                                            echo "<td style='text-align: center;'>$creating_time</td>";     
                                            echo "<td style='text-align: center;'>$delvery_adress</td>";                 
                                            echo "<td style='text-align: center;'>$phone_num</td>";                          
                                            echo "<td style='text-align: center;'>$full_name</td>";                              
                                            echo "<td style='text-align: center;'>$user_id</td>";                
                                            echo "<td style='text-align: center;'>$id</td>";
                                            echo "</tr>";
                                        }
                                        $sql= "SELECT sum(TOTAL_AMOUNT) AS TOTAL_SALES FROM orders where STATUS='done'";
                                     $q= $con->prepare($sql);
                                     $q ->execute();
                                     $sales = $q->fetch();

                                        echo "<tr>";
                                        echo "<td colspan='16' style='text-align:center;'><h2>$ إجمالي المبلغ:  ".$sales['TOTAL_SALES'].  "</h2></td>"; 
                                        echo "</tr>";
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