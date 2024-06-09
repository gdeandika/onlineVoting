<?php 
    session_start();
    require_once("config.php");

    if($_SESSION['key'] != "AdminKey")
    {
        echo "<script> location.assign('logout.php'); </script>";
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Voting Online</title>
    <!--Bootsrap-->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!--Bootsrap-->

    <!--Fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet"/>
    <!--Fonts-->

    <!--Style-->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!--Style-->
</head>
<body>
    
    <div class="container-fluid">
        <div class="row bg-secondary text-white">
            <div class="col-6 p-3"> 
                <img src="../assets/images/header.png" width="150px"/>
            </div>
            <div class="col-6 my-auto">
                <h4> ONLINE VOTING SYSTEM  - <small> Welcome  <?php echo $_SESSION['username']; ?></small> </h4>
            </div>
        </div>

 





