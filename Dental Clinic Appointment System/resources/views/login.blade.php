
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental World Clinic - Login</title>
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
  <!-- Login Form -->
  <form method="POST" action="{{ route('login') }}">
    @csrf
    <nav class="navbar">
      <div class="navdiv">
        <!-- Logo with Image -->
        <div class="logo">
          <a href="#">
            <img src="Documentation/logo.png" alt="Dental World Clinic Logo">
            <span>Dental World Clinic</span>
          </a>
        </div>
        <!-- Navigation Links -->
        <ul>
          <li><a href="{{ route('landingpage') }}">Home</a></li>
          <button><a href="{{ route('login') }}">Log in</a></button>
        </ul>
      </div>
    </nav>

    <!-- Success message alert -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Error message alert -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="login-form-container">
            <h1>Log In</h1>
            <p>Welcome back! Please log in to your account.</p>
            <div class="input-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="submit-button">
                <button type="submit">Log in</button>
                <div>
                    <p>Don't have an account? <a href="{{ route('signup') }}">Sign Up</a></p>
                </div>
            </div>
        </div>
    </form>


  <!-- Footer Section -->
  <footer class="footer">
    <div class="logo">
      <a href="#">
        <img src="Documentation/logo.png" alt="Dental World Clinic Logo">
        <span>DWC</span>
      </a>
    </div>
    <div class="footer-container">
      <div class="footer-details">
        <h2>Contact Details</h2>
        <p><strong>Phone:</strong> 0917-885-5153</p>
        <p><strong>Email:</strong> info@dentalworldclinic.com</p>
        <p><strong>Address:</strong> Poblacion, Tagoloan Misamis Oriental, 9001</p>
        <div class="footer-rights">
          <p>&copy; 2024 Dental World Clinic. All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>