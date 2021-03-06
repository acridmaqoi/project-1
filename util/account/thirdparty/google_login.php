<?php

require __DIR__ . '../../../db.php';
require __DIR__ . '../../login_inc/login_inc.php';
require __DIR__ . '../../register_inc/register_inc.php';
require __DIR__ . '../../../../lib/Google/vendor/autoload.php';

$CLIENT_ID = "415251980402-68khrcjsbsmncrutho9fismb3k09965i.apps.googleusercontent.com";
$id_token = $_POST['idtoken'];

$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
    $userid = $payload['sub'];
    $email = $payload['email'];

    if ($stmt = $con->prepare("SELECT id FROM accounts WHERE email = (?)")) {
        $stmt->bind_param('s', $email);
        $stmt->bind_result($id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();

        if ($stmt->num_rows() == 1) {
            // user already registered, login
            login_user($id, true);
            echo 'logging-in user...';
        } else {
            // register user
            register_user($email, null, $email, true);
            echo 'registering user...';
        }
    }

} else {
    // Invalid ID token
    echo 'false';
}
