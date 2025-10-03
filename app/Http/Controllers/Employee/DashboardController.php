<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the employee dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $tenantId = Auth::user()->tenant_id;
        $today = Carbon::today()->toDateString();
        
        $stats = [
            'assigned_tickets' => Ticket::where('assigned_to', $userId)
                ->whereIn('status', ['open', 'in_progress'])
                ->count(),
            'completed_today' => Ticket::where('assigned_to', $userId)
                ->where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->count(),
            'avg_resolution_time' => $this->getAverageResolutionTime($userId),
        ];

        return Inertia::render('Employee/Dashboard', [
            'stats' => $stats,
        ]);
    }

    /**
     * Calculate the average resolution time for tickets.
     *
     * @param int $userId
     * @return string
     */
    private function getAverageResolutionTime($userId)
    {
        $averageMinutes = Ticket::where('assigned_to', $userId)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, completed_at)) as avg_resolution_time')
            ->value('avg_resolution_time');

        if (!$averageMinutes) {
            return 'N/A';
        }

        if ($averageMinutes < 60) {
            return round($averageMinutes) . 'm';
        }

        $hours = floor($averageMinutes / 60);
        $minutes = $averageMinutes % 60;
        
        return $hours . 'h ' . $minutes . 'm';
    }

    /**
     * Get tasks assigned to the employee.
     *
     * @return \Illuminate\Http\JsonResponse
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
