<?php 
include "../M4_STORE/Connection.php";
session_start();
if( !isset($_SESSION['user']) )
{   header("Location:login.php");
   exit();
 }
$product_id = $_GET['id'];
$sql = "select * from products where ID =:id";
$q = $con->prepare($sql);
$q ->bindValue(':id' , $product_id);
$q ->execute();
$product = $q->fetch();

if ($product) {
   
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
   
    if (isset($_SESSION['cart'][$product['ID']])) {
        $_SESSION['cart'][$product['ID']]['quantity']++;
    } else {
        $_SESSION['cart'][$product['ID']] = [
            'name' => $product['TITLE'],
            'price' => $product['PRICE'],
            'quantity' => 1
        ];
    }

}
header("Location: product.php");
exit();
?>
