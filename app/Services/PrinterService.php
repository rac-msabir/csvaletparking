<?php

namespace App\Services;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\EscposImage;
use Exception;

class PrinterService
{
    protected $printer;
    protected $printerIp;
    protected $printerPort;

    public function __construct()
    {
        $this->printerIp = config('services.printer.ip');
        $this->printerPort = config('services.printer.port');
    }

    /**
     * Initialize printer connection
     */
    protected function initPrinter()
    {
        try {
            $connector = new NetworkPrintConnector($this->printerIp, $this->printerPort);
            $this->printer = new Printer($connector);
            return true;
        } catch (Exception $e) {
            \Log::error('Printer connection failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Print QR code with header and footer
     * 
     * @param string $ticketId
     * @param string $header
     * @param string $footer
     * @return array
     */
    public function printQrTicket($ticketId, $header = 'TICKET', $footer = 'Thank you for your business!')
    {
        if (!$this->initPrinter()) {
            return ['status' => 'error', 'message' => 'Could not connect to printer'];
        }

        try {
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            
            // Print header
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
            $this->printer->text("\n" . strtoupper($header) . "\n");
            $this->printer->selectPrintMode();
            
            $this->printer->text("#" . $ticketId . "\n\n");
            
            // Generate and print QR code
            $this->printer->qrCode("ticket:" . $ticketId, Printer::QR_ECLEVEL_L, 10);
            $this->printer->text("\nSCAN TO VIEW DETAILS\n\n");
            
            // Print footer
            $this->printer->text("\n" . $footer . "\n");
            
            // Cut paper
            $this->printer->cut();
            
            // Close connection
            $this->printer->close();
            
            return ['status' => 'success', 'message' => 'Ticket printed successfully'];
            
        } catch (Exception $e) {
            \Log::error('Printing failed: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Printing failed: ' . $e->getMessage()];
        }
    }
}
