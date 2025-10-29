<?php
include "../M4_STORE/header.php"; //header

// التحقق من وجود رسالة تحذير في الجلسة
if(isset($_SESSION['login_alert'])) {
    $alert_message = $_SESSION['login_alert'];
    unset($_SESSION['login_alert']); // إزالة الرسالة بعد عرضها
}

$sql ="select * from products order by ID desc limit 6";
$q = $con->prepare($sql);
$q->execute();
if($q->rowcount()):
  $rows= $q->fetchall();
?>
    <!-- main -->
     <main>
            <!-- عرض تحذير تسجيل الدخول إذا وجد -->
            <?php if(isset($alert_message)): ?>
                <div id="loginAlert" class="login-alert">
                    <?php echo $alert_message; ?>
                </div>
            <?php endif; ?>

            <div class="far"> <!--TITLES-->
        <center>
        <h1 dir="rtl" class="pas">متجر M4_STORE لبيع جميع انواع الاسلحه المتوسطه والخفيفه</h1>
        <h1 dir="rtl" class="pas">الصفحه الرىيسه</h1>
        </center>
      </div>
      <div>
        <p dir="rtl" id="pub">اشهر منتجاتنا:</p>
     </div>

     <div class="continer">
            <?php 
              foreach($rows as $row):
              $id = $row["ID"];
              $title = $row["TITLE"];
              $price = $row["PRICE"];
              $img = $row["IMAGE"];
              $adddate = $row["AddDate"]; ?>
              <div class='mainbox'>
               <a href="product_details.php?id=<?php echo $id; ?>">
                <img src='photos/<?php echo "$img";?>' alt='' class='pictures'>
              </a>
                <p dir='rtl' class='title'><?php echo "$title";?></p>
                <pre dir='rtl' class='price'>السعر: <?php echo "$price";?>$</pre>
                <a class="order" href="add_to_cart.php?id=<?php echo $id;?>" 
                   onclick="<?php if(!isset($_SESSION['user'])) { echo 'event.preventDefault(); showLoginAlert();'; } ?>">
                   إضافة إلى السلة
                </a>
              </div>
              <?php
                endforeach;
                endif;         
              ?>
            </div>
     </main>

<script>
    // عرض تحذير تسجيل الدخول
    function showLoginAlert() {
        // إنشاء عنصر التحذير
        const alertDiv = document.createElement('div');
        alertDiv.className = 'login-alert';
        alertDiv.innerHTML = 'يرجى تسجيل الدخول أولاً لتتمكن من إضافة المنتجات إلى السلة';
        document.body.appendChild(alertDiv);
        
        // إخفاء التحذير بعد 5 ثواني
        setTimeout(() => {
            alertDiv.style.animation = 'fadeOut 0.5s';
            setTimeout(() => {
                alertDiv.remove();
            }, 500);
        }, 5000);
    }

    // إذا كان هناك تحذير من الجلسة، إخفائه بعد 5 ثواني
    document.addEventListener('DOMContentLoaded', function() {
        const loginAlert = document.getElementById('loginAlert');
        if (loginAlert) {
            setTimeout(() => {
                loginAlert.style.animation = 'fadeOut 0.5s';
                setTimeout(() => {
                    loginAlert.remove();
                }, 500);
            }, 5000);
        }
    });
</script>

<?php include "../M4_STORE/fotter.php"; //footer ?>