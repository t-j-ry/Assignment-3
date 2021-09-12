<?php
define('SALT', 'a_very_random_salt_for_this_app');
define('FILE_SIZE_LIMIT', 4000000);

define('DB_HOST',     '127.0.0.1');
define('DB_PORT',     '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'comp3015');

function connect()
{
    $link = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    if (!$link)
    {
        echo mysqli_connect_error();
        exit;
    }

    return $link;
}

/**
 * Look up the user & password pair from the database.
 *
 * Passwords are simple md5 hashed, but salted.
 *
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $user string The username to look up
 * @param $pass string The password to look up
 * @return bool true if found, false if not
 */
function findUser($user, $pass)
{
    $found = false;

    $link = connect();
    $hash = md5($pass . SALT);

    $query   = 'select * from users where username = "'.$user.'" and password = "'.$hash.'"';
    $results = mysqli_query($link, $query);

    if (mysqli_fetch_array($results))
    {
        $found = true;
    }

    mysqli_close($link);
    return $found;
}

/**
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $data
 * @return bool
 */
function saveUser($data)
{
    $username   = trim($data['username']);
    $password   = md5($data['password']. SALT);

    $link    = connect();
    $query   = 'insert into users(username, password) values("'.$username.'","'.$password.'")';
    $success = mysqli_query($link, $query); // returns true on insert statements

    mysqli_close($link);
    return $success;
}

function checkUsername($username)
{
    return preg_match('/^([a-z]|[0-9]){8,15}$/i', $username);
}

/**
 * @param $data
 * @return bool
 */
function checkSignUp($data)
{
    $valid = true;

    // if any of the fields are missing
    if( trim($data['username'])        == '' ||
        trim($data['password'])        == '' ||
        trim($data['verify_password']) == '')
    {
        $valid = false;
    }
    elseif(!checkUsername(trim($data['username'])))
    {
        $valid = false;
    }
    elseif(!preg_match('/((?=.*[a-z])(?=.*[0-9])(?=.*[!?|@])){8}/', trim($data['password'])))
    {
        $valid = false;
    }
    elseif($data['password'] != $data['verify_password'])
    {
        $valid = false;
    }

    return $valid;
}

function filterUserName($name)
{
    // if it's not alphanumeric, replace it with an empty string
    return preg_replace("/[^a-z0-9]/i", '', $name);
}

/**
 * @param $file
 * @return bool
 */
function checkPost($file)
{
    if($file['picture']['size'] < FILE_SIZE_LIMIT && $file['picture']['type'] == 'image/jpeg')
    {
        return true;
    }

    return 'Unable to upload profile picture!';
}

/**
 * @param $username
 * @param $file
 * @return bool
 */
function saveProfile($username, $file)
{
    $picture = md5($username.time());
    $moved   = move_uploaded_file($file['picture']['tmp_name'], 'profiles/'.$picture);

    if($moved)
    {
        $link   = connect();
        $query  = 'insert into profiles(username, picture) values("'.$username.'","'.$picture.'")';
        $result = mysqli_query($link, $query);

        mysqli_close($link);
        return $result;
    }

    return false;
}

/**
 * @return bool|mysqli_result
 */
function getAllProfiles()
{
    $link     = connect();
    $query    = 'select * from profiles order by username';
    $profiles = mysqli_query($link, $query);

    mysqli_close($link);
    return $profiles;
}

/**
 * Delete a profile based on the ID and username combination
 *
 * @param $id
 * @param $username
 * @return bool returns true on deletion success or false on failure
 */
function deleteProfile($id, $username)
{
    $link    = connect();
    $query   = 'delete from profiles where id = "'.$id.'" and username = "'.$username.'"';
    $success = mysqli_query($link, $query);

    mysqli_close($link);
    return $success;
}

function isAdmin($adminUsername, $adminPassword) {
    $lines = file("admin.ini");

        foreach($lines as $line) {
            $pieces = preg_split("/,/", $line);
            echo $pieces[0]." ";
            echo $pieces[1];
            echo "<br/>";
            if ($pieces[0] == $adminUsername && trim($pieces[1]) == $adminPassword) {
                return true;
            }
        }
}

function isUser($profileUsername) {

    if ($profileUsername == $_SESSION['username']) {
        return '<button class="btn btn-default" data-toggle="modal" data-target="#changePassword">Change Password</button>';
    }
        
    return '';

}

function editProfile($admin, $profileUsername, $profileId) {
    if ($admin) {
        return '<a class="btn btn-default btn-sm" href="edit.php?id='.$profileId.'&username='.$profileUsername.'">Edit</a>';
    }

    return '';
}

function adminDelete($admin, $profileUsername) {

    if ($admin) {
        return '&username='.$profileUsername;
    }

    return '';
}

function updateProfile($file, $username, $id)
{

    $picture = md5($username.time());
    $moved   = move_uploaded_file($file['picture']['tmp_name'], 'profiles/'.$picture);

    if($moved)
    {
        $link   = connect();
        $query  = 'update profiles set picture="'.$picture.'" WHERE id='.$id.'';
        $result = mysqli_query($link, $query);

        mysqli_close($link);
    }

}


function updateUser($data)
{
    $username   = trim($data['username']);
    $password   = md5($data['password']. SALT);

    $link    = connect();
    $query   = 'update users set password="'.$password.'" WHERE username="'.$username.'"';
    $success = mysqli_query($link, $query); // returns true on update statements

    mysqli_close($link);
    return $success;
}

