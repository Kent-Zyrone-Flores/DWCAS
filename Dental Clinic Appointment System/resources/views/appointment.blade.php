<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/appointment.css">
    <title>Appointments</title>
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
        /* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.4); /* Background color */
    padding-top: 60px;
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
}

.close-button {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    float: right;
    cursor: pointer;
}

.close-button:hover,
.close-button:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
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
            <a href="{{ route('reports') }}" class="nav-link">Reports</a>
            <a href="{{ route('history') }}" class="nav-link">History</a>
        </div><hr>
        <div class="logout-btn">
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn text-white text-decoration-none">Logout</button>
            </form>  
        </div>
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
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</td>
                            <td>{{ date('h:i A', strtotime($appointment->time)) }}</td>
                            <td>{{ $appointment->status }}</td>
                            
                            <td class="status-column">
    <select class="status-dropdown" data-id="{{ $appointment->id }}">
        <option value="Cancelled" {{ $appointment->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
        <option value="Confirmed" {{ $appointment->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="Reschedule" {{ $appointment->status == 'Reschedule' ? 'selected' : '' }}>Reschedule</option>
        <option value="No show" {{ $appointment->status == 'No show' ? 'selected' : '' }}>No Show</option>
    </select>

<button class="btn btn-primary btn-sm reschedule-btn" data-id="{{ $appointment->id }}" data-name="{{ $appointment->name }}" data-date="{{ $appointment->date }}" data-time="{{ $appointment->time }}">
                                Reschedule
                            </button>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<!-- Reschedule Modal -->
<center>
<div id="rescheduleModal" class="modal">
    <div class="modal-content">
        <button class="close-button" id="closeRescheduleModal">&times;</button>
        <h3>Reschedule Appointment</h3>
        <br>
        <form id="rescheduleForm">
            @csrf
            <input type="hidden" id="appointmentId" name="appointmentId">
            <label for="rescheduleName">Name</label>
            <input type="text" id="rescheduleName" name="name" readonly>
<br><br>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" min="{{ date('Y-m-d') }}" required>

            
<br><br>
            <label for="rescheduleTime">New Time</label>
            <select id="rescheduleTime" name="time" required>
                <script>
                    const startHour = 8; // 8:00 AM
                    const endHour = 17; // 5:00 PM
                    const timeDropdown = document.getElementById('rescheduleTime');
            
                    for (let hour = startHour; hour <= endHour; hour++) {
                        const ampm = hour < 12 ? 'AM' : 'PM';
                        const displayHour = hour > 12 ? hour - 12 : hour; // Convert to 12-hour format
                        const time = `${displayHour}:00 ${ampm}`;
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeDropdown.appendChild(option);
                    }
                </script>
            </select>
            <br><br>
            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-danger" id="cancelRescheduleBtn">Cancel</button>
        </form>
    </div>
</div>
 </center>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Reschedule Modal Elements
    const rescheduleModal = document.getElementById('rescheduleModal');
    const closeModalButton = document.getElementById('closeRescheduleModal');
    const rescheduleForm = document.getElementById('rescheduleForm');
    const cancelRescheduleBtn = document.getElementById('cancelRescheduleBtn');

    // Open Reschedule Modal
    document.querySelectorAll('.reschedule-btn').forEach(button => {
        button.addEventListener('click', function () {
            const appointmentId = this.dataset.id;
            const name = this.dataset.name;
            const date = this.dataset.date;
            const time = this.dataset.time;

            // Set form data
            document.getElementById('appointmentId').value = appointmentId;
            document.getElementById('rescheduleName').value = name;
            document.getElementById('date').value = date;  // Fix the field name here
            document.getElementById('rescheduleTime').value = time;

            // Open the modal
            rescheduleModal.style.display = 'block';
        });
    });

    // Close Modal (Close Button)
    closeModalButton.addEventListener('click', () => {
        rescheduleModal.style.display = 'none';
    });

    // Close Modal (Cancel Button)
    cancelRescheduleBtn.addEventListener('click', () => {
        rescheduleModal.style.display = 'none';
    });

    // Submit Reschedule Form via AJAX
    rescheduleForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(rescheduleForm);

        fetch("{{ route('appointments.reschedule') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while rescheduling.');
        });
    });
});


</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener for changing status via dropdown
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                const appointmentId = this.getAttribute('data-id');
                const newStatus = this.value;
                updateStatus(appointmentId, newStatus);
            });
        });

        // Event listener for Edit button
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const appointmentId = this.getAttribute('data-id');
                // Implement your edit functionality (open a modal or redirect to edit page)
                alert(`Edit appointment with ID: ${appointmentId}`);
            });
        });

        // Event listener for Delete button
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const appointmentId = this.getAttribute('data-id');
                deleteAppointment(appointmentId);
            });
        });
    });

    // Function to update appointment status via AJAX
    function updateStatus(appointmentId, newStatus) {
    fetch("{{ route('appointments.updateStatus') }}", {  // Call named route
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: appointmentId, status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully!');
            location.reload(); // Reload to reflect changes
        } else {
            alert('Error updating status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
}

    // Function to delete an appointment via AJAX
    function deleteAppointment(appointmentId) {
        const confirmation = confirm("Are you sure you want to delete this appointment?");
        if (confirmation) {
            fetch(`/appointments/delete/${appointmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token for security
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Appointment deleted successfully!');
                    // Optionally, remove the row from the table after deletion
                    document.querySelector(`tr[data-id="${appointmentId}"]`).remove();
                } else {
                    alert('Error deleting appointment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the appointment.');
            });
        }
    }
</script>