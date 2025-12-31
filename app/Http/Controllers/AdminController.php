<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $totalRisks = Risk::count();
        $highRisks = Risk::all()->filter(fn($r) => in_array($r->awal_level, ['Tinggi', 'Kritis']))->count();
        $completedRisks = Risk::where('status', 'Completed')->count();
        $recentRisks = Risk::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalRisks', 'highRisks', 'completedRisks', 'recentRisks'
        ));
    }

    /**
     * Users management page
     */
    public function users(): View
    {
        $users = User::withCount('risks')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * All risks page
     */
    public function risks(): View
    {
        $risks = Risk::with('user')->orderBy('created_at', 'desc')->get();
        $units = Risk::distinct()->pluck('unit');
        return view('admin.risks', compact('risks', 'units'));
    }
}
