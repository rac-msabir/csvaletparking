<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($tenant) => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'email' => $tenant->email,
                'phone' => $tenant->phone,
                'is_active' => $tenant->is_active,
                'created_at' => $tenant->created_at->format('M d, Y'),
            ]);

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('tenants', 'email'),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $tenant = Tenant::create($validated);

        // Create the tenant's database and run migrations
        // This would be implemented based on your multi-tenancy setup

        return redirect()
            ->route('super-admin.tenants.index')
            ->with('success', 'Tenant created successfully');
    }

    public function show($id)
    {
        $tenant = Tenant::findOrFail($id);
        return Inertia::render('SuperAdmin/Tenants/View', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'email' => $tenant->email,
                'phone' => $tenant->phone,
                'address' => $tenant->address,
                'is_active' => $tenant->is_active,
                'created_at' => $tenant->created_at->format('Y-m-d\TH:i:s'),
                'updated_at' => $tenant->updated_at->format('Y-m-d\TH:i:s'),
            ],
            'appDomain' => config('app.domain', 'example.com'),
        ]);
    }

    public function edit($id)
    {
        // dd($id);
        $tenant = Tenant::findOrFail($id);
        return Inertia::render('SuperAdmin/Tenants/Edit', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'email' => $tenant->email,
                'phone' => $tenant->phone,
                'address' => $tenant->address,
                'is_active' => $tenant->is_active,
                'created_at' => $tenant->created_at->format('Y-m-d\TH:i:s'),
                'updated_at' => $tenant->updated_at->format('Y-m-d\TH:i:s'),
            ],
            'appDomain' => config('app.domain', 'example.com'),
        ]);
    }
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('tenants', 'email')->ignore($tenant->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $tenant->update($validated);

        return redirect()->route('super-admin.tenants.index')->with('success', 'Tenant updated successfully');
    }

    public function destroy(Tenant $tenant)
    {
        // Prevent deleting active tenants if needed
        if ($tenant->is_active) {
            return back()->with('error', 'Cannot delete an active tenant. Please deactivate first.');
        }

        $tenant->delete();

        // Clean up tenant resources (database, storage, etc.)
        // This would be implemented based on your multi-tenancy setup

        return redirect()
            ->route('super-admin.tenants.index')
            ->with('success', 'Tenant deleted successfully');
    }

    public function toggleStatus($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $tenant->update([
            'is_active' => !$tenant->is_active
        ]);

        $status = $tenant->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('super-admin.tenants.index')->with('success', "Tenant {$status} successfully");
    }
}
