<?php
include "../M4_STORE/header.php";
$ip = $_SERVER['REMOTE_ADDR'];

// دالة لفحص إذا كان الـ IP محظور
function is_ip_banned($con, $ip) {
    $stmt = $con->prepare("SELECT ban_until FROM login_attempts WHERE ip_address = :ip");
    $stmt->execute(['ip' => $ip]);
    $row = $stmt->fetch();
    if ($row && $row['ban_until']) {
        return strtotime($row['ban_until']) > time();
    }
    return false;
}

// منع دخول من لديه جلسة
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// منع دخول من IP محظور
if (is_ip_banned($con, $ip)) {
    die("<script>alert('تم حظر هذا الـ IP مؤقتًا بسبب محاولات فاشلة متكررة. حاول لاحقًا.')</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "<script>alert('يجب إدخال اسم المستخدم وكلمة المرور')</script>";
    } else {
        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);
        $passhash = sha1($pass);

        $sql = "SELECT * FROM users WHERE (USERNAME = :name OR EMAIL = :name) AND PASSWORD = :pass AND ACTIVE = 1";
        $q = $con->prepare($sql);
        $q->bindValue(':name', $user);
        $q->bindValue(':pass', $passhash);
        $q->execute();

        if ($q->rowCount()) {
            $row = $q->fetch();

            if ($row["ACTIVE"] == 1) {
                // تصفير المحاولات
                $stmt = $con->prepare("DELETE FROM login_attempts WHERE ip_address = :ip");
                $stmt->execute(['ip' => $ip]);

                setcookie("user_id", $row["ID"], time() + 60 * 60 * 24);
                $_SESSION['user_id'] = $row["ID"];

                if ($row["Role"] == "admin") {
                    setcookie("user", $_POST['username'], time() + 60 * 60 * 24);
                    $_SESSION['user'] = $_POST['username'];
                    $_SESSION['Role'] = "admin";
                } else {
                    setcookie("user", $_POST['username'], time() + 60 * 60 * 24);
                    $_SESSION['user'] = $_POST['username'];
                    $_SESSION['Role'] = "user";
                }
                header("location:index.php");
                exit();
            }
        } else {
            echo "<script>alert('البريد الإلكتروني / اسم المستخدم أو كلمة المرور غير صحيحة')</script>";

            // التعامل مع المحاولات الفاشلة
            $stmt = $con->prepare("SELECT * FROM login_attempts WHERE ip_address = :ip");
            $stmt->execute(['ip' => $ip]);
            $attempt = $stmt->fetch();

            if ($attempt) {
                $new_attempts = $attempt['attempts'] + 1;
                $ban_level = $attempt['ban_level'];
                $ban_until = null;

                if ($new_attempts > 10) {
                    if ($ban_level == 0) {
                        $ban_until = date("Y-m-d H:i:s", strtotime("+1 day"));
                        $ban_level = 1;
                    } elseif ($ban_level == 1) {
                        $ban_until = date("Y-m-d H:i:s", strtotime("+4 days"));
                        $ban_level = 2;
                    } elseif ($ban_level == 2) {
                        die("<script>alert('تمت محاولة دخول متكررة! سيتم مراجعة الحساب.')</script>");
                        // هنا يمكنك تنفيذ التبنيد أو الإبلاغ عن IP
                    }
                }

                $stmt = $con->prepare("UPDATE login_attempts SET attempts = :attempts, ban_until = :ban_until, ban_level = :ban_level WHERE ip_address = :ip");
                $stmt->execute([
                    'attempts' => $new_attempts,
                    'ban_until' => $ban_until,
                    'ban_level' => $ban_level,
                    'ip' => $ip
                ]);
            } else {
                $stmt = $con->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (:ip, NOW())");
                $stmt->execute(['ip' => $ip]);
            }
        }
    }
}
?>

<!-- واجهة تسجيل الدخول -->
<main>
  <section id="container-of-login">
    <div class="wra">
      <form method="post" action="">
        <h1 id="title-of-login">LOGIN</h1>
        <div class="input-box">
          <input type="text" required placeholder="email/username" name="username" class="login-inputs">
          <i class="fa-solid fa-user" id="login-icons"></i>
        </div>
        <div class="input-box">
          <input type="password" required placeholder="password" name="password" class="login-inputs">
          <i class="fa-solid fa-lock" id="login-icons"></i>
        </div>
        <div class="remember-forget">
          <label id="login-label">
            <input type="checkbox" id="login-label-inputs" name="remember_me">REMEMBER ME
          </label>
          <a href="#" id="login-remember-link" dir="rtl">هل نسيت كلمة السر؟</a>
        </div>
        <button type="submit" class="btn">LOGIN</button>
        <div class="registor-link">
          <p id="login-para"><a href="../M4_STORE/signup.php" id="login-registor-link">إنشاء حساب جديد</a></p>
        </div>
      </form>
    </div>
  </section>
</main>

<?php include "../M4_STORE/fotter.php"; ?>
