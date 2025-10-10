<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $ticket->ticket_number }} - Print</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: 80mm 297mm; /* 80mm width, auto height */
                margin: 0;
                padding: 10px;
            }
            body {
                width: 100%;
                margin: 0;
                padding: 10px;
                font-size: 12px;
                line-height: 1.2;
            }
            .no-print {
                display: none !important;
            }
            .ticket {
                width: 100%;
                border: 1px dashed #ccc;
                padding: 10px;
                box-sizing: border-box;
            }
        }
        .ticket {
            max-width: 80mm;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        .ticket-info {
            margin-bottom: 10px;
        }
        .ticket-info p {
            margin: 3px 0;
        }
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        .qr-code img {
            max-width: 120px;
            height: auto;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body class="bg-white">
    <div class="no-print" style="text-align: center; margin: 20px 0;">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Print Ticket
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-4">
            Close
        </button>
    </div>

    <div class="ticket">
        <div class="header">
            <h1 class="text-lg font-bold">Valet Parking Ticket</h1>
            <p class="text-sm">{{ config('app.name') }}</p>
        </div>

        <div class="ticket-info">
            <p><strong>Ticket #:</strong> {{ $ticket->ticket_number }}</p>
            <p><strong>Date:</strong> {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
        </div>

        <div class="customer-info">
            <h2 class="font-bold border-b border-gray-200 mb-2">Customer Information</h2>
            <p><strong>Name:</strong> {{ $ticket->customer_name }}</p>
            <p><strong>Phone:</strong> {{ $ticket->customer_phone }}</p>
        </div>

        <div class="vehicle-info mt-3">
            <h2 class="font-bold border-b border-gray-200 mb-2">Vehicle Information</h2>
            <p><strong>Make:</strong> {{ $ticket->vehicle_make }}</p>
            <p><strong>Model:</strong> {{ $ticket->vehicle_model }}</p>
            <p><strong>Color:</strong> {{ $ticket->vehicle_color }}</p>
            <p><strong>License Plate:</strong> {{ $ticket->license_plate }}</p>
            @if($ticket->parking_spot)
                <p><strong>Parking Spot:</strong> {{ $ticket->parking_spot }}</p>
            @endif
        </div>

        @if($ticket->special_instructions)
        <div class="special-instructions mt-3">
            <h2 class="font-bold border-b border-gray-200 mb-2">Special Instructions</h2>
            <p>{{ $ticket->special_instructions }}</p>
        </div>
        @endif

        @if($qrCodeUrl)
        <div class="qr-code mt-4">
            <img class="w-72 h-72" src="{{ $qrCodeUrl }}" alt="QR Code">
            <p>Scan to verify ticket</p>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for using our valet service!</p>
            <p>{{ config('app.url') }}</p>
        </div>
    </div>

    <script>
        // Auto-print when the page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
                // Close the window after printing (some browsers block this)
                // window.onafterprint = function() {
                //     window.close();
                // };
            }, 500);
        };
    </script>
</body>
</html>
