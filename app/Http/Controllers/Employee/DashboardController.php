<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the employee dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $tenantId = $user->tenant_id;
        $today = Carbon::today()->toDateString();

        
        // Get stats expected by UI
        $stats = [
            // New = pending tickets assigned to this employee
            'new_count' => Ticket::where('assigned_to', $userId)
                ->where('status', Ticket::STATUS_PENDING)
                ->count(),
            // Requested = in_progress tickets assigned to this employee
            'requested_count' => Ticket::where('assigned_to', $userId)
                ->where('status', Ticket::STATUS_IN_PROGRESS)
                ->count(),
            // Delivered = delivered tickets assigned to this employee
            'delivered_count' => Ticket::where('assigned_to', $userId)
                ->where('status', Ticket::STATUS_DELIVERED)
                ->count(),
        ];

        // Tickets list used by UI table (provide fields the Vue expects)
        $recentTickets = Ticket::where('assigned_to', $userId)
            ->latest()
            ->get()
            ->map(function (Ticket $ticket) {
                $reference = $ticket->ticket_number ?: ('TKT-' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT));
                $needAt = $ticket->check_out_at ? $ticket->check_out_at->format('M d, Y h:ia') : null;
                $totalPrice = $ticket->amount ? number_format((float) $ticket->amount, 2) : null;

                return [
                    'id' => $ticket->id,
                    'reference' => $reference,
                    'status' => $ticket->status,
                    'status_label' => $ticket->status_label,
                    'need_at' => $ticket->need_at,
                    'customer_phone' => $ticket->customer_phone,
                    'total_price' => $totalPrice ? ($totalPrice . ($ticket->payment_status ? (' / ' . ucfirst($ticket->payment_status)) : '')) : 'Free',
                    'car_brand' => $ticket->vehicle_make,
                    'car_plate' => $ticket->license_plate,
                    'note' => $ticket->special_instructions,
                    'qr_url' => $ticket->qr_code_url,
                ];
            });

            // dd($recentTickets);

        return Inertia::render('Employee/Dashboard', [
            'stats' => $stats,
            'recentTickets' => $recentTickets,
        ]);
    }

    /**
     * Calculate the average response time for tickets.
     *
     * @param int $userId
     * @return string
     */
    private function getAverageResponseTime($userId)
    {
        $averageMinutes = Ticket::where('assigned_to', $userId)
            ->whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_response_time')
            ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, first_response_at)'));

        if (!$averageMinutes) {
            return '0m';
        }

        if ($averageMinutes < 60) {
            return round($averageMinutes) . 'm';
        }

        $hours = floor($averageMinutes / 60);
        $minutes = round($averageMinutes % 60);

        return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
    }
    
    /**
     * Get the appropriate status class for a ticket status.
     *
     * @param string $status
     * @return string
     */
    private function getStatusClass($status)
    {
        $status = strtolower($status);
        
        return match($status) {
            'open' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get tasks assigned to the employee.
     *
     */
    public function tasks()
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->with(['assignedTo', 'ticket'])
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return response()->json($tasks);
    }
}
