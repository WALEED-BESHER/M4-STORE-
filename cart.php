<?php
include "../M4_STORE/header.php";//header

if( !isset($_SESSION['user']) )
{   header("Location:login.php");
   exit();
 }

if(isset($_GET['action'],$_GET['id']) and intval($_GET['id'])>0)
{
  if($_GET['action']=='delete')
  {
    unset($_SESSION['cart']);
  }


  if (isset($_GET['action'], $_GET['id']) && isset($_SESSION['cart'][$_GET['id']]))
  {
    $product_id = $_GET['id'];
    if ($_GET['action'] == 'increase') {
        $_SESSION['cart'][$product_id]['quantity']++;
    } elseif ($_GET['action'] == 'decrease' && $_SESSION['cart'][$product_id]['quantity'] > 1) {
        $_SESSION['cart'][$product_id]['quantity']--;
    }
     elseif ($_GET['action'] == 'deletepro') {
       unset($_SESSION['cart'][$product_id]);
    }
  }
}
?>


<main>
    <div class="container-cart">
    <center>
        <h1 dir="rtl" id="cart-h1">سلة التسوق</h1>
    </center>
    <?php 
      if (!empty($_SESSION['cart'])): 
          $total = 0;?>
          <table border="1" width="100%" height="auto">
           <thead >
             <tr>
                 <th class="cart-table-th">التحكم</th>
                 <th class="cart-table-th">الاجمالي</th>
                 <th class="cart-table-th">السعر</th>
                 <th class="cart-table-th">الكميه</th>
                 <th class="cart-table-th">الاسم </th>
             </tr>
           </thead><br>

          <?php
          foreach ($_SESSION['cart'] as $product_id => $item) {
            $name=$item['name'];
            $quan= $item['quantity'];
            $price=$item['price'];
            $total_item = $quan * $price;
            $total += $price * $quan;
            echo "<tr align='center'>";
            echo "<td class='cart-items'>
            <a href='?action=deletepro&id=$product_id' class='cart-bottons' ><i class='fa-solid fa-trash'></i></a>
            <a href='?action=decrease&id=$product_id' class='cart-bottons'>-</a>
            <a href='?action=increase&id=$product_id' class='cart-bottons'>+</a>
            </td>";
            echo "<td class='cart-items'>$ $total_item </td>";
            echo "<td class='cart-items'>$ $price </td>";
            echo "<td class='cart-items'> $quan </td>";
            echo "<td class='cart-items'> $name </td>";
            echo "</tr>";
          }
          echo "<tr>";
          echo '<td class="cart-total" colspan="5" align="center">$ الاجمالي : ' . $total . ' </td>';
          echo "</tr>";
          echo "</table><br><br>";
          $id=$_SESSION['user_id'];
          ?>
          <center>
            <div class="cart-done"><br>
             <a href="checkout.php" class="cart-done-button">إتمام الشراء</a>
           </div><br>
            <div class="cart-done"><br>
             <a href="?action=delete&id=<?php echo "$id"; ?>" class="cart-done-button" onclick="return confirm('هل انت متاكد انك تريد تفريغ السله')"> تفريغ السله</a>
           </div>
          </center>

          
          <?php
        else: 
          echo '<p id="empty-cart">السلة فارغة <br><br> يرجى اختيار منتجات
           <center>
          <div class="cart-done"><br>
          <a href="../M4_STORE/index.php" class="cart-done-button"> العوده الى الصفحه الريسيه</a>
          </div>
          </center>
          </p>';
        endif;
    ?>
    </div>

</main>

<?php include "../M4_STORE/fotter.php";//fotter ?>