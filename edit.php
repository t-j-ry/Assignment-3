<?php 

session_start();
require 'includes/functions.php';

if(!isset($_SESSION['admin']))
{
    header('Location: index.php');
    exit();
}

if (isset($_POST['change-picture'])) {

    $check = checkPost($_FILES);

    if($check !== true)
    {
        $message = '
        <div class="alert alert-danger text-center">
            '. $check .'
        </div>
        ';
    }
    else
    {
        updateProfile($_FILES, $_POST['username'], $_POST['id']);
        header('Location: profiles.php');
        exit();

    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    <div class="container">

        <div class="row">
        <?php
        
        if (isset($_GET['id'])) {
            
            $username = $_GET['username'];
            $id = $_GET['id'];
            
            echo '
            <div>
                <div>
                    <form role="form" method="post" action="edit.php" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">New Profile</h4>
                            </div>
                            <div class="modal-body">
                                    <div class="form-group">
                                        <label>Id</label>
                                        <input class="form-control" name="id" value="'.$id.'" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" name="username" value="'.$username.'" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        <input class="form-control" type="file" name="picture">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-default" href="profiles.php" ">Close</a>
                                <input type="submit" class="btn btn-primary" value="Submit!" name="change-picture" />
                            </div>
                        </div><!-- /.modal-content -->
                    </form>
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        ';
        }
        ?>
   

    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>