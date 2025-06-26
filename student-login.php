<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  </style>
      /* Apply to whole page */
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    /* Container */
    .login-container {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 350px;
    }

    /* Headline */
    .login-container h2 {
      margin-bottom: 20px;
      text-align: center;
    }

    /* Labels */
    .login-container label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 500;
    }

    /* Inputs */
    .login-container input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
      transition: border-color 0.2s;
    }

    .login-container input:focus {
      border-color: #6c63ff;
      outline: none;
    }

    /* Button */
    .login-container button {
      margin-top: 20px;
      width: 100%;
      background: #6c63ff;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-container button:hover {
      background: #574fe1;
    }

  </style>
</head>
<body class="login-page">
  <div class="login-container">
    <h2>Student Login</h2>
    <form action="php/student_auth.php" method="POST">
      <label for="prn">Enter Email:</label>
      <input type="text" name="prn" id="prn" placeholder="username@example.com" required>
      <label for="password">Enter Password:</label>
      <input type="password" name="password" id="password" placeholder="Password" required>
      <input type="submit" name="submit" id="submit" value="Login">
    </form>
    <p>New user? <a href="#">Contact Admin for Registration</a></p>
  </div>
</body>
</html>

</body>
</html>