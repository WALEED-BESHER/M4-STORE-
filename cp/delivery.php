<?php
session_start();
require "../connection.php";

$ice= $_SESSION['user_id'];
$checkingsql = "select * from users where ID=:id";
$chg = $con->prepare($checkingsql);
$chg->bindValue(':id' , $ice);
$chg->execute();
$chs=$chg->fetch(); 
if( !isset($_SESSION['user_id']) or $chs["DELIVERY"]!=1)
 {   header("Location: ../index.php");
    exit();
 }
// معالجة تأكيد التوصيل
if(isset($_POST['submit_delivery'])) {
    $order_id = $_POST['order_id'];
    $entered_code = $_POST['verification_code'];
    
    // التحقق من صحة الكود
    $sql = "SELECT * FROM orders WHERE ID=:id AND verification_code=:code";
    $q = $con->prepare($sql);
    $q->execute(array(
        "id" => $order_id,
        "code" => $entered_code
    ));
    $order = $q->fetch();
    
    if($order) {
        // تحديث حالة الطلب إلى "تم التوصيل"
        $update_sql = "UPDATE orders SET STATUS='done', DELIVERED_AT=NOW() WHERE ID=:id";
        $update_q = $con->prepare($update_sql);
        $update_q->execute(array("id" => $order_id));
        
        // إرسال إشعار للمستخدم
        $notification_sql = "INSERT INTO notifications (USER_ID ,ORDER_ID ,MASSAGE) 
                            VALUES (:user_id, :order_id, :messages)";
        $notification_q = $con->prepare($notification_sql);
        $notification_q->execute(array(
            "user_id" => $order['USER_ID'],
            "order_id" => $order_id,
            "messages" => "تم توصيل طلب رقم  $order_id  بنجاح !"
        ));
        
        $delivery_success = "تم توصيل طلب رقم  $order_id  بنجاح !";
    } else {
        $delivery_error = "كود التوصيل غير صحيح!";
    }
}

// جلب الطلبات الجاهزة للتوصيل
$sql = "SELECT * FROM orders WHERE STATUS='delevring' ORDER BY CREATED_AT DESC";
$q = $con->prepare($sql);
$q->execute();
$orders = $q->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>تأكيد التوصيل</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .code-input {
            letter-spacing: 5px;
            font-size: 24px;
            text-align: center;
            width: 150px;
        }
    </style>
</head>
<body>

<div class="row">
        <div class="col-xs-12">
            
            <div class="panel panel-default">
                <div class="panel-heading">
              
                   
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" >
                                    <thead>
                                        <tr> 
                                            <th style="text-align: center;">تفاصيل الفاتوره</th>
                                            <th style="text-align: center;">الاجمالي</th>
                                            <th style="text-align: center;">طريقه الدفع</th>
                                            <th style="text-align: center;">توقيت الطلب</th>                                          
                                            <th style="text-align: center;">عنوان التوصيل</th>
                                            <th style="text-align: center;">رقم الهاتف</th>                                                            
                                            <th style="text-align: center;">اسم العميل</th>                                                                                                                             
                                            <th style="text-align: center;">رقم الفاتوره</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                     $sql= "SELECT * FROM orders where STATUS='delevring' ORDER BY CREATED_AT DESC";
                                     $q= $con->prepare($sql);
                                     $q ->execute();
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
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
                                            $total = $row["TOTAL_AMOUNT"];

                                            echo "<tr>"; 
                                            echo "<td style='text-align: center;'><a href='order-items.php?id=".$id."'> تفاصيل</a></td>";                                            
                                            echo "<td style='text-align: center;'>$total</td>";                                              
                                            echo "<td style='text-align: center;'>$pay_method</td>";  
                                            echo "<td style='text-align: center;'>$creating_time</td>";     
                                            echo "<td style='text-align: center;'>$delvery_adress</td>";                 
                                            echo "<td style='text-align: center;'>$phone_num</td>";                          
                                            echo "<td style='text-align: center;'>$full_name</td>";                                           
                                            echo "<td style='text-align: center;'>$id</td>";
                                            echo "</tr>";
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


<div class="container">
    <h2 class="text-center"><i class="fas fa-truck"></i> تأكيد التوصيل</h2>
    
    <?php if(isset($delivery_success)): ?>
        <div class="alert alert-success"><?= $delivery_success ?></div>
    <?php elseif(isset($delivery_error)): ?>
        <div class="alert alert-danger"><?= $delivery_error ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>إدخال كود التوصيل</h4>
                </div>
                <div class="panel-body">
                    <form method="post">
                        <div class="form-group">
                            <label>رقم الطلب</label>
                            <select name="order_id" class="form-control" required>
                                <option value="">اختر الطلب</option>
                                <?php foreach($orders as $order): ?>
                                    <option value="<?= $order['ID'] ?>">
                                       #<?= $order['ID'] ?>  -  العنوان : <?= $order['ADDRESS'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>كود التوصيل (6 أرقام)</label>
                            <input type="text" name="verification_code" id="verificationCode" 
                                class="form-control code-input" placeholder="123-456" 
                                required pattern="\d{3}-\d{3}" maxlength="7">
                        </div>
                        <button type="submit" name="submit_delivery" class="btn btn-primary btn-block">
                            <i class="fas fa-check-circle"></i> تأكيد التوصيل
                        </button><br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('verificationCode').addEventListener('input', function(e) {
    // إزالة أي شرطات موجودة
    let value = e.target.value.replace(/-/g, '');
    
    // إضافة الشرطة بعد 3 أرقام
    if (value.length > 3) {
        value = value.substring(0, 3) + '-' + value.substring(3, 6);
    }
    
    e.target.value = value;
});
</script>
</body>
</html>