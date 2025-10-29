<?php
 include "../M4_STORE/header.php";
 if(!isset($_SESSION['user']))
{   header("Location:index.php");
   exit();
}

$pho = $_POST["PHONE_NUMBER"];
$id = $_SESSION['user_id'];
$sql = "select * from users where ID =:id";
$q = $con -> prepare($sql);
$q ->bindValue(':id' , $id);
$q ->execute();
$row = $q->fetch();

if($_SERVER["REQUEST_METHOD"] == "POST" )
{

    if (!preg_match("/(96777|96778|96773|96771|96770)[0-9]{7}$/", $pho)) //phone number match
    {
      $errors['phone'] = "<span style='color:red; font-size:25px;'>* رقم الهاتف غير صالح. يجب أن يبدأ بـ 967 ويتبعه 9 أرقام.</span><br>";
    }
    elseif(empty($_POST["PASSWORD"])) //EMPTY PASS 
    $errors['pass']= "<span style='color:red; font-size:20px; position:center;'>*ENTER YOUR password <br> </span>";
    elseif(strlen($_POST["FIRST_NAME"])<3) //FIRST NAME LESS THAN 3 LETERS
    $errors['lename']= "<span style='color:red; font-size:20px; position:center;'>* FIRST NAME SHOULD BE MORE THAN 3 LETTERS <br> </span>";
    elseif(strlen($_POST["FIRST_NAME"])>20) //FIRST NAME MORE THAN 20 LETERS
    $errors['moname']= "<span style='color:red; font-size:20px; position:center;'>* FIRST NAME SHOULD BE less THAN 20 LETTERS <br> </span>";   
    elseif(strlen($_POST["USERNAME"])<3) //USER  LESS THAN 3 LETERS
    $errors['leuser']= "<span style='color:red; font-size:20px; position:center;'>* USER SHOULD BE MORE THAN 3 LETTERS <br> </span>";
    elseif(strlen($_POST["USERNAME"])>30) //USER  MORE THAN 25 LETERS
    $errors['mouser']= "<span style='color:red; font-size:20px; position:center;'>* USER SHOULD BE LESS THAN 30 LETTERS <br> </span>";   
    elseif(strlen($_POST["EMAIL"])>40) ////email is long 
    $errors['longemail']= "<span style='color:red; font-size:20px; position:center;'>* email is so long <br> </span>";
    elseif(strlen($_POST["PASSWORD"])<3) ////pass is short
    $errors['shortpass']= "<span style='color:red; font-size:20px; position:center;'>* password is short <br> </span>";
    elseif(strlen($_POST["PASSWORD"])>30) ////pass is long
   $errors['longpass']= "<span style='color:red; font-size:20px; position:center;'>* password is so long <br> </span>"; 
    else{
        $fname = $_POST['FIRST_NAME'];
        $lname = $_POST['LAST_NAME'];
        $username = $_POST['USERNAME'];
        $phone = $_POST['PHONE_NUMBER'];
        $email = $_POST['EMAIL'];
        $pass =  $_POST['PASSWORD'];
        $passhash = sha1($pass);
    
        $update_sql = "UPDATE users SET FIRST_NAME = :first_name, LAST_NAME = :last_name, USERNAME = :username, PHONE = :phone, EMAIL = :email, PASSWORD = :pass WHERE ID = :id";
        $update_st = $con->prepare($update_sql);
        $update_st->bindValue(':first_name', $fname);
        $update_st->bindValue(':last_name', $lname);
        $update_st->bindValue(':username', $username);
        $update_st->bindValue(':phone', $phone);
        $update_st->bindValue(':email', $email);
        $update_st->bindValue(':pass', $passhash);
        $update_st->bindValue(':id', $id);
    
        if ($update_st->execute()) {
            echo "<script>alert('EDIT SUCCESSFULLAY') </script>";
            header("Location: profile.php"); // إعادة التوجيه إلى صفحة الملف الشخصي بعد التحديث
            exit();
    
        } else {
            echo "<h3 class='alert alert-danger'> failed editing profile</h3>";
        }
    
    }



   
}

?>


    <!-- MAIN -->
    <main>
      <section id="container-of-signup">
            <div class="wraping">
                <form method="post" action="" enctype="multipart/form-data">
                    <h1 id="title-of-signup">EDIT PROFILE PAGE</h1>
                    <p style="color:red; font-size:1em; text-align:right;"> لاكمال هذه الخطوه عليك ان تكتب كلمه السر السابقه او الجديده#</p>
                    <?php
                    if(isset($errors['pass']))  //EMPTY PASS 
                    echo $errors['pass'];
                    if(isset( $errors['phone']))//phone number doesnot match
                    echo $errors['phone'];
                    if(isset($errors['lename']))//FIRST NAME LESS THAN 3 LETERS
                    echo $errors['lename'];
                    if(isset($errors['moname']))//FIRST NAME MORE THAN 20 LETERS
                        echo $errors['moname'];
                    if(isset($errors['leuser']))//USER LESS THAN 3 LETERS
                        echo $errors['leuser'];
                    if(isset($errors['mouser']))//USER more THAN 25 LETERS
                        echo $errors['mouser'];
                    if(isset($errors['longemail']))//email is long 
                        echo $errors['longemail']; 
                    if(isset($errors['shortpass']))//pass is short
                        echo $errors['shortpass'];
                    if(isset($errors['longpass']))//pass is long
                        echo $errors['longpass'];    
                    ?>
                   
                    <div class="input-box-signup"> <!-- names-->
                        <input type="text" required placeholder="FIRST NAME" name="FIRST_NAME" class="signup-inputs"  value="<?php  if(isset($row)) echo $row['FIRST_NAME'] ?>">
                        <input type="text" placeholder="LAST NAME" name="LAST_NAME" class="signup-inputs" value="<?php  if(isset($row)) echo $row['LAST_NAME'] ?>">
                    </div>

                    <div class="input-box-signup"> <!--USERNAME-->
                        <input type="text" required  placeholder="USERNAME" name="USERNAME" class="signup-inputs" value="<?php  if(isset($row)) echo $row['USERNAME'] ?>">
                    </div>
                    <div class="input-box-signup"> <!--PHONE NUMBER-->
                        <input type="tel" required  placeholder="PHONE NUMBER" name="PHONE_NUMBER" class="signup-inputs" value="<?php  if(isset($row)) echo $row['PHONE'] ?>">
                    </div>
                    <div class="input-box-signup"> <!--EMAIL-->
                        <input type="email" required placeholder="EMAIL" name="EMAIL" class="signup-inputs" value="<?php  if(isset($row)) echo $row['EMAIL'] ?>">
                    </div>

                   <div class="input-box-signup"> <!--PASS-->
                     <input type="password" required placeholder="PASSWORD" name="PASSWORD" class="signup-inputs" >
                  </div>
                  <input type="submit" class="btnn" name="send" value="EDIT"></input> <!--EDIT-->
        
                </form>
            </div>
      </section>
    </main>
<?php
 include "../M4_STORE/fotter.php"; ?>