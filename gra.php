<?php

    session_start();
    if (!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
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
    <title>Osadnicy Gra</title>
</head>
<body>
    


<?php
  
    echo "<p>Hello ".$_SESSION['user'].'! [<a href="logout.php">Log out!</a>]</p>';
    echo "<p><b>Wood</b>: ".$_SESSION['drewno'];
    echo " | <b>Rock</b>: ".$_SESSION['kamien'];
    echo " | <b>Corn</b>: ".$_SESSION['zboze'];
    
    echo "<p><b>E-mail</b>: ".$_SESSION['email'];
    echo "<br><b>premium expired after</b>: ".$_SESSION['dnipremium']. "<b> days<b>"."</p>";



?>
    
</body>
</html>