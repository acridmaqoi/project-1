<?php

require '../controllers/auth.php';

require_once '../config/db.php';
require_once '../util/db_utils.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="list.css">
    <title>清单</title>
</head>

<body>
    <nav class="navtop">
        <a href="profile/profile.php"><?php echo get_username() ?></a>
        <a href="../util/account/logout.php">登出</a>
    </nav>

    <h1>Tasks</h1>

    <div class="content">
        <div class="form">
            <input id="task_title" type="text" placeholder="标题...">
            <button id="add_btn" type="submit" class="addBtn">加</button>
            <ul id="form_messages"></ul>
        </div>

        <ul id="myUL">

        </ul>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function() {
            // show tasks
            function loadTasks() {
                $.ajax({
                    url: "../util/task/get_tasks.php",
                    type: "POST",
                    data: {
                        id: "<?php $_SESSION['id'] ?>"
                    },
                    success: function(data) {
                        $("#myUL").html(data);
                    }
                });
            }

            loadTasks()

            // add task
            $("#task_title").on("keypress", function(e) {
                if (e.keyCode == 13) {
                    $("#add_btn").click(); // allow enter button to be used
                }
            });

            $("#add_btn").on("click", function(e) {
                e.preventDefault();

                var title = $("#task_title").val();

                $.ajax({
                    url: "../util/task/add_task.php",
                    type: "POST",
                    data: {
                        title: title
                    },
                    success: function(data) {
                        loadTasks(); // reload tasks every time new task is added
                        $("#task_title").val(''); // remove task from input field once added

                        $("#form_messages").empty();
                        // get response
                        parsed_data = jQuery.parseJSON(data);
                        parsed_data.messages.forEach((message) => {
                            // display error messages
                            $("#form_messages").append(message);
                        });
                    }
                });
            });

            // remove task
            $(document).on("click", "#remove-btn", function(e) {
                e.preventDefault();
                var id = $(this).parent().find("#id").data('id');

                $.ajax({
                    url: "../util/task/delete_task.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        loadTasks();
                        if (data == 0) {
                            alert("Something wrong went. Please try again.");
                        }
                    }
                });
            });

            // check/uncheck task
            $(document).on("click", "#complete-btn", function(e) {
                var id = $(this).parent().find("#id").data('id');

                $.ajax({
                    url: "../util/task/complete_task.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        loadTasks();

                        // clear prev messages
                        $("#form_messages").empty();
                        // get response messages
                        parsed_data = jQuery.parseJSON(data);
                        parsed_data.messages.forEach((message) => {
                            // display messages
                            $("#form_messages").append(message);
                        });
                    }
                })
            })

        });
    </script>



</body>

</html>