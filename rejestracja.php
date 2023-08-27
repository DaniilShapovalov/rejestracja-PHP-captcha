<?php
session_start();

if (isset($_POST['email'])) {
    //Udana walidacja true
    $wszystko_OK = true;

    //Spradz poprawnosc nickname`a
    $nick = $_POST['nick'];

    //sprawdzenie Dlugosci
    if ((strlen($nick) < 3) || (strlen($nick) > 25)) {
        $wszystko_OK = false;
        $_SESSION['e_nick'] = "Username must be at least 3 characters!";
    }

    //validate for captcha
    $secret = "6LcxekUmAAAAAPpmv8oXaJzhVyrnojBU2cLmoPqb";
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);

    $email = $_POST['email'];

    //email check
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Enter correct e-mail!";
    }

    $odpowiedz = json_decode($sprawdz);

    if ($odpowiedz->success == false) {
        $wszystko_OK = false;
        $_SESSION['e_bot'] = "Captcha false";
    }

    require_once "connect.php";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

            if (!$rezultat) throw new Exception($polaczenie->error);

            $ile_takich_maili = $rezultat->num_rows;
            if ($ile_takich_maili > 0) {
                $wszystko_OK = false;
                $_SESSION['e_email'] = "This e-mail already used";
            }

            //user
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

            if (!$rezultat) throw new Exception($polaczenie->error);

            $ile_takich_nickow = $rezultat->num_rows;
            if ($ile_takich_nickow > 0) 
            {
                $wszystko_OK = false;
                $_SESSION['e_nick'] = "This nickname already used";
            }
    // Проверяет, все ли символы в переданной строке text являются буквенно-цифровыми.
    if (ctype_alnum($nick) == false) {
        $wszystko_OK = false;
        $_SESSION['e_nick'] = "Username can only contain digits or letters(without Polish characters)";
    }

    //check password
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if ((strlen($password1) < 8) || (strlen($password1) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_password'] = "Your password must be between 8-20 characters";
    }

    if ($password1 != $password2) {
        $wszystko_OK = false;
        $_SESSION['e_password'] = "Password must match";
    }


     //hashowanie hasla
     $password_hash = password_hash($password1, PASSWORD_DEFAULT);
     if ($wszystko_OK==true)
     {
         //add player to db
         if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL,'$nick', '$password_hash', '$email', 100,
          100, 100, 14)"))
         {
             $_SESSION['SUCCESSFUL_REGISTRATION']=true;
             header('Location: witamy.php');
             exit();
         }   
         else
         {
             throw new Exception($polaczenie->error);
         }
     }
 
     $polaczenie->close();
 }
} catch (Exception $e) {
 echo '<span style="color:red;">Error! Server is not responding!</span>';
 // echo '<br>Information to developers: '.$e;
}
    //regulamin checked
    if (!isset($_POST['regulamin'])) {
        $wszystko_OK = false;
        $_SESSION['e_regulamin'] = "Confirm the terms and conditions";
    }

    if ($wszystko_OK == true) {
        //add user to database
        echo "Successful validation";
        exit();
    }
}

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
    header('Location: gra.php');
    exit();
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
    <style>

    </style>
</head>

<body>
    <div id="container">
        <form class="form1" method="post">
            Nickname: <br><input style="margin: auto;" type="text" name="nick"><br>
            <?php
            if (isset($_SESSION['e_nick'])) {
                echo '<div class="error">' . $_SESSION['e_nick'] . '</div>';
                unset($_SESSION['e_nick']);
            }
            ?>
            E-mail: <br><input style="margin: auto;" type="text" name="email"><br>
            <?php
            if (isset($_SESSION['e_email'])) {
                echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                unset($_SESSION['e_email']);
            }
            ?>
            Password: <br><input style="margin: auto;" type="password" name="password1"><br>
            <?php
            if (isset($_SESSION['e_password'])) {
                echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
                unset($_SESSION['e_password']);
            }
            ?>
            Repeat your password: <br><input style="margin: auto;" type="password" name="password2"><br>

            <label style="display: inline-block; padding-top: 15px;">
                <input type="checkbox" name="regulamin">I accept the terms and conditions
            </label><br><?php
                        if (isset($_SESSION['e_regulamin'])) {
                            echo '<div class="error">' . $_SESSION['e_regulamin'] . '</div>';
                            unset($_SESSION['e_regulamin']);
                        }
                        ?>

            <div class="g-recaptcha" data-sitekey="6LcxekUmAAAAAD-ZzQx_CQaHie5igLOP8ZtZDPO4">\</div>
            <?php
            if (isset($_SESSION['e_bot'])) {
                echo '<div class="error">' . $_SESSION['e_bot'] . '</div>';
                unset($_SESSION['e_bot']);
            }
            ?>
            <br>
            <input type="submit" value="Create account">
        </form>
    </div>
</body>

</html>