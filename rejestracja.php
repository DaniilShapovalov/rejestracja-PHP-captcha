<?php
session_start();

if (isset($_POST['email'])) {
    // Валидируем данные
    $wszystko_OK = true;

    // Проверка никнейма
    $nick = $_POST['nick'];
    if ((strlen($nick) < 3) || (strlen($nick) > 25)) {
        $wszystko_OK = false;
        $_SESSION['e_nick'] = "Username must be at least 3 characters!";
    }

    // Проверка e-mail
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Enter correct e-mail!";
    }

    // Проверка пароля
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if ((strlen($password1) < 8) || (strlen($password1) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_password'] = "Your password must be between 8-20 characters";
    }

    if ($password1 != $password2) {
        $wszystko_OK = false;
        $_SESSION['e_password'] = "Passwords must match";
    }

    // Подключение к базе данных
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            // Проверка уникальности e-mail
            $stmt = $polaczenie->prepare("SELECT id FROM uzytkownicy WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $wszystko_OK = false;
                $_SESSION['e_email'] = "This e-mail is already used";
            }

            // Проверка уникальности никнейма
            $stmt = $polaczenie->prepare("SELECT id FROM uzytkownicy WHERE user=?");
            $stmt->bind_param("s", $nick);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $wszystko_OK = false;
                $_SESSION['e_nick'] = "This nickname is already used";
            }

            // Если всё ок, добавляем пользователя
            if ($wszystko_OK == true) {
                $password_hash = password_hash($password1, PASSWORD_DEFAULT);
                $stmt = $polaczenie->prepare("INSERT INTO uzytkownicy (user, password, email, points, gold, wood, stone) VALUES (?, ?, ?, 100, 100, 100, 14)");
                $stmt->bind_param("sss", $nick, $password_hash, $email);
                if ($stmt->execute()) {
                    $_SESSION['SUCCESSFUL_REGISTRATION'] = true;
                    header('Location: witamy.php');
                    exit();
                } else {
                    throw new Exception($polaczenie->error);
                }
            }

            $polaczenie->close();
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Error! Server is not responding!</span>';
        // echo '<br>Information for developers: ' . $e;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css?v=<?php echo time(); ?>">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <title>Osadnicy Registration</title>
</head>

<body>
    <div id="container">
        <form class="form1" method="post">
            Nickname: <br><input type="text" name="nick"><br>
            <?php if (isset($_SESSION['e_nick'])) {
                echo '<div class="error">' . $_SESSION['e_nick'] . '</div>';
                unset($_SESSION['e_nick']);
            } ?>
            E-mail: <br><input type="text" name="email"><br>
            <?php if (isset($_SESSION['e_email'])) {
                echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                unset($_SESSION['e_email']);
            } ?>
            Password: <br><input type="password" name="password1"><br>
            <?php if (isset($_SESSION['e_password'])) {
                echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
                unset($_SESSION['e_password']);
            } ?>
            Repeat your password: <br><input type="password" name="password2"><br>

            <label><input type="checkbox" name="regulamin">I accept the terms and conditions</label><br>
            <?php if (isset($_SESSION['e_regulamin'])) {
                echo '<div class="error">' . $_SESSION['e_regulamin'] . '</div>';
                unset($_SESSION['e_regulamin']);
            } ?>

            <div class="g-recaptcha" data-sitekey="6LcxekUmAAAAAD-ZzQx_CQaHie5igLOP8ZtZDPO4"></div>
            <br>
            <input type="submit" value="Create account">
        </form>
    </div>
</body>

</html>