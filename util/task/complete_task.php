<?php

require_once "../../config/db.php";

$ok = true;
$messages = array();

// check data was submitted
if (!isset($_POST['id'])) {
    die('Error');
}

$id = $_POST['id'];

// insert task into db
if ($ok && ($stmt = $con->prepare('UPDATE tasks SET completed = !completed WHERE id = (?)'))) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

echo json_encode(
    array(
        'ok' => $ok,
		'messages' => $messages
    )
);