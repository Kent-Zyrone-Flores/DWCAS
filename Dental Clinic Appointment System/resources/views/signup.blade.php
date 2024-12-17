<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental World Clinic - Sign Up</title>
  <link rel="stylesheet" href="css/signup.css"> 
</head>

<script>
    function landingpage() {
        window.location.href = "{{ route('landingpage') }}";
    }

    function user() {
        window.location.href = "{{ route('user') }}";
    }
</script>

<nav class="navbaruser">
<nav class="navbar">
    <div class="navdiv">
      <div class="logo">
        <a href="#">
          <img src="Documentation/logo.png" alt="Dental World Clinic Logo">
          <span>Dental World Clinic</span>
        </a>
      </div>
      <ul>
        <li><a href="/landingpage">Home</a></li>
        <button><a href="login">Log In</a></button>
      </ul>
    </div>
  </nav>

  <!-- Boxed Sign-up Form -->
  <div class="signup-box">
  <h2 class="form-caption">Sign Up</h2> <!-- Caption added here -->
  <form method="POST" action="{{ route('signup.save') }}">
    @csrf
    <div class="input-group">
      <div class="input-field">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
      </div>
      <div class="input-field">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name">
      </div>
    </div>
    <div class="input-group">
      <div class="input-field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
      </div>
    </div>
    <div class="input-group">
      <div class="input-field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <div class="input-field">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
      </div>
    </div>
    <button type="submit">Sign Up</button>
  </form>
</div>



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
