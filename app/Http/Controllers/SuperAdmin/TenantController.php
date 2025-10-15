<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function index()
    {
        // List tenant users (is_tenant_admin = true) instead of Tenant records
        $tenants = User::where('is_tenant_admin', true)
            ->select(['id', 'name', 'domain', 'email', 'phone', 'is_active', 'created_at'])
            ->latest()
            ->paginate(10)
            ->toArray();

        $tenants['data'] = array_map(function ($user) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'domain' => $user['domain'] ?? null,
                'email' => $user['email'] ?? null,
                'phone' => $user['phone'] ?? null,
                'is_active' => (bool) ($user['is_active'] ?? true),
                'created_at' => \Carbon\Carbon::parse($user['created_at'])->format('M d, Y'),
            ];
        }, $tenants['data']);

        return Inertia::render('SuperAdmin/Tenants/Index', [
            'tenants' => $tenants,
            'filters' => request()->all(['search', 'status']),
        ]);
    }

    public function create()
    {
        return Inertia::render('SuperAdmin/Tenants/Create', [
            'appDomain' => config('app.domain', 'example.com'),
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $isActive = $request->boolean('is_active', true);

        // Create a User instead of a Tenant for the new tenant signup
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'domain' => $validated['domain'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make(Str::random(16)),
            'is_active' => $isActive,
            'is_tenant_admin' => true,
            'address' => $validated['address'] ?? null,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('super-admin.tenants.index')
            ->with('success', 'Tenant admin user created successfully');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('SuperAdmin/Tenants/View', [
            'tenant' => [
                'id' => $user->id,
                'name' => $user->name,
                'domain' => $user->domain,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'is_active' => (bool) $user->is_active,
                'created_at' => $user->created_at->format('Y-m-d\TH:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d\TH:i:s'),
            ],
            'appDomain' => config('app.domain', 'example.com'),
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('SuperAdmin/Tenants/Edit', [
            'tenant' => [
                'id' => $user->id,
                'name' => $user->name,
                'domain' => $user->domain,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'is_active' => (bool) $user->is_active,
                'created_at' => $user->created_at->format('Y-m-d\TH:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d\TH:i:s'),
            ],
            'appDomain' => config('app.domain', 'example.com'),
        ]);
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'domain' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'address' => 'nullable|string',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'domain' => $validated['domain'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active', $user->is_active),
            'address' => $validated['address'] ?? null,
        ];

        $user->update($payload);

        return redirect()->route('super-admin.tenants.index')->with('success', 'Tenant user updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_active) {
            return back()->with('error', 'Cannot delete an active tenant user. Please deactivate first.');
        }

        $user->delete();

        return redirect()
            ->route('super-admin.tenants.index')
            ->with('success', 'Tenant user deleted successfully');
    }

    public function toggleStatus($id)
    {
        // Toggle status for tenant admin user instead of Tenant model
        $user = User::findOrFail($id);

        $user->update([
            'is_active' => ! (bool) $user->is_active,
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('super-admin.tenants.index')->with('success', "Tenant user {$status} successfully");
    }
}
