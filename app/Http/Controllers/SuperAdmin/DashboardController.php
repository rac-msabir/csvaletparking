<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the super admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $stats = [
            'tenants_count' => Tenant::count(),
            'active_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
            'users_count' => User::count(),
        ];

        $tenants = Tenant::select(['id', 'name', 'is_active', 'created_at'])
            ->latest()
            ->paginate(10)
            ->toArray();

        // Transform the items array
        $tenants['data'] = array_map(function ($tenant) {
            return [
                'id' => $tenant['id'],
                'name' => $tenant['name'],
                'is_active' => $tenant['is_active'],
                'created_at' => \Carbon\Carbon::parse($tenant['created_at'])->format('M d, Y'),
            ];
        }, $tenants['data']);

        return Inertia::render('SuperAdmin/Dashboard/Index', [
            'tenants' => $tenants,
        ]);
    }
}
