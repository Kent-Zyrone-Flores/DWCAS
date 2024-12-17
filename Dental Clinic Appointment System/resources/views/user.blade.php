<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental World Clinic</title>
  <link rel="stylesheet" href="css/user.css">
  <style>
    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      width: 400px;
      text-align: center;
      position: relative;
    }
    .modal-content h3 {
      margin-top: 0;
    }
    .modal-content button {
      margin-top: 15px;
    }
    .close-button {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }
  </style>
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
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Full Name" required>

        <label for="phone">Contact No.</label>
        <input type="tel" id="phone" name="phone" placeholder="Contact Number" required>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" placeholder="Address" required>

        <label for="service">Service</label>
        <select id="service" name="service" required>
          <option value="General Dentistry" data-price="₱1000">General Dentistry</option>
          <option value="Orthodontics" data-price="₱1500">Orthodontics</option>
          <option value="Cosmetic Dentistry" data-price="₱2000">Cosmetic Dentistry</option>
          <option value="Pediatric Dentistry" data-price="₱2500">Pediatric Dentistry</option>
          <option value="Specialized Procedures" data-price="₱3000">Specialized Procedures</option>
        </select>

        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" placeholder="Amount" readonly required>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" required>

        <label for="time">Time</label>
        <select type="text" id="time" name="time" required>
          <option value="12:00 PM">12:00 PM</option>
          <option value="1:00 PM">1:00 PM</option>
          <option value="8:00 AM">8:00 AM</option>
          <option value="6:00 PM">6:00 PM</option>
          <option value="3:00 PM">3:00 PM</option>
        </select>

        <center>
        <button type="button" id="bookButton">Book</button>
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
              <td>{{ $appointment->amount }}</td>
              <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</td>
              <td>{{ $appointment->time }}</td>
              <td>{{ $appointment->status }}</td>
              <td>
  <button 
    class="cancel-button" 
    onclick="removeRow(this, '{{ $appointment->id }}')"
  >
    Cancel
  </button>
</td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal for Receipt -->
  <div id="receiptModal" class="modal">
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

  // Clinic Information
  const clinicName = "Dental World Clinic";
  const clinicAddress = "Poblacion, Tagoloan Misamis Oriental, 9001";
  const clinicContact = "Contact: 0917-885-5153";

  // Add a logo (Optional)
  const logoUrl = "Documentation/logo.png"; // Ensure the path is correct and hosted publicly
  pdf.addImage(logoUrl, 'PNG', 10, 10, 30, 30);

  // Title and Clinic Info
  pdf.setFontSize(18);
  pdf.setFont("helvetica", "bold");
  pdf.text(clinicName, 45, 20); // Align next to logo
  pdf.setFontSize(10);
  pdf.setFont("helvetica", "normal");
  pdf.text(clinicAddress, 45, 26);
  pdf.text(clinicContact, 45, 32);

  // Receipt Title
  pdf.setFontSize(14);
  pdf.text("Appointment Receipt", 105, 50, null, null, "center");

  // Receipt Details
  const details = [
    { label: "Name", value: document.getElementById('name').value },
    { label: "Phone", value: document.getElementById('phone').value },
    { label: "Address", value: document.getElementById('address').value },
    { label: "Service", value: serviceSelect.options[serviceSelect.selectedIndex].text },
    { label: "Amount", value: amountInput.value },
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

  // QR Code
  const qrCanvas = document.getElementById('qrCodeCanvas');
  const qrImage = qrCanvas.toDataURL('image/png');
  pdf.addImage(qrImage, 'PNG', 80, yPos + 10, 50, 50); // Position below details

  // Footer
  pdf.setFontSize(10);
  pdf.text("Thank you for choosing Dental World Clinic!", 105, yPos + 70, null, null, "center");

  // Save PDF
  pdf.save('receipt.pdf');
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
    const appointmentForm = document.getElementById('appointmentForm');
    const serviceSelect = document.getElementById('service');
    const amountInput = document.getElementById('amount');
    const bookButton = document.getElementById('bookButton');
    const receiptModal = document.getElementById('receiptModal');
    const receiptDetails = document.getElementById('receiptDetails');
    const closeModalButton = document.getElementById('closeModal');
    const dismissModalButton = document.getElementById('dismissModal');
    const qrCodeCanvas = document.getElementById('qrCodeCanvas'); // QR Code canvas

    // Update amount based on selected service
    serviceSelect.addEventListener('change', function () {
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
      const price = selectedOption.getAttribute('data-price');
      amountInput.value = price;
    });

    // Show modal with receipt details after booking
    bookButton.addEventListener('click', function () {
      const name = document.getElementById('name').value;
      const phone = document.getElementById('phone').value;
      const address = document.getElementById('address').value;
      const service = serviceSelect.options[serviceSelect.selectedIndex].text;
      const amount = amountInput.value;
      const date = document.getElementById('date').value;
      const time = document.getElementById('time').value;
      const today = new Date();
      today.setHours(0, 0, 0, 0); 
      const selectedDate = new Date(document.getElementById('date').value);
      const selectedTime = document.getElementById('time').value;
      const currentTime = today.getHours() * 100 + today.getMinutes(); // Convert current time to HHMM format
      const timeMap = {
        '12:00 PM': 1200,
        '1:00 PM': 1300,
        '8:00 AM': 800,
        '6:00 PM': 1800,
        '3:00 PM': 1500
    };
    const selectedTimeFormatted = timeMap[selectedTime];
     // Check if the selected date is in the past
     if (selectedDate < today) {
        alert('You cannot book an appointment for a past date.');
        return;
    }

    // Check if the selected time is in the past (for today's date)
    if (selectedDate.toDateString() === today.toDateString() && selectedTimeFormatted < currentTime) {
        alert('You cannot book an appointment for a past time.');
        return;
    }
      if (name && phone && address && service && amount && date && time) {
        receiptDetails.innerHTML = `
          <strong>Name:</strong> ${name}<br>
          <strong>Phone:</strong> ${phone}<br>
          <strong>Address:</strong> ${address}<br>
          <strong>Service:</strong> ${service}<br>
          <strong>Amount:</strong> ${amount}<br>
          <strong>Date:</strong> ${date}<br>
          <strong>Time:</strong> ${time}<br>
        `;

        // Generate QR Code
        QRCode.toCanvas(qrCodeCanvas, `Name: ${name}\nPhone: ${phone}\nService: ${service}\nAmount: ${amount}\nDate: ${date}\nTime: ${time}`, {
          width: 200,
          margin: 2,
        }, function (error) {
          if (error) console.error(error);
          console.log('QR Code generated!');
        });

        // Show the modal
        receiptModal.style.display = 'flex';

        // Save booking data when modal opens
        fetch("{{ route('user.submit') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: JSON.stringify({
            name,
            phone,
            address,
            service,
            amount,
            date,
            time
          })
        }).then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        }).then(data => {
          console.log('Booking saved:', data);
        }).catch(error => {
          console.error('Error saving booking:', error);
        });
      } else {
        alert('Please fill out all fields before booking.');
      }
    });

    // Close modal with close button
    closeModalButton.addEventListener('click', function () {
      receiptModal.style.display = 'none';
    });

    dismissModalButton.addEventListener('click', function () {
      receiptModal.style.display = 'none';
    });

    // Initialize amount field on page load
    serviceSelect.dispatchEvent(new Event('change'));

    function removeRow(button) {
      const row = button.closest('tr');
      row.remove();
    }
  </script>


</body>
</html>

