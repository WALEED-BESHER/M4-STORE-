<?php 
 include "header.php";
 require "../connection.php";
 if( !isset($_SESSION['user']) or  $_SESSION['Role']!="admin")
   {   header("Location:../login.php");
      exit();
    }
    $id=$_GET['id'];
    
    if($_SERVER["REQUEST_METHOD"] == "POST" )
    {
        $names=$_POST["title"];
        $price=$_POST["price"];
        $oldprice = $_POST["oldprice"];
        $img=$_FILES["img"] ["name"];
        $tmp = $_FILES["img"] ["tmp_name"];
        $folderss="../photos/".$img;
        $des = $_POST["describe"];
        $type = $_POST["TYPE"];
        $weight = $_POST["WEIGHT"];
        $made = $_POST["MADE"];

        if(!empty($_FILES["img"]["name"])) {
            $img = $_FILES["img"]["name"];
            $tmp = $_FILES["img"]["tmp_name"];
            $folderss = "../photos/".$img;
            move_uploaded_file($tmp, $folderss);
            
            $sql = "UPDATE products SET TITLE=:name, PRICE=:price, OLD_PRICE=:old, IMAGE=:img, 
                    DESCRIPTION=:des, TYPE=:type, WEIGHT=:weight, MADE=:made WHERE ID=:id";
             $q = $con->prepare($sql);
             $q->bindValue(':name' , $names);
             $q->bindValue(':price' , $price);
             $q->bindValue(':old' , $oldprice);
             if(!empty($_FILES["img"]["name"])) {
                 $q->bindValue(':img', $img);
             }
             $q->bindValue(':des' , $des);
             $q->bindValue(':type' , $type);
             $q->bindValue(':weight' , $weight);
             $q->bindValue(':made' , $made);
             $q->bindValue(':id' , $id);
             if($q->execute())
             {
                 move_uploaded_file($tmp,$folderss);
                 echo "<script>alert('EDIT SUCCESSFULLAY') </script>";   
                 header("Location: posts.php");
             }else {
                 echo "<h3 class='alert alert-danger'> failed editing </h3>";
             }
        } else {
            // إذا لم يتم تحميل صورة جديدة، لا تقم بتحديث حقل الصورة
            $sql = "UPDATE products SET TITLE=:name, PRICE=:price, OLD_PRICE=:old, 
                    DESCRIPTION=:des, TYPE=:type, WEIGHT=:weight, MADE=:made WHERE ID=:id";
             $q = $con->prepare($sql);
             $q->bindValue(':name' , $names);
             $q->bindValue(':price' , $price);
             $q->bindValue(':old' , $oldprice);
             if(!empty($_FILES["img"]["name"])) {
                 $q->bindValue(':img', $img);
             }
             $q->bindValue(':des' , $des);
             $q->bindValue(':type' , $type);
             $q->bindValue(':weight' , $weight);
             $q->bindValue(':made' , $made);
             $q->bindValue(':id' , $id);
             if($q->execute())
             {
                 move_uploaded_file($tmp,$folderss);
                 echo "<script>alert('EDIT SUCCESSFULLAY') </script>";   
                 header("Location: posts.php");
             }else {
                 echo "<h3 class='alert alert-danger'> failed editing </h3>";
             }
        }
       
    }
        
    $sq="select * from products where ID=:iid";
    $z = $con->prepare($sq);
    $z->bindValue(':iid' , $id);
    $z->execute();
    if($z->rowcount()==1)
    {
        $row= $z->fetch();
    }


?>
 
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class='fa fa-tasks'></i> EDIT PRODUCTS</h1>
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
        
				<h3 class="text-center"><i class='fa fa-plus-circle'></i> EDIT PRODUCTS</h3>
       
        <div class="panel-body">   
        
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-file-signature"></i>
				</span>
				<input type="text" class="form-control" name="title" placeholder="Title" 
				value="<?php if(isset($row)) echo $row['TITLE'] ?>" 
                />				
			</div>
		</div>

        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-regular fa-money-bill-1"></i>
				</span>
				<input type="text" class="form-control" name="price" placeholder="New Price"
				value="<?php if(isset($row)) echo $row['PRICE']?>"/>				
			</div>
		</div>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i>
				</span>
				<input type="text" class="form-control" name="oldprice" placeholder="Old Price"
				value="<?php if(isset($row)) echo $row['OLD_PRICE']?>"/>				
			</div>
		</div>
		
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-regular fa-image"></i></span>
				
				<input type="file" class="form-control" name="img" placeholder="img"
                  value="<?php if(isset($row)) echo $row['IMAGE'] ?>"
				 />				
			</div>
		</div>

        <input type="hidden" name="cid" value="<?php if(isset($row)) echo $idd; ?>"/>
		
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-scroll"></i></span>
				<textarea name="describe" placeholder="DESCRIBE" class="form-control"><?php if(isset($row)) echo $row['DESCRIPTION']?></textarea>
			</div>
		</div>


		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-i-cursor"></i></span>
				<textarea name="TYPE" placeholder="TYPE" class="form-control"><?php if(isset($row)) echo $row['TYPE']?></textarea>
			</div>
		</div>


		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-weight-scale"></i></span>
				<textarea name="WEIGHT" placeholder="WEIGHT" class="form-control"><?php if(isset($row)) echo $row['WEIGHT']?></textarea>
			</div>
		</div>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa-solid fa-plane-departure"></i></span>
				<textarea name="MADE" placeholder="MADE" class="form-control"><?php if(isset($row)) echo $row['MADE']?></textarea>
			</div>
		</div>
		
		</div>
		
			<input class="btn btn-lg btn-primary btn-block" type="submit" value='Save' name='save'>
      </div>
       </div>
        </div>
    </div>
</div>
</form>
</div> 
   
    
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