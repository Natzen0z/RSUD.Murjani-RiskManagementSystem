@extends('layouts.app')

@section('title', 'Kelola User - Admin Panel')

@section('content')
<div x-data="{ showModal: false, editUser: null }" x-init="setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100)">
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
            <a href="{{ route('admin.users') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl bg-amber-600 text-white shadow-lg shadow-amber-900/50">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium">Kelola User</span>
            </a>
            <a href="{{ route('admin.risks') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
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
        <header class="mb-8 flex justify-between items-center">
            <div>
               <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Kelola User</h1>
               <p class="text-slate-500 mt-1">Lihat dan kelola pengguna sistem</p>
            </div>
            <button @click="showModal = true; editUser = null" class="bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-xl flex items-center font-semibold transition-all">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                Tambah User
            </button>
        </header>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center text-green-700">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-center">Jumlah Risiko</th>
                            <th class="px-6 py-4">Dibuat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-700">{{ $user->risks_count }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($user->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-slate-300 text-xs">Anda</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add User Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showModal = false">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4" @click.stop>
            <h3 class="text-xl font-bold text-slate-800 mb-6">Tambah User Baru</h3>
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama</label>
                    <input type="text" name="name" required class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
                    <select name="role" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showModal = false" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 py-3 rounded-xl font-semibold transition-all">Batal</button>
                    <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-semibold transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
