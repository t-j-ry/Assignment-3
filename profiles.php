<?php
require 'includes/functions.php';
$message = '';
session_start();

if(!isset($_SESSION['loggedin']))
{
    header('Location: index.php');
    exit();
}

if(count($_FILES) > 0)
{
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
        saveProfile($_SESSION['username'], $_FILES);
    }
}

if(isset($_POST['submit-password'])){

    $_POST['username'] = $_SESSION['username'];

    if (findUser($_POST['username'], $_POST['curent-password'])) {
        if (checkSignUp($_POST) && updateUser($_POST)) {
            $message = '
            <div class="alert alert-danger text-center">
                Password has been updated!
            </div>
            ';
        }
    }
    

}


$profiles = getAllProfiles();
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
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">
                    COMP 3015 Assignment 2
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <hr/>
                <?php echo $message; ?>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i> New Profile</button>
                <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
                <hr/>
            </div>
        </div>

        <div class="row">
            <?php
            foreach($profiles as $profile)
            {
                echo '
                    <div class="col-md-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>
                                    '.$profile['username'].'
                                </span>
                ';

                if($profile['username'] == $_SESSION['username'] || $_SESSION['admin'])
                {
                    echo '
                                <span class="pull-right text-muted">'.editProfile($_SESSION['admin'], $profile['username'], $profile['id']).'
                                    <a class="" href="delete.php?id='.$profile['id'].adminDelete($_SESSION['admin'], $profile['username']).'">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </span>
                            ';
                }

                echo '
                            </div>
                            <div class="panel-body">
                                <p class="text-muted">
                                </p>
                                <img class="img-thumbnail" src="profiles/'.$profile['picture'].'"/>
                            </div>
                            <div class="panel-footer">
                                <p>'.isUser($profile['username']).'</p>
                            </div>
                        </div>
                    </div>
                ';
            }
            ?>

        </div>

    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="profiles.php" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Profile</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" value="<?php echo $_SESSION['username'];?>" disabled" disabled>
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input class="form-control" type="file" name="picture">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Submit!"/>
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Password change model -->
<div id="changePassword" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="profiles.php">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <label>Current Password</label>
                    <input class="form-control" type="password" name="curent-password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" type="password" name="password">
                </div>
                <div class="form-group">
                    <label>Verify Password</label>
                    <input class="form-control" type="password" name="verify_password">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Submit!" name="submit-password" />
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
