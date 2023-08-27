<?php

    session_start();

    if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
    {
        header('Location: index.php');
        exit(); 
    }

    require_once "connect.php";

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($polaczenie->connect_errno!=0)
    {
        echo "error: ".$polaczenie->connect_errno;
        // . "Opis". $polaczenie->connect_error;
    }
    else
    {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $login = htmlentities($login, ENT_QUOTES, "UTF-8");

        if ($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM uzytkownicy WHERE user='%s'", 
        mysqli_real_escape_string($polaczenie,$login))))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0)
            { 
                $wiersz = $rezultat->fetch_assoc();
            
                if (password_verify($password, $wiersz['pass']))
            {
                $_SESSION['zalogowany'] = true;

                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['user'] = $wiersz['user'];
                $_SESSION['drewno'] = $wiersz['drewno'];
                $_SESSION['kamien'] = $wiersz['kamien'];
                $_SESSION['zboze'] = $wiersz['zboze'];
                $_SESSION['email'] = $wiersz['email'];
                $_SESSION['dnipremium'] = $wiersz['dnipremium'];

                unset($_SESSION['blad']);
                $rezultat->free_result();
                header('Location: gra.php');
            }
            else 
            {
                
            $_SESSION['blad'] = '<span class="spanError">Nieprawidlowy login lub haslo!</span>';
            header('Location: index.php');
            }

            } else
                {
            $_SESSION['blad'] = '<span class="spanError">Nieprawidlowy login lub haslo!</span>';
            header('Location: index.php');
                }
            }

        $polaczenie->close();
    }

    $login = $_POST['login'];
    $password = $_POST['password'];

    // echo $login."<br>";
    // echo $password;
    
?>