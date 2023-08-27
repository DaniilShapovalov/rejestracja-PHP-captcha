<?php

    session_start();

    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
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
    <title>Osadnicy Login</title>
    <style>

    </style>
</head>
<body>
    
    <div id="container">

        

        <form action="osadnicy.php" method="post">

            
            <input name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'" type="text">


            
            <input name="password" placeholder="password" onfocus="this.placeholder=''" onblur="this.placeholder='password'" type="password">


            <input type="submit" value="Log in" class="buttonLogIn"> 

        <br><br>

        </form>
        <a class="registrationLink" href="rejestracja.php"> Registration - create account</a>
        <br>
<?php 
    if(isset($_SESSION['blad'])) echo $_SESSION['blad'];
?>
    </div>
   <img class="image-center" width="350px" src="img/users.jpg"></div>


    
</body>
</html>