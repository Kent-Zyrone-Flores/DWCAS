<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/appointment.css">
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

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('appointments') }}" class="nav-link">Appointments</a>
            <a href="{{ route('reports.index') }}" class="nav-link">Reports</a>
            <a href="{{ route('history') }}" class="nav-link">History</a>
        </div>
        <div class="logout-btn">
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn text-white text-decoration-none">Logout</button>
            </form>  
        </div>
        
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>History</h1>

        <!-- Search Bar on the Right Side -->
        <div class="search-container">
            <input type="text" class="form-control search-bar" placeholder="Search history...">
            <button class="btn btn-success search-button">Search</button>
        </div>

        <div class="filter-buttons mb-3">
            <button class="btn btn-secondary filter-btn" data-status="all">All</button>
            <button class="btn btn-danger filter-btn" data-status="Cancelled">Cancelled</button>
            <button class="btn btn-success filter-btn" data-status="Confirmed">Confirmed</button>
            <button class="btn btn-warning filter-btn" data-status="No Show">No Show</button>
            <button class="btn btn-info" onclick="printReport()">Print Report</button>
        </div>
        
        <!-- History Table -->
        <div class="history-table">
            <h4>History List</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="history-table-body">
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    
    <!-- View Details Modal -->
    <div id="viewDetailsModal" class="modal" style="display: none;">
        <div class="modal-content" style="width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background: #fff;">
            <span id="closeModal" style="float: right; cursor: pointer; font-size: 20px;">&times;</span>
            <h4>Appointment Details</h4>
            <div id="modal-details">
                <!-- Details will be populated here -->
            </div>
            <button id="printDetailsBtn" class="btn btn-primary" style="margin-top: 10px;">Print Details</button>
            <button id="cancelPrintBtn" class="btn btn-secondary" style="margin-top: 10px;">Cancel</button>
        </div>
        </div>
    </div>

        
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function () {
    // Filter Button Logic (Already Existing)
    $('.filter-btn').on('click', function () {
        const status = $(this).data('status');

        $.ajax({
            url: "{{ route('history.filter') }}",
            method: "GET",
            data: { status: status },
            success: function (data) {
                let rows = '';
                data.forEach(appointment => {
                    rows += `
                        <tr>
                            <td>${appointment.id}</td>
                            <td>${appointment.name}</td>
                            <td>${appointment.phone}</td>
                            <td>${appointment.service}</td>
                            <td>${appointment.amount}</td>
                            <td>${formatDate(appointment.date)}</td>
                            <td>${formatTime(appointment.time)}</td>
                            <td>${appointment.status}</td>
                            <td>
                                <button class="btn btn-info btn-sm view-details-btn" 
                                    data-id="${appointment.id}" 
                                    data-name="${appointment.name}" 
                                    data-phone="${appointment.phone}"
                                    data-service="${appointment.service}"
                                    data-amount="${appointment.amount}"
                                    data-date="${appointment.date}"
                                    data-time="${appointment.time}"
                                    data-status="${appointment.status}">
                                    View
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $('#history-table-body').html(rows);
            },
            error: function () {
                alert('Failed to fetch data. Please try again.');
            }
        });
    });

    // Open Modal with Details
    $(document).on('click', '.view-details-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const phone = $(this).data('phone');
        const service = $(this).data('service');
        const amount = $(this).data('amount');
        const date = formatDate($(this).data('date'));
        const time = formatTime($(this).data('time'));
        const status = $(this).data('status');

        const detailsHTML = `
    <div style="font-family: Arial, sans-serif; padding: 20px;">
        <div style="text-align: center;">
            <h2 style="margin: 0;">Dental World Clinic</h2>
            <p style="margin: 5px; font-size: 14px;">Poblcacion, Tagoloan</p>
            <p style="margin: 5px; font-size: 14px;">Phone: (63) 0917-885-5153 </p>
            <hr style="border: 1px solid #ddd;">
        </div>
        
        <h3>Receipt</h3>
        <p><strong>History ID:</strong> ${id}</p>
        <p><strong>Name:</strong> ${name}</p>
        <p><strong>Phone:</strong> ${phone}</p>
        <p><strong>Service:</strong> ${service}</p>
        <p><strong>Amount:</strong> $${amount}</p>
        <p><strong>Date:</strong> ${date}</p>
        <p><strong>Time:</strong> ${time}</p>
        <p><strong>Status:</strong> ${status}</p>

        <hr style="border: 1px solid #ddd;">
        
        <div style="text-align: center;">
            <p style="font-size: 12px;">Thank you for choosing our clinic. We look forward to serving you again!</p>
        </div>
    </div>
`;
$('#modal-details').html(detailsHTML);

        

        // Show the modal
        $('#viewDetailsModal').show();
    });

    // Close Modal
    $('#closeModal').on('click', function () {
        $('#viewDetailsModal').hide();
    });
    // Close Modal when Cancel button is clicked
$('#cancelPrintBtn').on('click', function () {
    $('#viewDetailsModal').hide();
});

    // Print Modal Content
    $('#printDetailsBtn').on('click', function () {
        const printContent = document.getElementById('modal-details').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = `
            <div style="padding: 20px; font-family: Arial, sans-serif;">
                <h2>Appointment Details</h2>
                ${printContent}
            </div>
        `;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload(); // Refresh back to the original content
    });

    // Format Date and Time Functions
    function formatDate(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('en-US', options);
    }

    function formatTime(time) {
        const [hour, minute] = time.split(':');
        const h = hour % 12 || 12;
        const ampm = hour >= 12 ? 'PM' : 'AM';
        return `${h}:${minute} ${ampm}`;
    }
});

function printReport() {
    // Clone the history-table element
    const historyTable = document.querySelector('.history-table').cloneNode(true);

    // Remove the "Action" column from the header
    const headerRow = historyTable.querySelector('thead tr');
    headerRow.removeChild(headerRow.lastElementChild); // Remove last column (Action)

    // Remove the "Action" column from the body
    const bodyRows = historyTable.querySelectorAll('tbody tr');
    bodyRows.forEach(row => {
        row.removeChild(row.lastElementChild); // Remove last cell in each row
    });

    // Prepare the content for printing
    const content = historyTable.outerHTML;
    const printWindow = window.open('', '', 'height=400,width=800');
    printWindow.document.write('<html><head><title>Print Report</title></head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}


</script>

</html>