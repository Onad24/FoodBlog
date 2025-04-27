<!-- routes/login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body>
       <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <form class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm flex flex-col justify-center" id="loginForm" action="./api/loginM.php" method="POST">
                <h1 class="text-xl mb-5">Login</h1>   
               
                <span class="flex justify-between mb-3">
                <label class="text-lg" for="email">Email Address:</label>
                <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="text" id="email" name="email" required>  
                </span>

                <span class="flex justify-between mb-3">
                <label class="text-lg" for="password">Password:</label>
                <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="password" id="password" name="password" required>
                </span>
                
                <button class="rounded-lg shadow-md bg-blue-400 p-2 my-5" type="submit" class="self-center">Login</button>
                
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </form>
        </div>
    <script>
        document.getElementById('loginForm').onsubmit = async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            console.log(result);

            if (result.status === 'success') {
                window.location.href = 'index.php';
            } else {
                alert(result.message);
            }
        };
    </script>
</body>
</html>
