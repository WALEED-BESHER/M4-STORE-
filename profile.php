<?php
include "../M4_STORE/header.php";
if(!isset($_SESSION['user']))
{   header("Location:index.php");
   exit();
}
?>


<!-- main -->
<main>

<div class="container-of-profile">
          <div id="box-of-profile">
              <h1 id="profile-h1"> <i class='fa-solid fa-user' id="profile-icon"></i></h1>

                <?php 
                $id = $_SESSION['user_id'];
                $sql = "select * from users where ID =:id";
                $q = $con -> prepare($sql);
                $q ->bindValue(':id' , $id);
                $q ->execute();
                while( $row= $q->fetch() )
                {
                    echo " <div class='profile-output-boxs'>
                        <pre id='proff'> FIRST NAME: " .$row["FIRST_NAME"]. "</pre>
                        <pre id='proff'>LAST NAME: ".$row["LAST_NAME"] ." </pre>                  
                    </div>";

                    echo "<div class='profile-output-boxs'>
                        <pre id='prof'>USERNAME:".$row["USERNAME"] . "</pre>                
                    </div>"; 
                    
                    echo "<div class='profile-output-boxs'>
                        <pre id='prof'>PHONE NUMBER: " . $row["PHONE"] . "</pre>                
                    </div>"; 
                    echo "<div class='profile-output-boxs'>
                        <pre id='prof'>EMAIL: " . $row["EMAIL"] . "</pre>                
                    </div>"; 
                                 
                }               
                ?>
              
              <div class="profile-output-boxs">
                 <a href="../M4_STORE/edit_profile.php" id="profile-to-edit">EDIT PROFILE</a>                  
              </div>
          </div>  
      </div>
        
</main>   


<!-- FOTTER -->
<?php include "../M4_STORE/fotter.php"; ?>