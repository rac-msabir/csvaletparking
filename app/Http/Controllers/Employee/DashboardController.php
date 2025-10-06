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
        
        // Get stats
        $stats = [
            'active_tickets' => Ticket::where('assigned_to', $userId)
                ->whereIn('status', ['open', 'in_progress'])
                ->count(),
            'employees_count' => $user->tenant->users()
                ->where('is_employee', true)
                ->count(),
            // 'avg_response_time' => $this->getAverageResponseTime($userId),
        ];

        // Get recent tickets (last 5)
        $recentTickets = Ticket::where('assigned_to', $userId)
            // ->with('customer')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->title,
                    'reference' => 'TKT-' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT),
                    'customer_name' => $ticket->customer ? $ticket->customer->name : 'N/A',
                    'created_at' => $ticket->created_at->diffForHumans(),
                    'status' => ucfirst(str_replace('_', ' ', $ticket->status)),
                    'statusClass' => $this->getStatusClass($ticket->status),
                ];
            });

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
