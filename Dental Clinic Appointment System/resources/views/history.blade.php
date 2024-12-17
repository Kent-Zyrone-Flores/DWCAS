<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/history.css">
    <title>History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
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

        /* Custom Dropdown Styles */
        .dropdown-menu {
            background-color: black; /* Set background color to black */
            color: white; /* Set text color to white */
        }

        .dropdown-item:hover {
            background-color: #575757; /* Option hover effect */
            color: white; /* Text color on hover */
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <img src="https://via.placeholder.com/80?text=Photo" alt="Profile Photo" class="profile-photo">
            <div class="name">Admin Name</div>
            <div class="email">admin@example.com</div>
        </div><hr>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('appointments') }}" class="nav-link">Appointments</a>
            <a href="{{ route('reports') }}" class="nav-link">Reports</a>
            
            <!-- History Dropdown -->
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="historyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    History
                </a>
                <ul class="dropdown-menu" aria-labelledby="historyDropdown">
                    <li><a class="dropdown-item" href="#">No Show</a></li>
                    <li><a class="dropdown-item" href="#">Cancelled</a></li>
                    <li><a class="dropdown-item" href="#">Settled</a></li>
                </ul>
            </div>
        </div><hr><br><br><br><br><br>

        <div class="logout-btn"><a href="#" class="text-white text-decoration-none">Logout</a></div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Dental Word Clinic History</h1>
        <h2>History</h2>


        <div class="search-container">
            <input type="text" class="form-control search-bar" placeholder="Search reports...">
            <button class="btn btn-success search-button">Search</button>
        </div>

        <!-- Control buttons for Create, Update, Delete, and Print -->
        <div class="control-buttons">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal">Create Report</button>
        <button class="btn btn-warning" onclick="updateReport()">Update</button>
            <button class="btn btn-danger" id="deleteReportsBtn">Delete</button>
            <button class="btn btn-info" onclick="printReport()">Print Report</button>
        </div>

        <div class="history-table">
            <h1>History List</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td><input type="checkbox" class="appointment-checkbox" data-id="{{ $appointment->id }}"></td>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->name }}</td>
                            <td>{{ $appointment->service }}</td>
                            <td>{{ $appointment->status }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</td>
                            <td>{{ $appointment->amount }}</td>
                            <td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
    </div>
    <div class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Create Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createReportForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Report Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
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
    function calculateTotalRevenue() {
        let total = 0; 

        document.querySelectorAll('.history-table tbody tr').forEach(row => {
            const revenueCell = row.cells[6]; 
            
            if (revenueCell) {
                const amount = parseFloat(revenueCell.innerText.replace('₱', '').replace(',', '').trim());
                
                if (!isNaN(amount)) {
                    total += amount; 
                }
            }
        });

        document.querySelector('.total-revenue h4').innerText = 'Total Revenue: ₱' + total.toFixed(2);
    }

    window.addEventListener('load', calculateTotalRevenue);
</script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateReport() {
            alert("Update functionality to be implemented");
        }

        function deleteReports() {
            const selectedIds = [];
            $('.appointment-checkbox:checked').each(function() {
                selectedIds.push($(this).data('id'));
            });

            if (selectedIds.length > 0) {
                $.ajax({
                    url: '/delete-reports', 
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function(response) {
                        alert('Reports deleted successfully');
                        location.reload(); 
                    },
                    error: function(error) {
                        alert('An error occurred while deleting reports');
                    }
                });
            } else {
                alert("No reports selected");
            }
        }

        function printReport() {
            const content = document.querySelector('.history-table').outerHTML;
            const printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>Print Report</title></head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        document.getElementById('selectAll').addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('.appointment-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
        });

        document.getElementById('deleteReportsBtn').addEventListener('click', deleteReports);
    </script>

</body>
</html>

