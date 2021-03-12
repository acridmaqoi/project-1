<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录</title>
</head>

<body>
    <h1>登录</h1>
    <div class="form">
        <input id="username" placeholder="Username" spellcheck="false">
        <input id="password" placeholder="Password" type='password'>
        <button id="btn-submit" type="submit" >Confirm</button>
        <ul id="form-messages"></ul>
        <?php if (isset($_GET["verify_email"])) {
            echo "Check your email for a verification link";
        } 
        ?>
    </div>
    
    <br>
    <a href="register.html">Create Account</a>

    <script>
         // allows enter button to be used when submitting form
         document.querySelector("#password").addEventListener("keyup", event => {
                if(event.key !== "Enter") return;
                document.querySelector("#btn-submit").click();
                event.preventDefault();
            });

            const form = {
                username: document.getElementById('username'),
                password: document.getElementById('password'),
                submit: document.getElementById('btn-submit'),
                messages: document.getElementById('form-messages')
            };

            form.submit.addEventListener('click', () => {
                const request = new XMLHttpRequest();

                request.onload = () => {
                    let responseObject = null;

                    try {
                        // response json from server
                        responseObject = JSON.parse(request.responseText)
                    } catch (e) {
                        console.error('Could not parse JSON!')
                    }

                    if (responseObject) {
                        handleResponse(responseObject)
                    }
                };

                const requestData = `username=${form.username.value}&password=${form.password.value}`;

                // send to server
                request.open('post', 'account/login.php');
                request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                request.send(requestData);
            });

            function handleResponse(responseObject) {
                //prevents duplicate messages
                while (form.messages.firstChild) {
                    form.messages.removeChild(form.messages.firstChild);
                }

                if (responseObject.ok) {
                    location.href = 'main/list.php'
                } else {
                    // user entered incorrect details
                    responseObject.messages.forEach((message) => {
                        const li = document.createElement('p');
                        console.log(message)
                        li.textContent = message;
                        form.messages.appendChild(li);
                    });
                }
            }
    </script>
    

</body>

</html>