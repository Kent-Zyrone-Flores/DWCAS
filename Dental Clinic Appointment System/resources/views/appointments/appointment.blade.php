<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 20px 0;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid white;
            margin-bottom: 10px;
        }
        .profile-section .name, .profile-section .email {
            display: block;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .nav-links a:hover {
            background-color: #575757;
        }
        .logout-btn {
            background-color: green;
            text-align: center;
            padding: 10px 0;
            border-radius: 5px;
        }
        .logout-btn a {
            color: white;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: darkgreen;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .appointment-table {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .appointment-controls {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
        .search-bar {
            width: 300px;
            margin-right: 10px;
        }
        .search-button {
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Profile Section -->
        <div class="profile-section">
            <img src="https://via.placeholder.com/80?text=Photo" alt="Profile Photo" class="profile-photo">
            <div class="name">Admin Name</div>
            <div class="email">admin@example.com</div>
        </div><hr>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('appointments') }}" class="nav-link">Appointments</a>
            <a href="{{ route('reports') }}" class="nav-link">Reports</a>
            <a href="{{ route('history') }}" class="nav-link">History</a>
        </div><hr><br><br><br><br><br>

        <div class="logout-btn"><a href="#" class="text-white text-decoration-none">Logout</a></div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Appointments</h1>

        <!-- Appointment Controls -->
        <div class="appointment-controls">
            <input type="text" class="form-control search-bar" placeholder="Search appointments...">
            <button class="btn btn-success search-button">Search</button>
        </div>

        <!-- Appointment Table -->
        <div class="appointment-table">
            <h4>Appointment List</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->id }}</td>
                <td>{{ $appointment->name }}</td>
                <td>{{ $appointment->phone }}</td>
                <td>{{ $appointment->address }}</td>
                <td>{{ $appointment->service }}</td>
                <td>{{ $appointment->amount }}</td>
                <td>{{ $appointment->date }}</td>
                <td>{{ $appointment->time }}</td>
                <td>{{ $appointment->status }}</td>
                <td>
                    <!-- You can add actions like edit, delete here -->
                    <a href="#">Edit</a> | <a href="#">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
            </table>
        </div>
    </div>
</body>
</html>
