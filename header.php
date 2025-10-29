<?php
session_start();
require "../M4_STORE/Connection.php";


// الحصول على عدد الإشعارات غير المقروءة
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $query = "SELECT COUNT(*) FROM notifications WHERE USER_ID =:user_id AND is_read=0";
  $stmt = $con->prepare($query);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $unread_notifications = $stmt->fetchColumn();
} else {
  $unread_notifications = 0;
}

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M4_STORE</title>
    <link rel="icon" href="../M4_STORE/photos/LOGO.png">
    <link rel="stylesheet" href="../M4_STORE/css/master.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav>
      <button onclick="toggleSidebar()" style="background: transparent; border: 0;"><i class="fa-solid fa-bars" style="color:red; background-color: transparent;"></i></button> <!-- menu -->
      <img src="../M4_STORE/photos/LOGO.png" alt="" id="logo"> M4_STORE <!--logo-->
      <ul id="maincontainer"> <!-- login icon and page profile and logout-->
        <?php
          if(isset($_SESSION['user']))
          {
            $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
            echo " <li id='mainlists'><a href='cart.php' id='link'><i class='fa-solid fa-cart-shopping'></i><span><sup>$cart_count</sup></span></a> </li> 
            <li id='mainlists'><a href='#' id='link'><i class='fas fa-user'></i>"; if ($unread_notifications > 0): echo "<span><sup style='color:red;'>●</sup></span>";  endif; echo "</a>
              <ul class='dropdown'>
                <li class='fractionlist'><a href='../M4_STORE/profile.php' id='link'><i class='fa-regular fa-user' id='userlog'></i> PROFILE</a></li>
                <li class='fractionlist'><a href='../M4_STORE/notification.php' id='link'><i class='fa-solid fa-bell' id='userlog'></i>";  if ($unread_notifications > 0): echo "<span id='con-noti'><sup id='supp'>$unread_notifications </sup></span>"; endif; echo " NOTIFI</a></li>
                <li class='fractionlist'><a href='../M4_STORE/logout.php' id='link'><i class='fas fa-sign-out-alt' id='userlog'></i> LOGOUT</a></li>
                </ul>                                  
               </li> ";
          } 
          else
          {
              echo "
              <li id='mainlists'><a href='../M4_STORE/login.php' id='link' style='box-shadow: 0px 0px 5px rgb(206, 79, 79);'>LOGIN</a></li>
              ";
          }
        ?>
                 
      </ul>    
    </nav>
   <!-- aside -->
    <aside id="sidebar"> <!--القواىم-->
        <h1 id="menu">MENU</h1><br>
        <ul id="ul">
          <li class="list"><a href="../M4_STORE/index.php" class="menu">الصفحه الريسيه</a></li> <br>
          <li class="list"><a href="../M4_STORE/product.php" class="menu">منتجاتنا </a></li> <br>
          <li class="list"><a href="../M4_STORE/about_us.php" class="menu">من نحن</a></li> <br>
          <li class="list"><a href="../M4_STORE/contact_us.php" class="menu">اتصل بنا</a></li> <br>
          <li class='list'>
              <?php 
                $ice=isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                $checkingsql = "select * from users where ID=:id";
                $chg = $con->prepare($checkingsql);
                $chg->bindValue(':id' , $ice);
                $chg->execute();
                $chs=$chg->fetch();  
                if( isset($_SESSION['user_id']) and $chs["DELIVERY"]==1)
                {  
                   echo "<a href='../M4_STORE/cp/delivery.php' class='menu'>delivery</a>";
                 }
              ?>
          </li><br>
          
          <?php 
               if(isset($_SESSION['user_id']) and $_SESSION['Role']=="admin")
               {  
                  echo " <li class='list'><a href='../M4_STORE/cp/dashboard.php' class='menu'>admin</a></li> <br>";
                }
          ?>
        </ul> 
    </aside>

    <script>
      const sidebar = document.getElementById('sidebar');
      function toggleSidebar()
      {
          sidebar.classList.toggle('show')
      }
    </script>

</body>
</html>