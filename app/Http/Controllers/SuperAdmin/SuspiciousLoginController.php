<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuspiciousLogin;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuspiciousLoginController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SuspiciousLogin::class, 'suspiciousLogin');
    }

    public function index()
    {
        $suspiciousLogins = SuspiciousLogin::with('user')
            ->latest()
            ->paginate(15);

        return Inertia::render( 'SuperAdmin/SuspiciousLogins/Index', [
            'suspiciousLogins' => $suspiciousLogins
        ]);
    }

    public function show(SuspiciousLogin $suspiciousLogin)
    {
        $suspiciousLogin->load('user');
        
        return Inertia::render('SuperAdmin/SuspiciousLogins/Show', [
            'suspiciousLogin' => $suspiciousLogin
        ]);
    }

    public function markAsNotified(SuspiciousLogin $suspiciousLogin)
    {
        $suspiciousLogin->update(['notified' => true]);
        
        return back()->with('success', 'Marked as notified');
    }
}