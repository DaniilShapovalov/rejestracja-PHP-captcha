<?php

    session_start();

    if (isset($_SESSION['SUCCESSFUL_REGISTRATION']))
    {
        header('Location: index.php');
        exit();
    }
    else
    {
        unset($_SESSION['SUCCESSFUL_REGISTRATION']);
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
    Thank you for your registration! Now you can enter to your account!<br><br>
     <a href="index.php">Log in to your account!</a>
    
   <img class="image-center" width="350px" src="img/users.jpg"></div>


    
</body>
</html>