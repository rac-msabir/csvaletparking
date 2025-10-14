<?php

namespace App\Http\Controllers;

use App\Services\PrinterService;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    protected $printerService;

    public function __construct(PrinterService $printerService)
    {
        $this->printerService = $printerService;
    }

    /**
     * Print a QR code for the given ticket ID
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function printQr($id)
    {
        $result = $this->printerService->printQrTicket(
            $id,
            'VALET TICKET',
            'Thank you for using our valet service!'
        );

        if ($result['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $result['message']
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket printed successfully'
        ]);
    }
}
