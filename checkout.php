<?php
include "../M4_STORE/header.php";//header
if( !isset($_SESSION['user']) )
{   header("Location:login.php");
   exit();
 }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من الجلسة
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('السله فارغه ') </script>";
        exit();
    }
    
    if (empty($_SESSION['user_id'])) {
        echo "<script>alert('لم تقم بتسجيل الدخول') </script>";
        exit();
    }

    // حساب المبلغ الإجمالي
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    $paymentMethod = $_POST['payment-method'];
    $user_id = $_SESSION['user_id'];
    
   
    // إذا كانت طريقة الدفع هي الدفع عند الاستلام
    if ($paymentMethod == 'cod') {
        $fullName = isset($_POST['full-name']) ? $_POST['full-name'] : '';
        $address = isset($_POST['houseaddress']) ? $_POST['houseaddress'] : '';
        $phone = isset($_POST['personphone']) ? $_POST['personphone'] : '';

         // التحقق من الحقول
        if (empty($fullName)||empty($address)||empty($phone)) {
            $errors['empts']= "<span style='color:red; font-size:25px; position:center;'> الرجاء تعبئة جميع الحقول <i class='fa-solid fa-ban'></i></span>";  
            exit();
        }
       elseif(!preg_match("/(96777|96773|96771|96770)[0-9]{7}$/",$phone))
        $errors['phone'] = "<span style='color:red; font-size:25px;'> رقم الهاتف غير صالح. يجب أن يبدأ بـ  967 ويتبعه 9 أرقام<i class='fa-solid fa-ban'></i></span><br>";
       elseif(strlen($fullName)<3) // NAME LESS THAN 3 LETERS
       $errors['lename']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> NAME SHOULD BE MORE THAN 3 LETTERS <br> </span>";
       elseif(strlen($fullName)>70) // NAME MORE THAN 20 LETERS
       $errors['moname']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> NAME SHOULD BE LESS THAN 70 LETTERS <br> </span>";  
       elseif(strlen($address)<3) //address LESS THAN 3 LETERS
       $errors['leadd']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> ADDRESS SHOULD BE MORE THAN 3 LETTERS <br> </span>";
       elseif(strlen($address)>70) //address MORE THAN 70 LETERS
       $errors['moadd']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> ADDRESS SHOULD BE LESS THAN 70 LETTERS <br> </span>";  

        else{
                // إدخال الطلب في قاعدة البيانات
                $sql = "INSERT INTO orders (USER_ID, TOTAL_AMOUNT, USER_FULL_NAME, ADDRESS, PHONE, PAYMENT_METHOD) 
                        VALUES (:userid, :total, :username, :addr, :phone, :paymeth)";
                $q = $con->prepare($sql);
                $q->bindValue(':userid', $user_id);
                $q->bindValue(':total', $total_amount);
                $q->bindValue(':username', $fullName);
                $q->bindValue(':addr', $address);
                $q->bindValue(':phone', $phone);
                $q->bindValue(':paymeth', $paymentMethod);
                $q->execute();
                $order_id = $con->lastInsertId();
    
        
                // إضافة العناصر في الفاتورة
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $sql = "INSERT INTO order_items (ORDER_ID, PRODUCT_ID, QUANTITY, PRICE) 
                            VALUES(:ordid, :proid, :quan, :price)";
                    $q = $con->prepare($sql);
                    $q->bindValue(':ordid', $order_id);
                    $q->bindValue(':proid', $product_id);
                    $q->bindValue(':quan', $item['quantity']);
                    $q->bindValue(':price', $item['price']);
                    $q->execute();
                }

                // تسجيل المبيعات
                $sql = "INSERT INTO sales (ORDERS_ID, AMOUNT) VALUES (:ord, :amou)";
                $q = $con->prepare($sql);
                $q->bindValue(':ord', $order_id);
                $q->bindValue(':amou', $total_amount);
                $q->execute();

                $us =$_SESSION["user_id"];
                $message="تم ارسال طلبك بنجاح! وطلبك رقم $order_id  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض";
                $sql="INSERT INTO notifications(USER_ID ,ORDER_ID ,MASSAGE) VALUES (:user_id ,:order_id ,:massage)";
                $q= $con->prepare($sql);
                $q ->bindValue(':user_id',$us);
                $q ->bindValue(':order_id',$order_id);
                $q ->bindValue(':massage',$message);
                $q->execute();


                    
                // تفريغ السلة بعد إتمام الشراء
                unset($_SESSION['cart']);

                echo '<center><h1 style="margin-top: 1.5em;">تم إتمام العملية بنجاح! وطلبك تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض</h1>
                    <h2 style="text-align:center; color:black;">رقم الفاتورة: ' . $order_id . '</h2>
                    <h2 style="text-align:center; color:black;">إجمالي المبلغ: $' . $total_amount . '</h2>
                    <div class="cart-done"><a href="index.php" class="cart-done-button"><br>الصفحة الرئيسية</a></div>
                    </center>';
                exit();
            }
    }
     
    // إذا كانت طريقة الدفع هي حواله
    if ($paymentMethod == 'transfer')
    {
         // التحقق من الحقول
        $transferFullName = isset($_POST['transfer-full-name']) ? $_POST['transfer-full-name'] : '';
        $transferPhone = isset($_POST['transfer-phone']) ? $_POST['transfer-phone'] : '';
        $transferAddress = isset($_POST['transfer-address']) ? $_POST['transfer-address'] : '';
        $transferId = isset($_POST['transfer-id']) ? $_POST['transfer-id'] : '';
        $transferNetwork = isset($_POST['transfer-network']) ? $_POST['transfer-network'] : '';

        if (empty($transferFullName) || empty($transferPhone) || empty($transferAddress) || empty($transferId) || empty($transferNetwork)) {
            $errors['empts']= "<span style='color:red; font-size:25px; position:center;'> الرجاء تعبئة جميع الحقول <i class='fa-solid fa-ban'></i></span>";  
        }
        elseif(!preg_match("/(96777|96773|96771|96770)[0-9]{7}$/",$transferPhone))
        $errors['phone'] = "<span style='color:red; font-size:25px;'> رقم الهاتف غير صالح. يجب أن يبدأ بـ  967 ويتبعه 9 أرقام<i class='fa-solid fa-ban'></i></span><br>";
       elseif(strlen($transferFullName)<3) // NAME LESS THAN 3 LETERS
       $errors['lename']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> NAME SHOULD BE MORE THAN 3 LETTERS <br> </span>";
       elseif(strlen($transferFullName)>70) // NAME MORE THAN 20 LETERS
       $errors['moname']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> NAME SHOULD BE LESS THAN 70 LETTERS <br> </span>";  
       elseif(strlen($transferAddress)<3) //address LESS THAN 3 LETERS
       $errors['leadd']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> ADDRESS SHOULD BE MORE THAN 3 LETTERS <br> </span>";
       elseif(strlen($transferAddress)>70) //address MORE THAN 70 LETERS
       $errors['moadd']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> ADDRESS SHOULD BE LESS THAN 70 LETTERS <br> </span>";  
       elseif(strlen($transferId)<3) //transfer_id less THAN 3 LETERS
       $errors['ltranid']= "<span style='color:red; font-size:25px; position:center;'> رقم الحواله اقل من 3 حروف <i class='fa-solid fa-ban'></i></span>";  
       elseif(strlen($transferId)>30) //transfer_id more  THAN 30 LETERS
       $errors['mtranid']= "<span style='color:red; font-size:25px; position:center;'> رقم الحواله اكثر  من 30 حرف <i class='fa-solid fa-ban'></i></span>";  
       elseif(strlen($transferNetwork)<3) //transfer_network less THAN 3 LETERS
       $errors['ltrannet']= "<span style='color:red; font-size:25px; position:center;'> اسم شبكه الحوالات اقل من 3 حروف <i class='fa-solid fa-ban'></i></span>";  
       
       elseif(strlen($transferNetwork)>30) //transfer_network less THAN 3 LETERS
       $errors['mtrannet']= "<span style='color:red; font-size:25px; position:center;'> اسم شبكه الحوالات اكثر من 30 حرف <i class='fa-solid fa-ban'></i></span>";  
       


        else
        {
            // إدخال الطلب في قاعدة البيانات
            $sql = "INSERT INTO orders (USER_ID, TOTAL_AMOUNT, USER_FULL_NAME, ADDRESS, PHONE, PAYMENT_METHOD, TRANSFER_ID, TRANSFER_NETWORK) 
                    VALUES (:userid, :total, :username, :addr, :phone, :paymeth, :tranid, :trannet)";
            $q = $con->prepare($sql);
            $q->bindValue(':userid', $user_id);
            $q->bindValue(':total', $total_amount);
            $q->bindValue(':username', $transferFullName);
            $q->bindValue(':addr',  $transferAddress);
            $q->bindValue(':phone', $transferPhone);
            $q->bindValue(':paymeth',$paymentMethod);
            $q ->bindValue(':tranid' ,$transferId);
            $q ->bindValue(':trannet' ,$transferNetwork);
            $q->execute();
            $order_id = $con->lastInsertId();
        
            // إضافة العناصر في الفاتورة
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $sql = "INSERT INTO order_items (ORDER_ID, PRODUCT_ID, QUANTITY, PRICE) 
                        VALUES(:ordid, :proid, :quan, :price)";
                $q = $con->prepare($sql);
                $q->bindValue(':ordid', $order_id);
                $q->bindValue(':proid', $product_id);
                $q->bindValue(':quan', $item['quantity']);
                $q->bindValue(':price', $item['price']);
                $q->execute();
            }
            // تسجيل المبيعات
            $sql = "INSERT INTO sales (ORDERS_ID, AMOUNT) VALUES (:ord, :amou)";
            $q = $con->prepare($sql);
            $q->bindValue(':ord', $order_id);
            $q->bindValue(':amou', $total_amount);
            $q->execute();

            $us =$_SESSION["user_id"];
            $message="تم ارسال طلبك بنجاح! وطلبك رقم $order_id  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض";
            $sql="INSERT INTO notifications(USER_ID ,ORDER_ID ,MASSAGE) VALUES (:user_id ,:order_id ,:massage)";
            $q= $con->prepare($sql);
            $q ->bindValue(':user_id',$us);
            $q ->bindValue(':order_id',$order_id);
            $q ->bindValue(':massage',$message);
            $q->execute();

            // تفريغ السلة بعد إتمام الشراء
            unset($_SESSION['cart']);

            echo '<center><h1 style="margin-top: 1.5em;">تم إتمام العملية بنجاح! وطلبك تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض</h1>
                <h2 style="text-align:center; color:black;">رقم الفاتورة: ' . $order_id . '</h2>
                <h2 style="text-align:center; color:black;">إجمالي المبلغ: $' . $total_amount . '</h2>
                <div class="cart-done"><a href="index.php" class="cart-done-button"><br>الصفحة الرئيسية</a></div>
                </center>';
            exit();

        }
        

    }

   if ($paymentMethod == 'wallet')
    {
         // التحقق من الحقول

        $walletFullName = isset($_POST['wallet-full-name']) ? $_POST['wallet-full-name'] : '';
        $walletAddress = isset($_POST['wallet-address']) ? $_POST['wallet-address'] : '';
        $walletPhone = isset($_POST['wallet-phone']) ? $_POST['wallet-phone'] : '';
        $walletType = isset($_POST['wallet-type']) ? $_POST['wallet-type'] : '';
        $walletTransactionId = isset($_POST['wallet-transaction-id']) ? $_POST['wallet-transaction-id'] : '';

        if (empty($walletFullName) || empty($walletAddress) || empty($walletPhone) || empty($walletType) || empty($walletTransactionId)) {
            $errors['empts']= "<span style='color:red; font-size:25px; position:center;'> الرجاء تعبئة جميع الحقول <i class='fa-solid fa-ban'></i></span>";    
        }
        elseif(!preg_match("/(96777|96773|96771|96770)[0-9]{7}$/",$walletPhone))
        $errors['phone'] = "<span style='color:red; font-size:25px;'> رقم الهاتف غير صالح. يجب أن يبدأ بـ  967 ويتبعه 9 أرقام<i class='fa-solid fa-ban'></i></span><br>";
       elseif(strlen($walletFullName)<3) // NAME LESS THAN 3 LETERS
       $errors['lename']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> NAME SHOULD BE MORE THAN 3 LETTERS <br> </span>";
       elseif(strlen($walletFullName)>70) // NAME MORE THAN 20 LETERS
       $errors['moname']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> NAME SHOULD BE LESS THAN 70 LETTERS <br> </span>";  
       elseif(strlen($walletAddress)<3) //address LESS THAN 3 LETERS
       $errors['leadd']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> ADDRESS SHOULD BE MORE THAN 3 LETTERS <br> </span>";
       elseif(strlen($walletAddress)>70) //address MORE THAN 70 LETERS
       $errors['moadd']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> ADDRESS SHOULD BE LESS THAN 70 LETTERS <br> </span>";  
       elseif(strlen($walletTransactionId)<3) //transfer_id less THAN 3 LETERS
       $errors['ltranid']= "<span style='color:red; font-size:25px; position:center;'> رقم العمليه اقل من 3 حروف <i class='fa-solid fa-ban'></i></span>";  
       elseif(strlen($walletTransactionId)>30) //transfer_id more  THAN 30 LETERS
       $errors['mtranid']= "<span style='color:red; font-size:25px; position:center;'>  رقم العمليه اكثر  من 30 حرف <i class='fa-solid fa-ban'></i></span>";  
       elseif(strlen($walletType)<3) //transfer_network less THAN 3 LETERS
       $errors['ltrannet']= "<span style='color:red; font-size:25px; position:center;'> اسم المحفظه اقل من 3 حروف <i class='fa-solid fa-ban'></i></span>";  
       
       elseif(strlen($walletType)>30) //transfer_network less THAN 3 LETERS
       $errors['mtrannet']= "<span style='color:red; font-size:25px; position:center;'> اسم المحفظه اكثر من 30 حرف <i class='fa-solid fa-ban'></i></span>";  
       

        else
        {
            // إدخال الطلب في قاعدة البيانات
            $sql = "INSERT INTO orders (USER_ID, TOTAL_AMOUNT, USER_FULL_NAME, ADDRESS, PHONE, PAYMENT_METHOD, WALLET_TYPE, WALLET_ID) 
                VALUES (:userid, :total, :username, :addr, :phone, :paymeth, :waltype, :walid)";
            $q = $con->prepare($sql);
            $q ->bindValue(':userid' , $user_id);
            $q ->bindValue(':total' , $total_amount);
            $q ->bindValue(':username' ,$walletFullName);
            $q ->bindValue(':addr' , $walletAddress);
            $q ->bindValue(':phone' ,$walletPhone);
            $q ->bindValue(':paymeth' ,$paymentMethod);
            $q ->bindValue(':waltype' ,$walletType);
            $q ->bindValue(':walid' ,$walletTransactionId);
            $q->execute();
            $order_id = $con->lastInsertId();
   
           // إضافة العناصر في الفاتورة
           foreach ($_SESSION['cart'] as $product_id => $item) {
               $sql = "INSERT INTO order_items (ORDER_ID, PRODUCT_ID, QUANTITY, PRICE) 
                       VALUES(:ordid, :proid, :quan, :price)";
               $q = $con->prepare($sql);
               $q->bindValue(':ordid', $order_id);
               $q->bindValue(':proid', $product_id);
               $q->bindValue(':quan', $item['quantity']);
               $q->bindValue(':price', $item['price']);
               $q->execute();
           }
           // تسجيل المبيعات
           $sql = "INSERT INTO sales (ORDERS_ID, AMOUNT) VALUES (:ord, :amou)";
           $q = $con->prepare($sql);
           $q->bindValue(':ord', $order_id);
           $q->bindValue(':amou', $total_amount);
           $q->execute();

           $us =$_SESSION["user_id"];
           $message="تم ارسال طلبك بنجاح! وطلبك رقم $order_id  تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض";
           $sql="INSERT INTO notifications(USER_ID ,ORDER_ID ,MASSAGE) VALUES (:user_id ,:order_id ,:massage)";
           $q= $con->prepare($sql);
           $q ->bindValue(':user_id',$us);
           $q ->bindValue(':order_id',$order_id);
           $q ->bindValue(':massage',$message);
           $q->execute();
   
           // تفريغ السلة بعد إتمام الشراء
           unset($_SESSION['cart']);
   
           echo '<center><h1 style="margin-top: 1.5em;">تم إتمام العملية بنجاح! وطلبك تحت المراجعه سيتم ارسال اشعار اليك في حال الموافقه او الرفض</h1>
                 <h2 style="text-align:center; color:black;">رقم الفاتورة: ' . $order_id . '</h2>
                 <h2 style="text-align:center; color:black;">إجمالي المبلغ: $' . $total_amount . '</h2>
                 <div class="cart-done"><a href="index.php" class="cart-done-button"><br>الصفحة الرئيسية</a></div>
                 </center>';
           exit();

        }


    }

   
   



   





    
}
?>



<main>
<div class="container-cart1">
        <center>
            <h1 dir="rtl" id="cart-h1">اتمام الشراء</h1>
        </center>
        <?php 
        if (!empty($_SESSION['cart'])): 
            $total = 0; ?>
            <table border="1" width="100%" height="auto">
                <thead>
                    <tr>
                        <th class="cart-table-th">الاجمالي</th>
                        <th class="cart-table-th">السعر</th>
                        <th class="cart-table-th">الكميه</th>
                        <th class="cart-table-th">الاسم</th>
                    </tr>
                </thead>
                <?php
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $name = $item['name'];
                    $quan = $item['quantity'];
                    $price = $item['price'];
                    $total += $price * $quan;
                    echo "<tr align='center'>
                            <td class='cart-items'>$ $total</td>
                            <td class='cart-items'>$ $price</td>
                            <td class='cart-items'>$quan</td>
                            <td class='cart-items'>$name</td>
                          </tr>";
                }
                echo "<tr><td class='cart-total' colspan='4' align='center'>$ الاجمالي : $total</td></tr>";
                echo "</table><br><br>";
        else: 
            echo '<p id="empty-cart">السلة فارغة، يرجى اختيار منتجات.</p>';
        endif;
        ?>
    </div>


    <div class="payment-form">
        <h2>إتمام الدفع</h2>
        
        <?php 
          
          if(isset($errors['empts']))// empty blocks
          echo $errors['empts'];
          if(isset($errors['phone']))// phone number matching
          echo $errors['phone'];
          if(isset($errors['lename']))//name is smaller than 3 letters
          echo $errors['lename'];
          if(isset($errors['moname']))//name is greater than 70 letters
          echo $errors['moname'];
          if(isset($errors['leadd']))//address is less than 3 letters
          echo $errors['leadd'];
          if(isset($errors['moadd']))//address is greater than 70 letters
          echo $errors['moadd'];
          if(isset($errors['ltranid']))// transfer id is less than 3 letters
          echo $errors['ltranid'];
          if(isset($errors['mtranid']))// transfer id is more than 30 letters
          echo $errors['mtranid'];
          if(isset($errors['ltrannet']))// transfer network is less than 3 letters
          echo $errors['ltrannet'];
          if(isset($errors['mtrannet']))// transfer network is more than 30 letters
          echo $errors['mtrannet'];
        ?>
        <form method="post" action="">
            <label for="payment-method">اختر طريقة الدفع:</label>
            <select id="payment-method" name="payment-method" onchange="showPaymentFields()" required>
                <option value="">اختر...</option>
                <option value="cod">الدفع عند الاستلام</option>
                <option value="transfer">حواله محليه</option>
                <option value="wallet">الدفع من المحفظة الإلكترونية</option>
            </select>

             <!-- الحقول تظهر بناءً على طريقة الدفع -->
             <div id="cod-fields" style="display:none;">
                <h3>الدفع عند الاستلام</h3>
                <label for="full-name">الاسم الرباعي:</label>
                <input type="text" id="full-name" name="full-name" value="<?php if(isset($fullName)) echo $fullName; ?>">

                <label for="address">العنوان:</label>
                <input type="text" id="address" name="houseaddress" value="<?php if(isset($address)) echo $address; ?>">

                <label for="phone">رقم الهاتف:</label>
                <input type="tel" id="phone" name="personphone" value="<?php if(isset($phone)) echo $phone; else echo '967'; ?>" >
            </div>

            <!-- حواله محليه -->
            <div id="transfer-fields" style="display:none;">
                <h3>حواله محليه </h3>
                <p>عليك التحويل إلى اسمي: الوليد عبدالله بشر، ورقمي هو: 770411921</p>

                <label for="transfer-full-name">الاسم الرباعي (كما في الحوالة):</label>
                <input type="text" id="transfer-full-name" name="transfer-full-name" value="<?php if(isset($transferFullName)) echo $transferFullName; ?>">

                <label for="transfer-phone">رقم الهاتف:</label>
                <input type="tel" id="transfer-phone" name="transfer-phone" value="<?php if(isset($transferPhone)) echo $transferPhone; else echo '967'; ?>">

                <label for="transfer-address">العنوان:</label>
                <input type="text" id="transfer-address" name="transfer-address" value="<?php if(isset($transferAddress)) echo $transferAddress; ?>">

                <label for="transfer-id">رقم الحوالة:</label>
                <input type="text" id="transfer-id" name="transfer-id" value="<?php if(isset($transferId)) echo $transferId; ?>">

                <label for="transfer-network">شبكة التحويل:</label>
                <input type="text" id="transfer-network" name="transfer-network" value="<?php if(isset($transferNetwork)) echo $transferNetwork; ?>">
            </div>

            <!-- الدفع عبر المحفظة الإلكترونية -->
            <div id="wallet-fields" style="display:none;">
                <h3>الدفع من المحفظة الإلكترونية</h3>
                <p>عليك التحويل إلى الرقم: 770411921</p>

                <label for="wallet-full-name">الاسم الرباعي:</label>
                <input type="text" id="wallet-full-name" name="wallet-full-name" value="<?php if(isset($walletFullName)) echo $walletFullName; ?>">

                <label for="wallet-address">العنوان:</label>
                <input type="text" id="wallet-address" name="wallet-address" value="<?php if(isset($walletAddress)) echo $walletAddress; ?>">

                <label for="wallet-phone">رقم الهاتف:</label>
                <input type="tel" id="wallet-phone" name="wallet-phone" value="<?php if(isset($walletPhone)) echo $walletPhone; else echo '967'; ?>">

                <label for="wallet-type">نوع المحفظة:</label>
                <input type="text" id="wallet-type" name="wallet-type" value="<?php if(isset($walletType)) echo $walletType; ?>">

                <label for="wallet-transaction-id">رقم المعاملة:</label>
                <input type="text" id="wallet-transaction-id" name="wallet-transaction-id" value="<?php if(isset($walletTransactionId)) echo $walletTransactionId; ?>">
            </div>

            <!-- زر ارسال -->
            <button type="submit">إتمام الدفع</button>
        </form>
    </div> 

</main>

<?php
include "../M4_STORE/fotter.php";//header
?>
<script>
    function showPaymentFields() {
        var paymentMethod = document.getElementById("payment-method").value;
        document.getElementById("cod-fields").style.display = "none";
        document.getElementById("transfer-fields").style.display = "none";
        document.getElementById("wallet-fields").style.display = "none";
        
        if (paymentMethod === "cod") {
            document.getElementById("cod-fields").style.display = "block";
        } else if (paymentMethod === "transfer") {
            document.getElementById("transfer-fields").style.display = "block";
        } else if (paymentMethod === "wallet") {
            document.getElementById("wallet-fields").style.display = "block";
        }
    }
</script>
