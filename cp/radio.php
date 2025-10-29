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

 if($_SERVER["REQUEST_METHOD"] == "POST" )
 {
    $massage=$_POST["massage"];
    $sql="INSERT INTO notifications (USER_ID, MASSAGE, is_read, created_at,STATUSS)
     SELECT ID, '$massage', 0, CURRENT_TIMESTAMP,'public'
     FROM users;";
    $q = $con->prepare($sql);
    $q->execute();
 }

    if(isset($_GET['action'],$_GET['id']) and intval($_GET['id'])>0)
    {
        if($_GET['action']=='delete') //delete button
        {
           $sql = "DELETE FROM notifications WHERE MASSAGE = (SELECT MASSAGE FROM notifications WHERE ID = :id)";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));
        }
    }

    
// إحصاء عدد الذين شاهدوا وعدد الذين لم يشاهدوا الإشعار
$sql_stats = "SELECT 
(SELECT COUNT(*) FROM notifications WHERE is_read = 1 AND STATUSS = 'public') AS seen_count,
(SELECT COUNT(*) FROM notifications WHERE is_read = 0 AND STATUSS = 'public') AS not_seen_count,
(SELECT COUNT(*) FROM users) AS total_users";  // إجمالي عدد المستخدمين
$q_stats = $con->prepare($sql_stats);
$q_stats->execute();
$stats = $q_stats->fetch(PDO::FETCH_ASSOC);
$seen_count = $stats['seen_count'];
$not_seen_count = $stats['not_seen_count'];
$total_users = $stats['total_users'];

// حساب النسب المئوية
$seen_percentage = $total_users > 0 ? ($seen_count / $total_users) * 100 : 0;
$not_seen_percentage = $total_users > 0 ? ($not_seen_count / $total_users) * 100 : 0;
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa-solid fa-microphone-lines"></i> RADIO PAGE</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
	
	
	
 <div id="fullscreen_bg" class="fullscreen_bg"/>
 <form class="form-signin" method="post" enctype="multipart/form-data">
	<div class="container" style='width:970px'>
    <div class="row">
        <div class="col-12-sx">
        <div class="panel panel-default">
        <div class="panel panel-primary">
        
				<h3 class="text-center"><i class="fa-brands fa-adversal"></i> Add New AD</h3>
       
        <div class="panel-body">   
        
		<div class="form-group">
			
		
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-file-signature"></i></span>
				<textarea name="massage" placeholder="PUT YOUR MASSAGE HER " class="form-control"></textarea>
			</div>
		</div>
			<input class="btn btn-lg btn-primary btn-block" type="submit" value='SEND' name='send'>
      </div>
       </div>
        </div>
    </div>
</div>
</form>
</div> 
   
    <div class="row">
        <div class="col-xs-12">           
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-tasks fa-fw"></i> ADS
                    <div class="pull-right">
                        
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" >
                                    <thead >
										<tr>
											<th>MASSAGE</th>
											<th>ADD DATE</th>
											<th>SEEN</th>
											<th>NOT SEEN</th>
                                            <th>Seen %</th>
                                            <th>Not Seen %</th>
										    <th colspan='' style='text-align:center'>Actions</th>
                                     </tr>
                                    </thead>
                                    <tbody>
                                        
                                    <?php 
                                     $sql = "SELECT ID, MASSAGE, MIN(created_at) AS created_at, STATUSS FROM notifications WHERE STATUSS = 'public' GROUP BY MASSAGE";
                                     $q = $con->prepare($sql);
                                     $q->execute();
                                     if($q->rowcount())
                                     {
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $title = $row["MASSAGE"];
                                            $stat = $row["STATUSS"];
                                            $adddate = $row["created_at"];

                                             // إحصاء عدد الذين شاهدوا وعدد الذين لم يشاهدوا الرسالة
                                             $sql_seen = "SELECT COUNT(*) FROM notifications WHERE MASSAGE = :massage AND is_read = 1";
                                             $q_seen = $con->prepare($sql_seen);
                                             $q_seen->execute(['massage' => $title]);
                                             $seen_count = $q_seen->fetchColumn();

                                             $sql_not_seen = "SELECT COUNT(*) FROM notifications WHERE MASSAGE = :massage AND is_read = 0";
                                             $q_not_seen = $con->prepare($sql_not_seen);
                                             $q_not_seen->execute(['massage' => $title]);
                                             $not_seen_count = $q_not_seen->fetchColumn();

                                               // حساب النسب المئوية
                                               $seen_percentage = $total_users > 0 ? ($seen_count / $total_users) * 100 : 0;
                                               $not_seen_percentage = $total_users > 0 ? ($not_seen_count / $total_users) * 100 : 0;


                                            echo "<tr>";
                                            echo "<td> $title</td>";
                                            echo "<td> $adddate</td>";
                                            echo "<td>$seen_count</td>";
                                            echo "<td>$not_seen_count</td>";
                                            echo "<td>" . number_format($seen_percentage, 2) . "%</td>";
                                            echo "<td>" . number_format($not_seen_percentage, 2) . "%</td>";

                                                echo "<td>";
                                                echo "<a href='?action=delete&id=$id' class='btn btn-danger'  title='delete' onclick=\"return confirm('are you sure')\" >delete </a> ";
                                                echo"</td>";
                                            echo "</tr>";
                                        }
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