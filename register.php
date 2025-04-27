<!-- routes/register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register</title>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <form class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm flex flex-col justify-center"id="registerForm" action="./api/registerM.php" method="POST">
        <h1 class="text-xl mb-5">Registration</h1> 
        
        <span class="flex justify-between mb-3">            
            <label class="text-lg" for="name">Full Name:</label>
            <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="text" id="name" name="name" required>
        </span>
        
        <span class="flex justify-between mb-3">    
            <label class="text-lg" for="occupation">Occupation:</label>
            <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="text" id="occupation" name="occupation" required/>
        </span>

        <span class="flex justify-between mb-3">    
            <label class="text-lg" for="birthDate">Birth Date:</label>
            <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="date" id="birthDate" name="birthDate" required/>
        </span>
        
        <span class="flex justify-between mb-3">    
            <label class="text-lg" for="birthPlace">Birth Place:</label>
            <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="text" id="birthPlace" name="birthPlace" required/>
        </span>

        <span class="flex justify-between mb-3">    
            <label class="text-lg" for="aboutMe">About Me:</label>
            <textarea class="border border-blue-400 p-1 rounded-lg shadow-lg" type="text" id="aboutMe" name="aboutMe" required></textarea>
        </span>

        <span class="flex justify-between mb-3">
            <label class="text-lg" for="email">Email Address:</label>
            <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="text" id="email" name="email" required>
        </span>
        
        <span class="flex justify-between mb-3">            
            <label class="text-lg" for="password">Password:</label>
            <input class="border border-blue-400 p-1 rounded-lg shadow-lg" type="password" id="password" name="password" required>
        </span>
        <button class="rounded-lg shadow-md bg-blue-400 p-2 my-5" type="submit">Register</button>

        <p>Already have an account? <a href="login.php">Login here</a>.</p>

        </form>
    </div>


    <script>
        document.getElementById('registerForm').onsubmit = async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                window.location.href = 'login.php';
            } else {
                alert(result.message);
            }
        };
    </script>
</body>
</html>
