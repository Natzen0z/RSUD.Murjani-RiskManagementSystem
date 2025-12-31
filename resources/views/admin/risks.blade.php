@extends('layouts.app')

@section('title', 'Semua Risiko - Admin Panel')

@section('content')
<div x-data="{ searchTerm: '', filterUnit: '' }" x-init="setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100)">
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
            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium">Kelola User</span>
            </a>
            <a href="{{ route('admin.risks') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl bg-amber-600 text-white shadow-lg shadow-amber-900/50">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="font-medium">Semua Risiko</span>
            </a>
        </nav>

        <div class="mt-auto space-y-4">
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
    <main class="flex-1 md:ml-64 p-4 md:p-8">
        <header class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Semua Risiko</h1>
            <p class="text-slate-500 mt-1">Data risiko dari semua user dan unit</p>
        </header>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input x-model="searchTerm" type="text" placeholder="Cari risiko..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                </div>
            </div>
            <div>
                <select x-model="filterUnit" class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Risks Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-4">Kode</th>
                            <th class="px-4 py-4">User</th>
                            <th class="px-4 py-4">Unit</th>
                            <th class="px-4 py-4">Kategori</th>
                            <th class="px-4 py-4">Risiko</th>
                            <th class="px-4 py-4 text-center">Level Awal</th>
                            <th class="px-4 py-4 text-center">Level Sisa</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($risks as $risk)
                        <tr class="hover:bg-slate-50" 
                            x-show="(searchTerm === '' || '{{ strtolower($risk->risiko) }}'.includes(searchTerm.toLowerCase())) && (filterUnit === '' || '{{ $risk->unit }}' === filterUnit)">
                            <td class="px-4 py-4 font-medium text-slate-900">{{ $risk->kode }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $risk->user ? $risk->user->name : 'N/A' }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $risk->unit }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs">{{ $risk->kategori }}</span>
                            </td>
                            <td class="px-4 py-4 text-slate-700 max-w-xs">{{ Str::limit($risk->risiko, 50) }}</td>
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
                                    {{ $risk->awal_skor }} ({{ $risk->awal_level }})
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $levelColors[$risk->residual_level] ?? 'bg-slate-200' }}">
                                    {{ $risk->residual_skor }} ({{ $risk->residual_level }})
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
                            <td class="px-4 py-4">
                                @php
                                    $validasiColors = [
                                        'Valid' => 'bg-teal-100 text-teal-800',
                                        'Revisi' => 'bg-red-100 text-red-800',
                                        'Menunggu' => 'bg-slate-100 text-slate-600',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $validasiColors[$risk->validasi] ?? 'bg-slate-100' }}">
                                    {{ $risk->validasi }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
