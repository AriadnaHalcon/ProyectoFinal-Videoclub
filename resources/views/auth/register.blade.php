<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FADADD; 
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #FFF0F5;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .input-container {
            position: relative;
            margin-bottom: 20px;
        }
        .input-container input {
            width: 100%;
            padding: 10px;
            padding-right: 40px; /* Espacio para el ícono */
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .input-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 74%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #D28B7A;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #C76C5B;
        }
        a {
            color: #D28B7A;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            color: #C76C5B;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 style="text-align: center;">Register</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="input-container">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-container">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-container">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-eye toggle-password" id="toggle-password"></i>
            </div>
            <div class="input-container">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <i class="fas fa-eye toggle-password" id="toggle-password-confirmation"></i>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var icon = this;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('toggle-password-confirmation').addEventListener('click', function() {
            var passwordConfirmationInput = document.getElementById('password_confirmation');
            var icon = this;
            if (passwordConfirmationInput.type === 'password') {
                passwordConfirmationInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordConfirmationInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
