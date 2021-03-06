<?php

require __DIR__ . '../../../autoloader.php';
require __DIR__ . '../../../util/db.php';

// destory remember-acc cookie
if ($stmt = $con->prepare('UPDATE accounts SET login_token = NULL WHERE id = (?)')) {
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
}

unset($_COOKIE['login_token']);
setcookie('login_token', null, -1, '/');

session_destroy();
// Redirect to the login page:
// header('Location: ../../index.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://apis.google.com/js/platform.js"></script>
    <meta name="google-signin-client_id" content="415251980402-68khrcjsbsmncrutho9fismb3k09965i.apps.googleusercontent.com">

    <title>logging out...</title>
</head>

<body>

    <script>
        gapi.load('auth2', function() {
            /* Ready. Make a call to gapi.auth2.init or some other API */
            gapi.auth2.init({
                client_id: '415251980402-68khrcjsbsmncrutho9fismb3k09965i.apps.googleusercontent.com'
            }).then(auth2 => {
                var auth2 = gapi.auth2.getAuthInstance();


                if (auth2.isSignedIn.get()) {
                    auth2.signOut().then(auth2.disconnect().then(function() {
                        console.log('User signed out.');
                        window.location.href = "http://localhost/project-1";
                    }));
                } else {
                    window.location.href = "http://localhost/project-1";
                }
            })

            gapi.auth2.init({
                client_id: '415251980402-68khrcjsbsmncrutho9fismb3k09965i.apps.googleusercontent.com'
            }).then(auth2 => {
                var auth2 = gapi.auth2.getAuthInstance();
                user = auth2.currentUser.get();
                // console.log(JSON.stringify(user));
                console.log(user);
            })
        });
    </script>

</body>

</html>