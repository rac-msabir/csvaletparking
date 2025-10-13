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

        // List tenant users instead of Tenant records
        $tenants = User::where('is_tenant_admin', true)
            ->select(['id', 'name', 'domain', 'is_active', 'created_at'])
            ->latest()
            ->paginate(10)
            ->toArray();

        // Transform items to match UI expectations
        $tenants['data'] = array_map(function ($user) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'domain' => $user['domain'] ?? null,
                'is_active' => $user['is_active'] ?? true,
                'created_at' => \Carbon\Carbon::parse($user['created_at'])->format('M d, Y'),
            ];
        }, $tenants['data']);

        return Inertia::render('SuperAdmin/Dashboard/Index', [
            'tenants' => $tenants,
        ]);
    }
}
