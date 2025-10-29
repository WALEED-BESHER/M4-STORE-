<?php
include "../M4_STORE/header.php";//header


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if(empty($name) || empty($phone) || empty($email) || empty($message))
    {
      $errors['emp']="<span style='color:red; font-size:25px;'>الرجاء تعبئة جميع الحقول.</span><br>";
    }
    elseif(!preg_match("/(96777|96773|96771|96770)[0-9]{7}$/",$phone))
  {
    $errors['phone'] = "<span style='color:red; font-size:25px;'>* رقم الهاتف غير صالح. يجب أن يبدأ بـ  967 ويتبعه 9 أرقام.</span><br>";
  }
  elseif(strlen($name)<3 ||strlen($message)<3)
  {
    $errors['lessthan']="<span style='color:red; font-size:25px;'> يجب ان يزيد عدد احرف الاسم او الرساله عن 3 احرف</span><br>";
  }
  elseif(strlen($name)>50)
  {
    $errors['longname']="<span style='color:red; font-size:25px;'>الاسم طويل جدا يجب ان يكون اقل من 50حرف </span><br>"; 
  }
  else{
    // استقبال البيانات من النموذج
    

    // إعداد البريد الإلكتروني
    $to = "m4store75@gmail.com"; // البريد الإلكتروني الخاص بالمتجر
    $subject = "رسالة جديدة من  $name";
    $body = "الاسم : $name \nرقم الهاتف : $phone\nالايميل : $email\nالرساله: $message";
    
    mail($to, $subject, $body);#ارسال الى البريد الاكتروني

    $sql = "INSERT INTO messages (NAME , PHONE , EMAIL, MESSAGE) VALUES (:name, :phone , :email, :message)";
    $q = $con->prepare($sql);
    $q->bindValue(':name' , $name);
    $q->bindValue(':phone' , $phone);
    $q->bindValue(':email' , $email);
    $q->bindValue(':message' , $message);
    $q->execute();
    echo "<script>alert('تم الارسال بنجاح ')</script>";
  }  

}

?>
    <main id="main-contactus">
        <div class="far"> <!--TITLES-->
            <center>
            <h1 dir="rtl" class="pas">متجر M4_STORE لبيع جميع انواع الاسلحه المتوسطه والخفيفه</h1>
            <h1 dir="rtl" class="pas"> اتصل بنا</h1>
            </center><br><br>
        </div>

        <section class="contact">
            <div class="container_of_contactus">
              <h2 id="contact-id">Contact Us</h2>
              <?php  
               if(isset($errors['emp']))// empty blanks
               echo $errors['emp'];
               if(isset($errors['phone']))// phone number matching
               echo $errors['phone'];
               if(isset($errors['lessthan']))// massage and name less than 3 letters
               echo $errors['lessthan'];
               if(isset($errors['longname']))// name more than 50 letters
               echo $errors['longname'];              
              ?>
              <div class="contact-wrapper">
                <div class="contact-form">
                    <h3 id="contact-id1">Send us a message</h3>
                   <form method="POST">
                      <div class="form-group">
                       <input type="text" required name="name" placeholder="Your name" id="contact-name-input"/>
                      </div>
                      <div class="form-group">
                       <input type="text" required name="phone" placeholder="Your phone number" id="contact-name-input"  value="967"/>
                      </div>
                    
                      <div class="form-group">
                        <input type="email" required name="email" placeholder="Your Email" id="contact-email-input"/>
                      </div>
                    
                      <div class="form-group">
                        <textarea name="message" required placeholder="Your Message" id="contact-massage-text"></textarea>
                      </div>
                      <button type-"submit" id="contact-submet">Send Message</button>
                   </form>
               </div>
               <div class="contact-info">
                  <h3 id="contact-id1">Information</h3>
                  <p id="contact-para"><a href="tel:+967770411921"><i class="fa-solid fa-phone" id="contact-icon"> +967 770411921</i></a></p><br>
                  <p id="contact-para"> <a href="mailto:M4-STORE@GMAIL.COM"><i class="fa-regular fa-envelope" id="contact-icon"> Email:M4-STORE@GMAIL.COM</i></a></p> <br>
                  <p id="contact-para"><i class="fa-solid fa-location-dot" id="contact-icon"> اليمن صنعاءعطان</i></p>
                </div>
            
              </div>
          </div>
      </section>


    </main>
    <?php include "../M4_STORE/fotter.php";//fotter ?>