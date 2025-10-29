<?php
include "../M4_STORE/header.php";//header
if(isset($_SESSION['user_id']))
{
    $user_id = $_SESSION['user_id'];
    // تحديث حالة جميع الإشعارات إلى مقروءة (is_read = 1)
    $update_query = "UPDATE notifications SET is_read = 1 WHERE USER_ID =:user_id AND is_read =0";
    $stmt = $con->prepare($update_query);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
}
else
{   header("Location:login.php");
   exit();
 }

 if(isset($_GET['action'],$_GET['id']) and intval($_GET['id'])>0)
 {
    if($_GET['action']=='delete')
    {
        $sql="delete from notifications where ID=:id";
        $q= $con->prepare($sql);
        $q->execute(array("id"=>$_GET['id']));

    }
    header("Location: notification.php");
    exit();
 }
?>

<main>
    <div class="far">
        <center>
        <h1 dir="rtl" class="pas">متجر M4_STORE لبيع جميع انواع الاسلحه المتوسطه والخفيفه</h1>
        <h1 dir="rtl" class="pas"><i class="fas fa-bell"></i> الاشعارات</h1>
        </center><br><br>
    </div>

    <?php 
     $iid=$_SESSION['user_id'];
     $sql = "SELECT * FROM notifications where USER_ID=:id ORDER BY ID DESC";
     $q=$con->prepare($sql);
     $q->bindValue(':id' , $iid);
     $q ->execute();
     if($q->rowcount())
     {
        $result = $q->fetchall();
        foreach($result as $row)
        {
            $id=$row["ID"];
            $massage=$row["MASSAGE"];
            $created_at=$row["created_at"];

            echo "<div class='container-of-notif'>";
            echo "<pre id='notification-massage' dir='rtl'>$massage</pre>";
            echo "<p id='notification-create' dir='rtl'>$created_at</p>";
            echo "<a href='?action=delete&id=$id' class='delfromnotif' title='delete' onclick=\"return confirm('are you sure')\" >delete </a> ";
            echo "</div>";
        }
     }
    
    ?>   
</main>

<?php
include "../M4_STORE/fotter.php";//header
?>
