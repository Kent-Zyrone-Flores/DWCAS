<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Linking the CSS using Laravel's asset() helper -->
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <title>Generated Reports</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Additional custom styles */
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
        .sidebar {
            position: fixed;
            width: 260px;
            height: 100%;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            left: 0;
        }
        .main-content {
            margin-left: 260px; /* Adjust to match sidebar width */
            padding: 20px;
        }
        .history-table {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .control-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
        .control-buttons button {
            margin-left: 10px;
        }
        .search-container {
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
        .status-column select {
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <img src="https://via.placeholder.com/80?text=Photo" alt="Profile Photo" class="profile-photo">
            <div class="name">Admin Name</div>
            <div class="email"> @if (Auth::check())
                <p>{{ Auth::user()->email }}</p>
            @else
                <p>Welcome, Guest!</p>
            @endif</div>

        </div><hr>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('appointments') }}" class="nav-link">Appointments</a>
            <a href="{{ route('reports') }}" class="nav-link">Reports</a>
            <a href="{{ route('history') }}" class="nav-link">History</a>
        </div>
        <hr>
        <br><br><br><br><br>

        <div class="logout-btn"><a href="#" class="text-white text-decoration-none">Logout</a></div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Generated Reports</h1>

        <div class="search-container">
            <input type="text" class="form-control search-bar" placeholder="Search report..." name="search">
            <button class="btn btn-success search-button">Search</button>
        </div>

        <!-- Control buttons for Create, Update, Delete, and Print -->
        <div class="control-buttons">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal">Create Report</button>
            <button class="btn btn-warning" onclick="updateReport()">Update Report</button>
            <button class="btn btn-danger" onclick="deleteReport()">Delete Report</button>
            <button class="btn btn-info" onclick="printReport()">Print Report</button>
        </div>

        <div class="container">
            <h1>Appointment Reports</h1>

            <table class="table history-table">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Create Report -->
    <div class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Create Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store_report') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="service" class="form-label">Service</label>
                            <input type="text" class="form-control" id="service" name="service" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="In Progress">In Progress</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="revenue" class="form-label">Revenue</label>
                            <input type="text" class="form-control" id="revenue" name="revenue" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to update report (just an alert for now)
        function updateReport() {
            alert("Update functionality to be implemented");
        }

        // Function to delete report (just an alert for now)
        function deleteReport() {
            alert("Delete functionality to be implemented");
        }

        // Function to print report
        function printReport() {
            const content = document.querySelector('.history-table').outerHTML;
            const printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>Print Report</title></head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

    <!-- Bootstrap JS for Modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

        