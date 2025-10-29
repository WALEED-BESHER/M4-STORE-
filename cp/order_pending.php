<?php  
include "header.php";
require "../connection.php";

// التحقق من صلاحيات المستخدم
if(!isset($_SESSION['user']) || $_SESSION['Role']!="admin") {   
    header("Location:../login.php");
    exit();
}

// معالجة طلبات القبول أو الرفض
if(isset($_GET['action'], $_GET['id']) && intval($_GET['id'])>0) {
    $ord_id = $_GET['id'];
    
    // الحصول على معلومات الطلب
    $sql = "SELECT * FROM orders WHERE ID=:id";
    $q = $con->prepare($sql);
    $q->bindValue(':id', $ord_id);
    $q->execute();
    $order = $q->fetch();
    $us = $order["USER_ID"];
    
    if($_GET['action'] == 'accept') { 
        $verification_code = sprintf("%03d-%03d", rand(0,999), rand(0,999));
        $message = "لقد تم قبول طلبك رقم $ord_id وسيتم التوصيل في اسرع وقت ممكن وهذا الكود $verification_code لا تعطيه الموصل الا حين تستلم بضاعتك ولسنا متحملين اي مسوليه اذا عطيته قبل تسليم شحنتك و شكرا لكم ولثقتكم بنا";
        
        $sql = "UPDATE orders SET STATUS='delevring', VERIFICATION_CODE=:code WHERE ID=:id";
        $q = $con->prepare($sql);
        $q->bindValue(':code', $verification_code);
        $q->bindValue(':id', $ord_id);
        $q->execute();
        
        $sql="INSERT INTO notifications(USER_ID ,ORDER_ID ,MASSAGE) VALUES (:user_id ,:order_id ,:massage)";
        $q= $con->prepare($sql);
        $q ->bindValue(':user_id',$us);
        $q ->bindValue(':order_id',$ord_id);
        $q ->bindValue(':massage',$message);
        $q->execute();
    }
    
    if($_GET['action'] == 'reject') {
        // إذا كان هناك سبب مرفق مع الرفض
        $reject_reason = isset($_POST['reject_reason']) ? $_POST['reject_reason'] : 'بعض الأسباب';
        $message = "لقد تم رفض طلبك رقم $ord_id نظراً لـ: $reject_reason";
        
        // حذف الطلب والبيانات المرتبطة به
        $tables = ['sales', 'order_items', 'orders'];
        foreach($tables as $table) {
            $column = ($table == 'sales') ? 'ORDERS_ID' : (($table == 'order_items') ? 'ORDER_ID' : 'ID');
            $sql = "DELETE FROM $table WHERE $column=:id";
            $q = $con->prepare($sql);
            $q->bindValue(':id', $ord_id);
            $q->execute();
        }
        
        $sql="INSERT INTO notifications(USER_ID ,MASSAGE) VALUES (:user_id ,:massage)";
        $q= $con->prepare($sql);
        $q ->bindValue(':user_id',$us);
        $q ->bindValue(':massage',$message);
        $q->execute();
    }
    
    header("Location: order_pending.php");
    exit();
}

// دالة مساعدة لإضافة إشعار
function addNotification($user_id, $order_id, $message) {
    global $con;
    
    $sql = "INSERT INTO notifications(USER_ID, ORDER_ID, MASSAGE) VALUES (:user_id, :order_id, :message)";
    $q = $con->prepare($sql);
    $q->bindValue(':user_id', $user_id);
    $q->bindValue(':order_id', $order_id);
    $q->bindValue(':message', $message);
    $q->execute();
}
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa-solid fa-hourglass-start"></i> ORDER PENDING</h1>
        </div>
    </div>
   
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa-solid fa-stopwatch"></i> ORDER PENDING
                </div>
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">سبب الرفض</th>
                                            <th style="text-align: center;">حاله الطلب</th> 
                                            <th style="text-align: center;">تفاصيل الفاتوره</th>
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
                                    $sql = "SELECT * FROM orders WHERE STATUS='pending' ORDER BY CREATED_AT DESC";
                                    $q = $con->prepare($sql);
                                    $q->execute();
                                    $result = $q->fetchAll();
                                    
                                    foreach($result as $row) {
                                        $id = $row["ID"];
                                        $user_id = $row["USER_ID"];
                                        $full_name = $row["USER_FULL_NAME"];
                                        $phone_num = $row["PHONE"];
                                        $delvery_adress = $row["ADDRESS"];
                                        $creating_time = $row["CREATED_AT"]; 
                                        $pay_method = $row["PAYMENT_METHOD"];
                                        
                                        // تحويل طريقة الدفع إلى نص مقروء
                                        $pay_methods = [
                                            'cod' => 'الدفع عند الاستلام',
                                            'transfer' => 'حواله محليه',
                                            'wallet' => 'محففظه اكترونيه'
                                        ];
                                        $pay_method = $pay_methods[$pay_method] ?? $pay_method;
                                        
                                        $trans_net = $row["TRANSFER_NETWORK"];
                                        $trans_id = $row["TRANSFER_ID"];
                                        $wallet_type = $row["WALLET_TYPE"];               
                                        $wallet_id = $row["WALLET_ID"];       
                                        $total = $row["TOTAL_AMOUNT"];
                                        $status = $row["STATUS"];
                                    ?>
                                        <tr>
                                            <td style='text-align: center;'>
                                                <form method='post' action='?action=reject&id=<?= $id ?>' style='display:inline;'>
                                                    <input type='text' name='reject_reason' placeholder='سبب الرفض' required>
                                                    <button type='submit' class='btn btn-danger btn-xs'>إرسال</button>
                                                </form>
                                            </td>
                                            <td style='text-align: center;'>
                                                <a href='?action=accept&id=<?= $id ?>' title='قبول' class='btn btn-success btn-xs'><i class='fa-solid fa-square-check'></i> قبول</a>
                                            </td> 
                                            <td style='text-align: center;'><a href='order-items.php?id=<?= $id ?>'> تفاصيل</a></td>                                            
                                            <td style='text-align: center;'><?= $total ?></td>                                             
                                            <td style='text-align: center;'><?= $wallet_id ?></td> 
                                            <td style='text-align: center;'><?= $wallet_type ?></td> 
                                            <td style='text-align: center;'><?= $trans_id ?></td> 
                                            <td style='text-align: center;'><?= $trans_net ?></td>  
                                            <td style='text-align: center;'><?= $pay_method ?></td>  
                                            <td style='text-align: center;'><?= $creating_time ?></td>     
                                            <td style='text-align: center;'><?= $delvery_adress ?></td>                 
                                            <td style='text-align: center;'><?= $phone_num ?></td>                          
                                            <td style='text-align: center;'><?= $full_name ?></td>                              
                                            <td style='text-align: center;'><?= $user_id ?></td>                
                                            <td style='text-align: center;'><?= $id ?></td>
                                        </tr>
                                    <?php } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/jquery-3.1.0.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
<script>
$(function() {
    'use strict';
    $('#deletej').click(function() {
        return confirm('هل أنت متأكد؟');
    });
});
</script>
</body>
</html>