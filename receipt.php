<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(rgba(255, 218, 185, 0.3), rgba(255, 218, 185, 0.5)), url('images/background.jpg');
            background-size: cover;
            background-position: center;
            padding: 2rem;
        }

        .receipt-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .receipt-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, #FFDAB9, #e67e22);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #FFDAB9;
            position: relative;
        }

        .receipt-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background-color: #e67e22;
        }

        .receipt-header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .receipt-header .logo {
            font-size: 1.8rem;
            color: #e67e22;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .receipt-header .receipt-date {
            color: #666;
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .total-section {
            background: #f8f8f8;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .total-amount {
            text-align: right;
            padding: 1.5rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .total-amount .amount {
            font-size: 1.8rem;
            color: #e67e22;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .total-amount .amount-in-words {
            color: #666;
            font-size: 1.1rem;
            font-style: italic;
            margin-top: 0.5rem;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            color: rgba(230, 126, 34, 0.03);
            pointer-events: none;
            white-space: nowrap;
            font-weight: bold;
        }

        @media print {
            .watermark {
                display: none;
            }
        }

        .receipt-info {
            margin: 2rem 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .info-group {
            margin-bottom: 1rem;
        }

        .info-group label {
            font-weight: bold;
            color: #666;
            display: block;
            margin-bottom: 0.5rem;
        }

        .info-group span {
            color: #333;
            font-size: 1.1rem;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        .items-table th,
        .items-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .items-table th {
            background-color: #FFDAB9;
            color: #333;
            font-weight: bold;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .print-btn, .back-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .print-btn {
            background-color: #e67e22;
            color: white;
        }

        .back-btn {
            background-color: #FFDAB9;
            color: #333;
        }

        .print-btn:hover, .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .receipt-footer {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #ddd;
            color: #666;
        }

        .important-notice {
            margin: 2rem 0;
            padding: 1.5rem;
            background-color: #fff3e0;
            border: 2px solid #FFDAB9;
            border-radius: 8px;
            color: #333;
        }

        .important-notice h3 {
            color: #e67e22;
            margin-bottom: 1rem;
        }

        .important-notice ul {
            list-style-type: none;
            padding: 0;
        }

        .important-notice li {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .important-notice li:before {
            content: "•";
            color: #e67e22;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                max-width: 100%;
                margin: 0;
            }

            .action-buttons {
                display: none;
            }

            .receipt-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">Event Waale</div>
        <div class="receipt-header">
            <div class="logo">Event Waale</div>
            <h1>Booking Receipt</h1>
            <div class="receipt-date" id="receiptDate"></div>
        </div>

        <div class="receipt-info">
            <div class="customer-info">
                <div class="info-group">
                    <label>Customer Name:</label>
                    <span id="customerName"></span>
                </div>
                <div class="info-group">
                    <label>Mobile Number:</label>
                    <span id="customerMobile"></span>
                </div>
                <div class="info-group">
                    <label>Event Date:</label>
                    <span id="eventDate"></span>
                </div>
            </div>
            <div class="event-info">
                <div class="info-group">
                    <label>Number of Guests:</label>
                    <span id="guestCount"></span>
                </div>
                <div class="info-group">
                    <label>Booking ID:</label>
                    <span id="bookingId"></span>
                </div>
                <div class="info-group">
                    <label>Booking Date:</label>
                    <span id="bookingDate"></span>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Price Details</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="itemsList">
                <!-- Items will be populated by JavaScript -->
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-amount" id="totalAmount">
                Total Amount: ₹0
            </div>
        </div>

        <div class="important-notice">
            <h3>⚠️ Important Instructions</h3>
            <ul>
                <li>Please print this receipt and submit it to our Event Waale center for verification.</li>
                <li>Your booking will be confirmed only after verification and payment collection.</li>
                <li><strong>Center Address:</strong> Event Waale Center, Shop No. 123, Crystal Plaza, Near City Mall, Nashik Road, Nashik - 422101</li>
                <li><strong>Center Timings:</strong> Monday to Saturday, 10:00 AM to 7:00 PM</li>
                <li><strong>Contact:</strong> +91 1234567890</li>
            </ul>
        </div>

        <div class="action-buttons">
            <button class="print-btn" onclick="window.print()">
                🖨️ Print Receipt
            </button>
            <a href="index.php#ceremonies" class="back-btn">
                ← Back to Home
            </a>
        </div>

        <div class="receipt-footer">
            <p>This is a computer-generated receipt. No signature required.</p>
            <p>For any queries, please contact us at support@eventwaale</p>
            <p style="margin-top: 1rem;"><strong>Note:</strong> This is a provisional booking receipt. Final confirmation will be provided after verification at our center.</p>
        </div>
    </div>

    <?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn'])) {

    $to = "somaprem103@gmail.com";
    $subject = "🎉 Booking Confirmation - Event Waale 🎉";  
    
    $message = "
    <html>
    <head>
        <title>Booking Confirmation - Event Waale</title>
    </head>
    <body style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
    
        <div style='max-width: 600px; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
    
            <h2 style='color: #2c3e50; text-align: center;'>🎊 Booking Confirmed! 🎊</h2>
            
            <p style='color: #555;'>Dear Customer,</p>
            <p style='color: #333;'>Thank you for choosing <strong style='color: #e74c3c;'>Event Waale</strong>. We are excited to inform you that your booking request has been successfully received. 🎟️</p>
            
            <hr style='border: 1px solid #ddd;'>
    
            <h3 style='color: #3498db;'>📌 Important Instructions:</h3>
            <ul style='color: #555;'>
                <li>🖨️ <strong>Please print the receipt that is provided on the website at the time of booking</strong> and submit it to our <strong>Event Waale Center</strong> for verification.</li>
                <li>💳 Your booking will be <strong>confirmed only after verification and payment collection.</strong></li>
            </ul>
    
            <h3 style='color: #27ae60;'>🏢 Event Waale Center Details:</h3>
            <p><strong>📍 Address:</strong> Shop No. 123, Crystal Plaza, Near City Mall, Nashik Road, Nashik - 422101</p>
            <p><strong>🕘 Timings:</strong> Monday to Saturday, 10:00 AM to 7:00 PM</p>
            <p><strong>📞 Contact:</strong> +91 1234567890</p>
    
            <hr style='border: 1px solid #ddd;'>
    
            <p style='color: #777;'>If you have any queries, feel free to contact us. We look forward to making your event special! 🎶🎤</p>
    
            <p style='color: #888; text-align: center;'>Best Regards,<br>
            <strong style='color: #e67e22;'>Event Waale Team</strong> 🚀</p>
        </div>
    
    </body>
    </html>
    ";
    
    $headers = "From: support@eventwaale.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
}


?>


    <script>
        // Function to convert number to words
        function numberToWords(num) {
            const units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten"];
            const teens = ["Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
            const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
            const scales = ["", "Thousand", "Lakh", "Crore"];

            if (num === 0) return "Zero";

            function processGroup(n) {
                if (n === 0) return "";
                else if (n < 11) return units[n];
                else if (n < 20) return teens[n-11];
                else if (n < 100) {
                    const unit = n % 10;
                    const ten = Math.floor(n / 10);
                    return tens[ten] + (unit !== 0 ? " " + units[unit] : "");
                }
                else {
                    const unit = n % 100;
                    const hundred = Math.floor(n / 100);
                    return units[hundred] + " Hundred" + (unit !== 0 ? " and " + processGroup(unit) : "");
                }
            }

            let words = "";
            let groupIndex = 0;
            
            while (num > 0) {
                let n = 0;
                if (groupIndex === 0) {
                    n = num % 1000;
                    num = Math.floor(num / 1000);
                } else {
                    n = num % 100;
                    num = Math.floor(num / 100);
                }
                
                if (n !== 0) {
                    let groupWords = processGroup(n);
                    if (groupIndex > 0 && groupWords !== "") {
                        groupWords += " " + scales[groupIndex];
                    }
                    words = groupWords + (words ? " " + words : "");
                }
                
                groupIndex++;
            }
            
            return words + " Rupees Only";
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Get booking details from localStorage
            const bookingDetails = JSON.parse(localStorage.getItem('bookingDetails') || '{}');
            const selectedItems = JSON.parse(localStorage.getItem('selectedItems') || '[]');

            // Set current date
            const today = new Date();
            document.getElementById('receiptDate').textContent = today.toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

            // Populate booking details
            document.getElementById('customerName').textContent = bookingDetails.name || 'N/A';
            document.getElementById('customerMobile').textContent = bookingDetails.mobile || 'N/A';
            document.getElementById('eventDate').textContent = bookingDetails.date || 'N/A';
            document.getElementById('guestCount').textContent = bookingDetails.guestCount || 'N/A';
            document.getElementById('bookingId').textContent = 'BK' + Math.random().toString(36).substr(2, 8).toUpperCase();
            document.getElementById('bookingDate').textContent = today.toLocaleDateString('en-IN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Populate items table
            const itemsList = document.getElementById('itemsList');
            let total = 0;

            selectedItems.forEach((item, index) => {
                const priceValue = parseInt(item.price.replace(/[^0-9]/g, ''));
                const row = document.createElement('tr');
                
                // Categories that don't need to be multiplied by guest count
                const nonMultiplyCategories = ['decoration', 'cradle', 'Electronic', 'cakes', 'flowers'];
                const guestCount = parseInt(bookingDetails.guestCount) || 1;
                
                let itemTotal;
                let priceDetails;

                if (nonMultiplyCategories.includes(item.category)) {
                    itemTotal = priceValue;
                    priceDetails = `₹${priceValue}`;
                } else {
                    // For furniture and other items, multiply by guest count
                    itemTotal = priceValue * guestCount;
                    priceDetails = `₹${priceValue} × ${guestCount} guests`;
                }

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.category}</td>
                    <td class="text-right">₹${itemTotal}</td>
                `;
                itemsList.appendChild(row);
                total += itemTotal;
            });

            // Update total amount with words
            const totalElement = document.getElementById('totalAmount');
            totalElement.innerHTML = `
                <div class="amount">Total Amount: ₹${total}</div>
                <div class="amount-in-words">${numberToWords(total)}</div>
            `;
        });
    </script>
</body>
</html>
