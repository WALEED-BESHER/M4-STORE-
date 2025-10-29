<?php
include "../M4_STORE/header.php";//header
if(isset($_SESSION['user']))
{   header("Location:index.php");
   exit();
}
if ($_SERVER['REQUEST_METHOD']=='POST')
    { 
        $fname = isset($_POST['FIRST_NAME']) ? $_POST['FIRST_NAME'] : '';
        $lname = isset($_POST['LAST_NAME']) ? $_POST['LAST_NAME'] : '';
        $users = isset($_POST['USERNAME']) ? trim($_POST['USERNAME']) : '';
        $phone = isset($_POST['PHONE_NUMBER']) ? $_POST['PHONE_NUMBER'] : '';
        $email = isset($_POST['EMAIL']) ? trim($_POST['EMAIL']) : '';
        $pass = isset($_POST['PASSWORD']) ? trim($_POST['PASSWORD']) : '';
        $passhash = sha1($pass);

      
        $sqll = "select * from users where EMAIL =:em";
        $p = $con -> prepare($sqll);
        $p ->bindValue(':em' , $email);
        $p ->execute();
        
        $sqd = "select * from users where USERNAME =:us";
        $d = $con -> prepare($sqd);
        $d ->bindValue(':us' , $users);
        $d ->execute();
        if($p->rowcount())//اذا الايميل موجود
        {
           $row = $p->fetch();
           if($row["EMAIL"]=="$email")
           {
            $errors['emails']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> this email has ben taken try other one <br> </span>";
           }  
        }
        elseif($d->rowcount()) //اذا المستخدم موجود
        {
           $rows = $d->fetch();
           if($rows["USERNAME"]=="$users")
           {
            $errors['users']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> this user has ben taken try other one <br> </span>";
           }  
        }
       elseif (!preg_match("/(96777|96773|96771|96770)[0-9]{7}$/", $phone)) 
        {
          $errors['phone'] = "<span style='color:red; font-size:25px;'> رقم الهاتف غير صالح. يجب أن يبدأ بـ +967 أو 967 ويتبعه 9 أرقام <i class='fa-solid fa-ban'></i></span><br>";
        }
        elseif(empty($fname)) //EMPTY FIRST NAME
            $errors['firstname']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i>ENTER YOUR FIRST NAME <br> </span>";
        elseif(empty($users))//EMPTY USERNAME
            $errors['username']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i>ENTER YOUR USERNAME <br> </span>";
        elseif(empty($email)) //EMPTY EMAIL
            $errors['email']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i>ENTER YOUR EMAIL <br> </span>";
        elseif(empty($pass)) //EMPTY PASS 
         $errors['pass']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i>ENTER YOUR password <br> </span>";
        elseif(strlen($fname)<3) //FIRST NAME LESS THAN 3 LETERS
         $errors['lename']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> FIRST NAME SHOULD BE MORE THAN 3 LETTERS <br> </span>";
        elseif(strlen($fname)>20) //FIRST NAME MORE THAN 20 LETERS
         $errors['moname']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> FIRST NAME SHOULD BE less THAN 20 LETTERS <br> </span>";   
        elseif(strlen($users)<3) //USER  LESS THAN 3 LETERS
         $errors['leuser']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> USER SHOULD BE MORE THAN 3 LETTERS <br> </span>";
        elseif(strlen($users)>30) //USER  MORE THAN 25 LETERS
         $errors['mouser']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> USER SHOULD BE LESS THAN 30 LETTERS <br> </span>";   
        elseif(strlen($email)>40) ////email is long 
          $errors['longemail']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> email is so long <br> </span>";
        elseif(strlen($pass)<3) ////pass is short
          $errors['shortpass']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> password is short <br> </span>";
        elseif(strlen($pass)>30) ////pass is long
         $errors['longpass']= "<span style='color:red; font-size:25px; position:center;'><i class='fa-solid fa-ban'></i> password is so long <br> </span>"; 

      else{
              $sql= "insert into users (FIRST_NAME ,LAST_NAME ,USERNAME , PHONE,EMAIL,PASSWORD)
              values(:fname, :lname, :user, :phone, :email, :pass)";
              $q = $con->prepare($sql);
              $q ->bindValue(':fname' , $fname);
              $q ->bindValue(':lname' , $lname);
              $q ->bindValue(':user' , $users);
              $q ->bindValue(':phone' , $phone);
              $q ->bindValue(':email' , $email);
              $q ->bindValue(':pass' , $passhash);
              $q ->execute();
              header("Location:login.php");
              exit();
          }
    }

?>
    <!-- main -->
    <main>
        <section id="container-of-signup">
            <div class="wraping">
                <form method="post" action="">
                    <h1 id="title-of-signup">SIGNUP</h1>
                    <?php //رسايل الاخطاء
                        if(isset( $errors['emails']))//email is taken
                            echo $errors['emails'];
                        if(isset( $errors['users']))//user is taken
                            echo $errors['users'];
                        if(isset( $errors['phone']))//phone number doesnot match
                            echo $errors['phone'];
                        if(isset($errors['firstname']))//EMPTY FIRST NAME              
                            echo $errors['firstname'];             
                        if(isset($errors['username']))//EMPTY USERNAME
                            echo $errors['username'];
                        if(isset($errors['email']))//EMPTY EMAIL
                            echo $errors['email'];
                        if(isset($errors['pass']))  //EMPTY PASS 
                        echo $errors['pass'];
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
                        <input type="text"  required  placeholder="FIRST NAME" name="FIRST_NAME" class="signup-inputs" value="<?php if(isset($fname)) echo $fname; ?>">
                        <input type="text" placeholder="LAST NAME (option)" name="LAST_NAME" class="signup-inputs" value="<?php if(isset($lname)) echo $lname; ?>">
                    </div>
        
                    <div class="input-box-signup"> <!--USERNAME-->
                        <input type="text" required placeholder="USERNAME" name="USERNAME" class="signup-inputs" value="<?php if(isset($users)) echo $users; ?>">
                    </div>
                    <div class="input-box-signup"> <!--PHONE NUMBER-->
                        <input type="tel" required placeholder="PHONE NUMBER" name="PHONE_NUMBER" class="signup-inputs" value="<?php if(isset($phone)) echo "$phone"; else echo '967'; ?>">
                    </div>
                    <div class="input-box-signup"> <!--EMAIL-->
                        <input type="email" required placeholder="EMAIL" name="EMAIL" class="signup-inputs" value="<?php if(isset($email)) echo $email; ?>">
                    </div>
                
                    <div class="input-box-signup"> <!--PASS-->
                        <input type="password" required placeholder="PASSWORD" name="PASSWORD" class="signup-inputs" value="<?php if(isset($pass)) echo $pass; ?>">
                    </div>
        
                    <input type="submit" class="btnn" name="send" value="SIGN UP"></input> <!--SIGNUP-->
        
                    <div class="registor-linkos">
                        <p id="signup-para"><a href="../M4_STORE/login.php" id="signup-registor-link">يوجد لدي حساب سابق</a></p> <!--OLD ACCOUNT-->
                    </div>
                </form>
            </div>
        </section>    




    </main>


    <?php include "../M4_STORE/fotter.php";//fotter ?>