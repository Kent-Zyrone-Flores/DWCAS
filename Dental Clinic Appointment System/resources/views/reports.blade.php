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

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('appointments') }}" class="nav-link">Appointments</a>
            <a href="{{ route('reports.index') }}" class="nav-link">Reports</a>
            <a href="{{ route('history') }}" class="nav-link">History</a>
        </div> <hr>
        <div class="logout-btn">
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn text-white text-decoration-none">Logout</button>
            </form>  
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Dental World Clinic Reports</h1>

      
        
        

        <!-- Control buttons for Create, Update, Delete, and Print -->
        <div class="control-buttons">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal">Create Report</button>
            {{-- <button class="btn btn-warning" onclick="updateReport()">Update Report</button>
            <button class="btn btn-danger" onclick="deleteReport()">Delete Report</button> --}}
            <button class="btn btn-info" onclick="printReport()">Print Report</button>
        </div>

<div class="search-container">
    <form action="{{ route('reports.index') }}" method="GET">
        <div class="input-group">
            <input 
                type="text" 
                class="form-control search-bar" 
                placeholder="Search report..." 
                name="search" 
                value="{{ request('search') }}"
            >
            <button class="btn btn-success search-button" type="submit">Search</button>
        </div>
    </form>
</div>

<div class="container mt-4">
    <h1>Appointment Reports</h1>

    @if ($reports->isEmpty())
        <div class="alert alert-warning text-center mt-3">
            No records found for "{{ request('search') }}".
        </div>
    @else
        <table class="table report-table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Patient Name</th>
                    <th>Service Category</th>
                    <th>SubService Category</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->name }}</td>
                    <td>{{ $report->service }}</td>
                    <td>{{ $report->subservice }}</td>
                    <td>{{ $report->amount }}</td>
                    <td>{{ $report->status }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('F j, Y') }}</td>
                    <td>{{ $report->description }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="openEditModal({{ $report }})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteReport({{ $report->id }})">Cancel</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
    
    <!-- edit and calcel Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="reportId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="editCategory" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSubcategory" class="form-label">Subcategory</label>
                            <input type="text" class="form-control" id="editSubcategory" name="subcategory" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAmount" class="form-label">Amount</label>
                            <input type="text" class="form-control" id="editAmount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <input type="text" class="form-control" id="editStatus" name="status" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveEdit()">Save Changes</button>
                </div>
            </div>
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


                       <!-- Category Field -->
    <div class="mb-3">
        <label for="service" class="form-label">Category</label>
        <select 
            class="form-select" 
            id="service" 
            name="service" 
            required>
            <option value="" disabled selected>-Select Category-</option>
            <option value="General Dentistry">General Dentistry</option>
            <option value="Orthodontics">Orthodontics</option>
            <option value="Cosmetic Dentistry">Cosmetic Dentistry</option>
            <option value="Pediatric Dentistry">Pediatric Dentistry</option>
            <option value="Specialized Procedures">Specialized Procedures</option>
        </select>
    </div>

    <!-- Subcategory Field -->
    <div class="mb-3">
        <label for="subservice" class="form-label">Subcategory</label>
        <select 
            class="form-select" 
            id="subservice" 
            name="subservice" 
            required>
            <option value="" disabled selected>-Select Subcategory-</option>
        </select>
    </div>

    <!-- Amount Field -->
    <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input 
            type="text" 
            class="form-control" 
            id="amount" 
            name="amount" 
            placeholder="₱0.00" 
            readonly 
            required>
    </div>

<script>
  // Define categories, subcategories, and prices
  const serviceData = {
    "General Dentistry": [
      { subcategory: "Dental Cleaning", price: "₱1,500" },
      { subcategory: "Tooth Extraction", price: "₱2,000" },
      { subcategory: "Filling (Per Tooth)", price: "₱1,200" },
    ],
    "Orthodontics": [
      { subcategory: "Braces Installation", price: "₱60,000" },
      { subcategory: "Retainer (Upper or Lower)", price: "₱8,000" },
      { subcategory: "Adjustment (Per Visit)", price: "₱1,500" },
    ],
    "Cosmetic Dentistry": [
      { subcategory: "Teeth Whitening", price: "₱5,000" },
      { subcategory: "Veneers (Per Tooth)", price: "₱15,000" },
      { subcategory: "Bonding (Per Tooth)", price: "₱3,000" },
    ],
    "Pediatric Dentistry": [
      { subcategory: "Baby Tooth Extraction", price: "₱1,000" },
      { subcategory: "Fluoride Treatment", price: "₱800" },
      { subcategory: "Sealants (Per Tooth)", price: "₱1,200" },
    ],
    "Specialized Procedures": [
      { subcategory: "Root Canal Treatment", price: "₱8,000" },
      { subcategory: "Dental Implants", price: "₱100,000" },
      { subcategory: "TMJ Treatment", price: "₱25,000" },
    ],
  };

  const categorySelect = document.getElementById("service");
  const subcategorySelect = document.getElementById("subservice");
  const amountInput = document.getElementById("amount");

  // Populate subcategories based on selected category
  categorySelect.addEventListener("change", function () {
    const selectedCategory = this.value;

   
 // Clear subcategories
  subcategorySelect.innerHTML = '<option value="" disabled selected>-Select Subcategory-</option>';
  amountInput.value = "";

  if (serviceData[selectedCategory]) {
    serviceData[selectedCategory].forEach(service => {
      const option = document.createElement("option");
      option.value = service.subcategory;
      option.textContent = service.subcategory;
      option.dataset.price = service.price;
      subcategorySelect.appendChild(option);
    });
  }
  });

  // Update amount based on selected subcategory
  subcategorySelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    amountInput.value = selectedOption.dataset.price || "";
  });
</script>

                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Completed">Completed</option>
                                <option value="No show">No Show</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" required>
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
        // Open edit modal with data
        function openEditModal(report) {
            document.getElementById('reportId').value = report.id;
            document.getElementById('editName').value = report.name;
            document.getElementById('editCategory').value = report.category;
            document.getElementById('editSubcategory').value = report.subcategory;
            document.getElementById('editAmount').value = report.amount;
            document.getElementById('editStatus').value = report.status;
            document.getElementById('editDate').value = report.date;
            document.getElementById('editDescription').value = report.description;
    
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    
        // Save edits
        function saveEdit() {
            const id = document.getElementById('reportId').value;
            const formData = new FormData(document.getElementById('editForm'));
    
            fetch(`/reports/${id}/edit`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Report updated successfully!');
                    location.reload();
                } else {
                    alert('Failed to update report.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    
        // Delete report
        function deleteReport(id) {
            if (!confirm('Are you sure you want to delete this report?')) return;
    
            fetch(`/reports/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Report deleted successfully!');
                    location.reload();
                } else {
                    alert('Failed to delete report.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    
    <script>

function printReport() {
    // Select the table content
    const content = document.querySelector('.report-table');

    if (!content) {
        alert('Table not found.');
        return;
    }

    // Create a new window for printing
    const printWindow = window.open('', '', 'height=600,width=800');

    if (!printWindow) {
        alert('Unable to open print window. Please check your browser settings.');
        return;
    }

    // Write content and styles to the new window
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Report</title>
            <style>
                /* Add your table styling */
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table, th, td {
                    border: 1px solid black;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }

                /* Hide the Action column */
                th:last-child, td:last-child {
                    display: none;
                }
            </style>
        </head>
        <body>
            <h2>Report Table</h2>
            ${content.outerHTML}
        </body>
        </html>
    `);

    // Close the document and print
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}



        


        
    </script>

    <!-- Bootstrap JS for Modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

        