<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental World Clinic</title>
  <link rel="stylesheet" href="css/user.css">
  
</head>
<body>
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
          <div  class="email"> @if (Auth::check())
            <p>Hello, {{ Auth::user()->email }}</p>
        @else
            <p>Welcome, Guest!</p>
        @endif</div>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
        </ul>
      </div>
    </nav>
  </nav>    
  @if (session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border: 1px solid #f5c6cb;">
        {{ session('error') }}
    </div>
@endif

  <div class="main-container">
    <div class="form-container">
      <h2>Book Appointment</h2>
      <form method="POST" action="{{ route('user.submit') }}" id="appointmentForm">
        @csrf
        <label for="name">Full Name</label>
                  <input 
                      type="text" 
                      id="name" 
                      name="name" 
                      value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" 
                      placeholder="Full Name" 
                      required
                  >
  
                  <label for="phone">Contact No.</label>
                  <input 
                      type="tel" 
                      id="phone" 
                      name="phone" 
                      value="{{ Auth::user()->phone }}" 
                      placeholder="Contact Number" 
                      required
                  >
  
                  <label for="address">Address</label>
                  <input 
                      type="text" 
                      id="address" 
                      name="address" 
                      value="{{ Auth::user()->address }}" 
                      placeholder="Address" 
                      required
                  >

                  <label for="service">Category</label>
<select id="service" name="service" required>
  <option value="">Select Service</option>
  <option value="General Dentistry">General Dentistry</option>
  <option value="Orthodontics">Orthodontics</option>
  <option value="Cosmetic Dentistry">Cosmetic Dentistry</option>
  <option value="Pediatric Dentistry">Pediatric Dentistry</option>
  <option value="Specialized Procedures">Specialized Procedures</option>
</select>

<br>

<label for="subservice">Sub Category</label>
<select id="subservice" name="subservice" required>
  <option value="">Select Subcategory</option>
</select>

<script>
// Define categories, subcategories, and prices
const serviceData = {
  "General Dentistry": [
    { subservice: "Dental Cleaning", price: "₱1,500" },
    { subservice: "Tooth Extraction", price: "₱2,000" },
    { subservice: "Filling (Per Tooth)", price: "₱1,200" },
  ],
  "Orthodontics": [
    { subservice: "Braces Installation", price: "₱60,000" },
    { subservice: "Retainer (Upper or Lower)", price: "₱8,000" },
    { subservice: "Adjustment (Per Visit)", price: "₱1,500" },
  ],
  "Cosmetic Dentistry": [
    { subservice: "Teeth Whitening", price: "₱5,000" },
    { subservice: "Veneers (Per Tooth)", price: "₱15,000" },
    { subservice: "Bonding (Per Tooth)", price: "₱3,000" },
  ],
  "Pediatric Dentistry": [
    { subservice: "Baby Tooth Extraction", price: "₱1,000" },
    { subservice: "Fluoride Treatment", price: "₱800" },
    { subservice: "Sealants (Per Tooth)", price: "₱1,200" },
  ],
  "Specialized Procedures": [
    { subservice: "Root Canal Treatment", price: "₱8,000" },
    { subservice: "Dental Implants", price: "₱100,000" },
    { subservice: "TMJ Treatment", price: "₱25,000" },
  ],
};

// Function to populate subcategory based on category selected
function updateSubcategory() {
  const categorySelect = document.getElementById("service");
  const subcategorySelect = document.getElementById("subservice");

  // Clear existing subcategories
  subcategorySelect.innerHTML = '<option value="">Select Sub Service</option>';

  const selectedCategory = categorySelect.value;

  if (serviceData[selectedCategory]) {
    serviceData[selectedCategory].forEach(service => {
      const option = document.createElement("option");
      option.value = service.subservice;
      option.textContent = service.subservice;
      option.dataset.price = service.price;
      subcategorySelect.appendChild(option);
    });
  }
}

// Event listener for category dropdown
document.getElementById("service").addEventListener("change", updateSubcategory);

// Update amount based on subcategory selection
document.getElementById("subservice").addEventListener("change", function () {
  const selectedOption = this.options[this.selectedIndex];
  document.getElementById("amount").value = selectedOption.dataset.price || "";
});

document.addEventListener('DOMContentLoaded', function () {
      const viewButtons = document.querySelectorAll('.view-button modal-button');
      const modal = document.getElementById('appointmentModal');
      const modalDetails = document.getElementById('modal-details');
      const closeModal = document.getElementById('closeModal');
      const cancelModalBtn = document.getElementById('cancelModalBtn');
      const printDetailsBtn = document.getElementById('printDetailsBtn');

      // Open Modal and Populate Data
      viewButtons.forEach(button => {
          button.addEventListener('click', function () {
              const name = button.getAttribute('data-name');
              const phone = button.getAttribute('data-phone');
              const address = button.getAttribute('data-address');
              const service = button.getAttribute('data-service');
              const amount = button.getAttribute('data-amount');
              const date = button.getAttribute('data-date');
              const time = button.getAttribute('data-time');
              const status = button.getAttribute('data-status');

              const detailsHTML = `
                  <p><strong>Name:</strong> ${name}</p>
                  <p><strong>Phone:</strong> ${phone}</p>
                  <p><strong>Address:</strong> ${address}</p>
                  <p><strong>Service:</strong> ${service}</p>
                  <p><strong>Amount:</strong> ${amount}</p>
                  <p><strong>Date:</strong> ${date}</p>
                  <p><strong>Time:</strong> ${time}</p>
                  <p><strong>Status:</strong> ${status}</p>
              `;

              modalDetails.innerHTML = detailsHTML;
              modal.style.display = 'flex';
          });
      });

      // Close Modal
      closeModal.addEventListener('click', () => modal.style.display = 'none');
      cancelModalBtn.addEventListener('click', () => modal.style.display = 'none');

      // Print Details
      printDetailsBtn.addEventListener('click', function () {
          const printContent = modalDetails.innerHTML;
          const originalContent = document.body.innerHTML;

          document.body.innerHTML = `
              <div style="text-align: center; font-family: Arial, sans-serif;">
                  <h2>Dental Clinic Appointment</h2>
                  <div style="text-align: left; margin-left: 20px;">${printContent}</div>
              </div>
          `;
          window.print();
          document.body.innerHTML = originalContent;
          window.location.reload();
      });

      // Close Modal on Outside Click
      window.addEventListener('click', function (e) {
          if (e.target === modal) modal.style.display = 'none';
      });
  });


  
</script>


        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" placeholder="Amount" readonly required>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" min="{{ date('Y-m-d') }}" required>
        
{{-- 
    <script>
        // Set today's date as the minimum selectable date
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('date');
            const today = new Date();

            // Format the date as YYYY-MM-DD
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const day = String(today.getDate()).padStart(2, '0');

            const minDate = `${year}-${month}-${day}`;
            dateInput.setAttribute('min', minDate);
        });
    </script> --}}


        <label for="time">Time</label>
        <select type="text" id="time" name="time" required>
          <option value="8:00">8:00 AM</option>
          <option value="9:00">9:00 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="13:00">1:00 PM</option>
          <option value="14:00">2:00 PM</option>
          <option value="15:00">3:00 PM</option>
          <option value="16:00">4:00 PM</option>
          <option value="17:00">5:00 PM</option>
        </select>

        <center>
        <button type="submit" id="bookButton">Book</button>
        </center>
      </form>
    </div>

    
    <div class="booking-container">
      <h2>Booking Appointments</h2>
      <table class="appointment-table" id="bookingTable">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Phone Number</th>
                  <th>Address</th>
                  <th>Service</th>
                  <th>Sub Service</th>
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
                  <td>{{ $appointment->name }}</td>
                  <td>{{ $appointment->phone }}</td>
                  <td>{{ $appointment->address }}</td>
                  <td>{{ $appointment->service }}</td>
                  <td>{{ $appointment->subservice }}</td>
                  <td>{{ $appointment->amount }}</td>
                  <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</td>
                  <td>{{ date('h:i A', strtotime($appointment->time)) }}</td>
                  <td>{{ $appointment->status }}</td>
                  <td>
                      <button 
                          class="cancel-button modal-button" 
                          onclick="removeRow(this, '{{ $appointment->id }}')"
                      >
                          Cancel
                      </button>
                      <button 
                          class="view-button modal-button" 
                          data-name="{{ $appointment->name }}"
                          data-phone="{{ $appointment->phone }}"
                          data-address="{{ $appointment->address }}"
                          data-service="{{ $appointment->service }}"
                          data-amount="{{ $appointment->amount }}"
                          data-date="{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}"
                          data-time="{{ date('h:i A', strtotime($appointment->time)) }}"
                          data-status="{{ $appointment->status }}"
                      >
                          View
                      </button>
                      <button class="printReceiptButton">Print Receipt</button>

                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>
<!-- Include jsPDF and QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

<script>
  // Function to generate and print PDF for a specific booking
  function generatePDF(row) {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    // Extract data from the row
    const name = row.cells[0].innerText;
    const phone = row.cells[1].innerText;
    const service = row.cells[3].innerText;
    const subservice = row.cells[4].innerText;
    const date = row.cells[6].innerText;
    const amount = row.cells[5].innerText;

    // Clinic Details
    const clinicName = "Dental World Clinic";
    const clinicAddress = "Poblacion, Tagoloan Misamis Oriental, 9001";
    const clinicContact = "Contact: 0917-885-5153";

    // Logo URL (ensure the image path is publicly accessible)
    const logoUrl = "Documentation/logo.png"; 
    pdf.addImage(logoUrl, 'PNG', 10, 10, 30, 30);

    pdf.setFontSize(18);
    pdf.setFont("helvetica", "bold");
    pdf.text(clinicName, 45, 20);
    pdf.setFontSize(10);
    pdf.setFont("helvetica", "normal");
    pdf.text(clinicAddress, 45, 26);
    pdf.text(clinicContact, 45, 32);

    pdf.setFontSize(14);
    pdf.text("Booking Successful", 105, 50, null, null, "center");

    // Add appointment details
    const details = [
      { label: "Name", value: name },
      { label: "Phone", value: phone },
      { label: "Service", value: service },
      { label: "Sub Service", value: subservice },
      { label: "Date", value: date },
      { label: "Amount", value: amount }
    ];

    let yPos = 60;
    pdf.setFontSize(12);
    details.forEach(item => {
      pdf.setFont("helvetica", "bold");
      pdf.text(`${item.label}:`, 20, yPos);
      pdf.setFont("helvetica", "normal");
      pdf.text(`${item.value}`, 60, yPos);
      yPos += 8;
    });

    // Generate QR code for the booking (could encode the booking ID or receipt info)
    const qrCodeData = `Name: ${name}\nService: ${service}\nSub Service: ${subservice}\nDate: ${date}\nAmount: ${amount}`;
    QRCode.toDataURL(qrCodeData, { width: 100, margin: 2 }, (err, url) => {
      if (err) {
        console.error(err);
      } else {
        // Add QR Code to PDF
        pdf.addImage(url, 'PNG', 150, 60, 50, 50);
        
        // Save the generated PDF
        pdf.setFontSize(10);
        pdf.text("Thank you for choosing Dental World Clinic!", 105, yPos + 20, null, null, "center");
        pdf.save(`${name}_booking_receipt.pdf`);
      }
    });
  }

  // Add event listener to all "Print Receipt" buttons in table rows
  document.querySelectorAll('.printReceiptButton').forEach(button => {
    button.addEventListener('click', function() {
      // Get the parent row (tr) of the button clicked
      const row = this.closest('tr');
      // Call function to generate and print PDF for that row's data
      generatePDF(row);
    });
  });
</script>

<!-- Modal -->
<div id="appointmentModal" class="modal-overlay" style="display: none;">
  <div class="modal-card">
      <span id="closeModal" class="close-modal">&times;</span>
      <div class="modal-header">
          <h2>Appointment Details</h2>
      </div>
      <div id="modal-details" class="modal-body">
          <!-- Dynamic Details -->
      </div>
      <div class="modal-footer">
          <button id="printDetailsBtn" class="modal-button print-btn">Print</button>
          <button id="cancelModalBtn" class="modal-button cancel-btn">Close</button>
      </div>
  </div>
</div>

  


  </div>

  <!-- Modal for Receipt -->
  <div id="receiptModal" class="modal" style="display:none;">
    <div class="modal-content">
      <button class="close-button" id="closeModal">&times;</button>
      <h3>Booking Successful</h3>
      <p id="receiptDetails"></p>
      <canvas id="qrCodeCanvas"></canvas>
      <br>
      <button id="printPdfButton" style="margin-top: 15px;">Print PDF</button>
      <button id="dismissModal" style="margin-top: 15px;">Close</button>
    </div>
  </div>

  <!-- Include jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

  <script>
    const printPdfButton = document.getElementById('printPdfButton');
    printPdfButton.addEventListener('click', function () {
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF();

      const clinicName = "Dental World Clinic";
      const clinicAddress = "Poblacion, Tagoloan Misamis Oriental, 9001";
      const clinicContact = "Contact: 0917-885-5153";

      const logoUrl = "Documentation/logo.png"; // Ensure the path is correct and hosted publicly
      pdf.addImage(logoUrl, 'PNG', 10, 10, 30, 30);

      pdf.setFontSize(18);
      pdf.setFont("helvetica", "bold");
      pdf.text(clinicName, 45, 20);
      pdf.setFontSize(10);
      pdf.setFont("helvetica", "normal");
      pdf.text(clinicAddress, 45, 26);
      pdf.text(clinicContact, 45, 32);

      pdf.setFontSize(14);
      pdf.text("Appointment Receipt", 105, 50, null, null, "center");

      const details = [
        { label: "Name", value: document.getElementById('name').value },
        { label: "Phone", value: document.getElementById('phone').value },
        { label: "Address", value: document.getElementById('address').value },
        { label: "Service", value: document.getElementById('service').value },
        { label: "Amount", value: document.getElementById('amount').value },
        { label: "Date", value: document.getElementById('date').value },
        { label: "Time", value: document.getElementById('time').value },
      ];

      let yPos = 60;
      pdf.setFontSize(12);
      details.forEach(item => {
        pdf.setFont("helvetica", "bold");
        pdf.text(`${item.label}:`, 20, yPos);
        pdf.setFont("helvetica", "normal");
        pdf.text(`${item.value}`, 60, yPos);
        yPos += 8;
      });

      pdf.setFontSize(10);
      pdf.text("Thank you for choosing Dental World Clinic!", 105, yPos + 10, null, null, "center");

      // Save as PDF
      pdf.save("appointment_receipt.pdf");
    });
  </script>


<script>
  function removeRow(button, id) {
    if (confirm('Are you sure you want to cancel this booking?')) {
      const row = button.closest('tr');

      // Send an HTTP request to the backend to delete the record
      fetch(`/appointments/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          'Content-Type': 'application/json'
        },
      })
      .then(response => {
        if (response.ok) {
          // Remove the row from the table if the backend confirms deletion
          row.remove();
          alert('Booking successfully cancelled.');
        } else {
          alert('Failed to cancel booking. Please try again.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
      });
    }
  }
</script>


  <!-- Include QRCode.js -->
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
  
  <script>
   
  </script>


</body>
</html>

