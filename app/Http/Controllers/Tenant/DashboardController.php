<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the tenant admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        
        $stats = [
            'active_tickets' => Ticket::where('tenant_id', $tenantId)
                ->whereIn('status', ['open', 'in_progress'])
                ->count(),
            'employees_count' => User::where('tenant_id', $tenantId)
                ->where('is_employee', true)
                ->count(),
            'avg_response_time' => $this->getAverageResponseTime($tenantId),
        ];

        return Inertia::render('Tenant/Dashboard', [
            'stats' => $stats,
        ]);
    }

    /**
     * Calculate the average response time for tickets.
     *
     * @param int $tenantId
     * @return string
     */
    private function getAverageResponseTime($tenantId)
    {
        $averageMinutes = Ticket::where('tenant_id', $tenantId)
            ->whereNotNull('first_response_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_response_time')
            ->value('avg_response_time');

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
}
