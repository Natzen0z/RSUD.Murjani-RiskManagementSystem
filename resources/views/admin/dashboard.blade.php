@extends('layouts.app')

@section('title', 'Admin Dashboard - Risk Management System')

@section('content')
<div x-data="adminDashboard()" x-init="init()">
    <!-- SIDEBAR (DESKTOP) -->
    <div class="w-64 bg-slate-900 text-white h-screen fixed left-0 top-0 p-4 flex flex-col shadow-xl z-50 hidden md:flex">
        <div class="mb-8 flex items-center space-x-2 px-2">
            <i data-lucide="shield-check" class="w-8 h-8 text-amber-400"></i>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Admin Panel</h1>
                <p class="text-xs text-slate-400">RSUD dr. Murjani</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl bg-amber-600 text-white shadow-lg shadow-amber-900/50">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium">Kelola User</span>
            </a>
            <a href="{{ route('admin.risks') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="font-medium">Semua Risiko</span>
            </a>
        </nav>

        <div class="mt-auto space-y-4">
            <div class="px-4 py-4 bg-slate-800 rounded-xl border border-slate-700">
                <div class="flex items-center mb-2 text-amber-400">
                   <i data-lucide="user-circle" class="w-4 h-4 mr-2"></i>
                   <p class="text-xs font-semibold uppercase tracking-wider">Admin</p>
                </div>
                <p class="font-bold text-sm text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-xl flex items-center justify-center font-semibold shadow-lg transition-all">
                    <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 transition-all duration-300 ease-in-out">
        
        <!-- HEADER -->
        <header class="mb-8 flex justify-between items-center">
            <div>
               <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Dashboard Admin</h1>
               <p class="text-slate-500 mt-1">Selamat datang, {{ Auth::user()->name }}</p>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalUsers }}</h3>
                <p class="text-slate-500 text-sm">Total User</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalRisks }}</h3>
                <p class="text-slate-500 text-sm">Total Risiko</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $highRisks }}</h3>
                <p class="text-slate-500 text-sm">Risiko Tinggi/Kritis</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $completedRisks }}</h3>
                <p class="text-slate-500 text-sm">Mitigasi Selesai</p>
            </div>
        </div>

        <!-- Recent Risks Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800">Risiko Terbaru (Semua Unit)</h2>
                <p class="text-slate-500 text-sm mt-1">Menampilkan 10 risiko terbaru dari semua user</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-4">Kode</th>
                            <th class="px-4 py-4">User</th>
                            <th class="px-4 py-4">Unit</th>
                            <th class="px-4 py-4">Risiko</th>
                            <th class="px-4 py-4 text-center">Level</th>
                            <th class="px-4 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentRisks as $risk)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-4 font-medium text-slate-900">{{ $risk->kode }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $risk->user ? $risk->user->name : 'N/A' }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $risk->unit }}</td>
                            <td class="px-4 py-4 text-slate-700 max-w-xs truncate">{{ $risk->risiko }}</td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $levelColors = [
                                        'Kritis' => 'bg-red-600 text-white',
                                        'Tinggi' => 'bg-orange-500 text-white',
                                        'Sedang' => 'bg-yellow-400 text-black',
                                        'Rendah' => 'bg-green-500 text-white',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $levelColors[$risk->awal_level] ?? 'bg-slate-200' }}">
                                    {{ $risk->awal_level }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusColors = [
                                        'Completed' => 'bg-emerald-100 text-emerald-800',
                                        'In-Progress' => 'bg-blue-100 text-blue-800',
                                        'Not Started' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$risk->status] ?? 'bg-gray-100' }}">
                                    {{ $risk->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                Belum ada data risiko.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.users') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl p-6 flex items-center transition-all">
                <i data-lucide="users" class="w-10 h-10 mr-4"></i>
                <div>
                    <h3 class="font-bold text-lg">Kelola User</h3>
                    <p class="text-blue-200 text-sm">Lihat dan kelola semua pengguna</p>
                </div>
            </a>
            <a href="{{ route('admin.risks') }}" class="bg-teal-600 hover:bg-teal-700 text-white rounded-2xl p-6 flex items-center transition-all">
                <i data-lucide="file-text" class="w-10 h-10 mr-4"></i>
                <div>
                    <h3 class="font-bold text-lg">Lihat Semua Risiko</h3>
                    <p class="text-teal-200 text-sm">Akses data risiko semua unit</p>
                </div>
            </a>
            <a href="{{ route('risk.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-2xl p-6 flex items-center transition-all">
                <i data-lucide="activity" class="w-10 h-10 mr-4"></i>
                <div>
                    <h3 class="font-bold text-lg">Risk Dashboard</h3>
                    <p class="text-purple-200 text-sm">Kembali ke dashboard risiko</p>
                </div>
            </a>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    function adminDashboard() {
        return {
            init() {
                setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
            }
        }
    }
</script>
@endpush
