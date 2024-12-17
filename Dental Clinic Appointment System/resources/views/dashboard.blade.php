<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/appointment.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Dashboard</title>
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
        .logout-btn {
            margin-top: 20px;
        }
        .revenue-list ul {
            list-style: none;
            padding: 0;
        }
        .revenue-list li {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .calendar table {
            width: 100%;
            text-align: center;
        }
        .calendar td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .calendar .today {
            background-color: #ffeb3b; /* Highlight today's date */
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
        <h1>Welcome to the Dental World</h1>

        <!-- Revenue List and Calendar Section -->
        <div class="row">
            <!-- Revenue List (Left Column) -->
            <div class="col-md-6">
                <h4>Revenue List</h4>
                <div class="revenue-list">
                    <ul>

                    <li>50,000</li>
                        @foreach($revenues as $revenue)
                            <li>Revenue: ₱{{ $revenue->amount }}</li>
                            <td>{{ $appointment->amount }}</td>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Calendar (Right Column) -->
            <div class="col-md-6">
                <div class="calendar">
                    <h4>Calendar</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sun</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>9</td>
                                <td>10</td>
                                <td>11</td>
                                <td>12</td>
                                <td>13</td>
                                <td>14</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>16</td>
                                <td>17</td>
                                <td>18</td>
                                <td>19</td>
                                <td>20</td>
                                <td>21</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>23</td>
                                <td>24</td>
                                <td>25</td>
                                <td>26</td>
                                <td>27</td>
                                <td>28</td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>30</td>
                                <td>31</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Patient List -->
        <div class="patient-table">
            <h4>Patient List</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through the appointments data passed from the controller -->
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->name }}</td> <!-- Ensure patient name is displayed -->
                            <td>{{ $appointment->phone_number }}</td>
                            <td>{{ $appointment->address }}</td>
                            <td>{{ $appointment->service }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</td>
                            <td>{{ $appointment->time }}</td>
                            <td>{{ $appointment->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date();
            const day = today.getDate();
            const calendarCells = document.querySelectorAll("#calendar-body td");

            calendarCells.forEach(cell => {
                if (parseInt(cell.textContent) === day) {
                    cell.classList.add("today");
                }
            });
        });
    </script>
    <script>
    // Function to calculate and update total revenue
    function updateTotalRevenue() {
        let totalRevenue = 0;
        
        // Iterate through each row in the table to sum up the revenue values
        $('table tbody tr').each(function() {
            const revenue = parseFloat($(this).find('td:nth-child(7)').text().replace('₱', '').trim());
            if (!isNaN(revenue)) {
                totalRevenue += revenue;
            }
        });
        
        // Update the total revenue display
        $('.total-revenue h4').text(`Total Revenue: ₱${totalRevenue.toFixed(2)}`);
    }

    // Call the updateTotalRevenue function after the page loads
    $(document).ready(function() {
        updateTotalRevenue();
    });

    // Optional: You can call updateTotalRevenue again if any updates occur, such as deleting a report
    // For example, after deleting a report:
    // deleteReports() {
    //    // After successful deletion
    //    updateTotalRevenue();
    // }
</script>


</body>
</html>
