<?php
include "../M4_STORE/header.php";

// التحقق من وجود رسالة تحذير في الجلسة
if(isset($_SESSION['login_alert'])) {
    $alert_message = $_SESSION['login_alert'];
    unset($_SESSION['login_alert']);
}

// التحقق من وجود معرف المنتج
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];

// جلب بيانات المنتج من قاعدة البيانات
$sql = "SELECT * FROM products WHERE ID = :id";
$q = $con->prepare($sql);
$q->bindValue(':id', $product_id);
$q->execute();

if($q->rowCount() == 0) {
    header("Location: products.php");
    exit();
}

$product = $q->fetch();
?>

<main>
    <!-- عرض تحذير تسجيل الدخول إذا وجد -->
    <?php if(isset($alert_message)): ?>
        <div id="loginAlert" class="login-alert">
            <?php echo $alert_message; ?>
        </div>
    <?php endif; ?>

    <div class="product-details-container">
        <div class="product-images">
            <img src="photos/<?php echo $product['IMAGE']; ?>" alt="<?php echo $product['TITLE']; ?>" class="main-image">
            <!-- يمكن إضافة صور إضافية هنا -->
        </div>
        
        <div class="product-info">
            <h1 dir="rtl" id="product-det-h1"><?php echo $product['TITLE']; ?></h1>
            
            <div class="price-section">
                <span class="newprice"><?php echo $product['PRICE']; ?>$</span>
                <?php if($product['OLD_PRICE'] > 0): ?>
                    <span class="old-price"><?php echo $product['OLD_PRICE']; ?>$</span>
                <?php endif; ?>
            </div>
            
            <div class="description" dir="rtl">
                <h3 id="product-det-h3">وصف المنتج:</h3>
                <p><?php echo nl2br($product['DESCRIPTION']); ?></p>
            </div>
            
            <div class="specs" dir="rtl">
                <h3 id="product-det-h3">مواصفات المنتج:</h3>
                <ul id="product-det-ul">
                    <li class="product-det-li"><strong class="product-det-li-strong">* النوع :</strong> <?php echo $product['TYPE']; ?></li>
                    <li class="product-det-li"><strong class="product-det-li-strong">* الوزن :</strong> <?php echo $product['WEIGHT']; ?></li>
                    <li class="product-det-li"><strong class="product-det-li-strong">* بلاد التصنيع :</strong> <?php echo $product['MADE']; ?> </li>
                    <!-- يمكن إضافة المزيد من المواصفات -->
                </ul>
            </div>
            
            <div class="actions">
                <a href="add_to_cart.php?id=<?php echo $product['ID']; ?>" 
                   class="add-to-cart"
                   onclick="<?php if(!isset($_SESSION['user'])) { echo 'event.preventDefault(); showLoginAlert();'; } ?>">
                   إضافة إلى السلة
                </a>
                <a href="product.php" class="back-to-products">العودة إلى المنتجات</a>
            </div>
        </div>
    </div>
</main>

<script>
    // عرض تحذير تسجيل الدخول
    function showLoginAlert() {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'login-alert';
        alertDiv.innerHTML = 'يرجى تسجيل الدخول أولاً لتتمكن من إضافة المنتجات إلى السلة';
        document.body.appendChild(alertDiv);
        
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

<?php include "../M4_STORE/fotter.php"; ?>