<?php 
 include "header.php";
 require "../connection.php";
 if( !isset($_SESSION['user']) or  $_SESSION['Role']!="admin")
   {   header("Location:../login.php");
      exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" )
    {
        $name = $_FILES["img"] ["name"];
        $tmp = $_FILES["img"] ["tmp_name"];
        if(move_uploaded_file($tmp,"../photos/$name"))
        {
            $sql ="insert into products(TITLE,PRICE,OLD_PRICE,IMAGE,DESCRIPTION,TYPE,WEIGHT,MADE) values(:title,:price,:old,:img,:des,:type,:weight,:made)";
            $q = $con->prepare($sql);
            $q->execute(array("title"=>$_POST["title"],"price"=>$_POST["price"],"old"=>$_POST["oldprice"],"img"=>$name,"des"=>$_POST["describe"],"type"=>$_POST["TYPE"],"weight"=>$_POST["WEIGHT"],"made"=>$_POST["MADE"]));
            if($q->rowcount())
            {
                echo "<h3 class='alert alert-success'  style='text-align: center;'> post has been added </h3>";
                
            }
            else{
                echo "<h3 class='alert alert-danger'  style='text-align: center;'> post has`t been added </h3>";
                
            }
            
        }
        header("Location: posts.php");
        exit();
    }

    ////////////////////////////////

    
    if(isset($_GET['action'],$_GET['id']) and intval($_GET['id'])>0)
    {
        if($_GET['action']=='delete') //delete button
        {
            $sql="delete from products where ID=:id";
            $q= $con->prepare($sql);
            $q->execute(array("id"=>$_GET['id']));

            if($q->rowcount()==1)
            echo "<script>alert('DELETED SUCCESSFULLAY') </script>";
            else
            echo "<script>alert('coudent delete') </script>";
        }

    }
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class='fa fa-tasks'></i> Products</h1>
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
        
				<h3 class="text-center"><i class='fa fa-plus-circle'></i> Add New Product</h3>
       
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

        <input type="hidden" name="cid" value="<?php if(isset($row)) echo $row['ID']?>"/>
		
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
		
			<input class="btn btn-lg btn-primary btn-block" type="submit" value='Save' name='save'>
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
                    <i class="fa fa-tasks fa-fw"></i> Product
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
											<tr >
											<th>#</th>
											<th>TITLE</th>
										    <th >PRICE</th>
										    <th >OLD_PRICE</th>
										    <th >IMG</th>
										    <th >DESCRIPTION</th>
										    <th >TYPE</th>
										    <th >WEIGHT</th>
										    <th >MADE</th>
										    <th >Date</th>
										    <th colspan='' style='text-align:center'>Actions</th>
                                     </tr>
                                    </thead>
                                    <tbody>
                                        
                                    <?php 
                                     $sql = "select * from products order by ID desc";
                                     $q = $con->prepare($sql);
                                     $q->execute();
                                     if($q->rowcount())
                                     {
                                        $result = $q->fetchall();
                                        foreach($result as $row)
                                        {
                                            $id = $row["ID"];
                                            $title = $row["TITLE"];
                                            $price = $row["PRICE"];
                                            $oldprice = $row["OLD_PRICE"];
                                            $img = $row["IMAGE"];
                                            $des = $row["DESCRIPTION"];
                                            $type = $row["TYPE"];
                                            $weight = $row["WEIGHT"];
                                            $made = $row["MADE"];
                                            $adddate = $row["AddDate"];

                                            echo "<tr>";
                                                echo "<td>$id</td>";
                                                echo "<td> $title</td>";
                                                echo "<td>$price</td>";
                                                echo "<td>$oldprice</td>";
                                                echo "<td> $img</td>";
                                                echo "<td> $des</td>";
                                                echo "<td> $type</td>";
                                                echo "<td> $weight</td>";
                                                echo "<td> $made</td>";
                                                echo "<td> $adddate</td>";

                                                echo "<td>";
                                               
                                                echo "<a href='edit_posts.php?id=$id' class='btn btn-success' title='tadmin'> <i class='fa fa-edit'></i> Edit </a> ";
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