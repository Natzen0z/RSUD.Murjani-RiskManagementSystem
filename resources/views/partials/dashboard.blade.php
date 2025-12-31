<!-- 1. DASHBOARD VIEW -->
<div x-show="activeTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
    
    <!-- Empty State -->
    <div x-show="riskData.length === 0" class="flex flex-col items-center justify-center h-96 text-center bg-white rounded-2xl border border-slate-200 border-dashed p-8">
        <i data-lucide="file-text" class="w-16 h-16 text-slate-300 mb-4"></i>
        <h3 class="text-lg font-bold text-slate-700">Belum Ada Data Risiko</h3>
        <p class="text-slate-500 max-w-md">Silakan masuk ke menu "Daftar Risiko" untuk mulai menambahkan data risiko secara manual.</p>
    </div>

    <!-- Stats & Charts -->
    <div x-show="riskData.length > 0" class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:-translate-y-1 transition-transform">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Risiko</p>
                    <h3 class="text-3xl font-bold text-slate-800" x-text="riskData.length"></h3>
                </div>
                <div class="p-3 rounded-xl bg-blue-500 bg-opacity-10 text-blue-600">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
            </div>
            <!-- Kritis/Tinggi -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:-translate-y-1 transition-transform">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Risiko Tinggi/Kritis</p>
                    <h3 class="text-3xl font-bold text-slate-800" x-text="getHighRiskCount()"></h3>
                </div>
                <div class="p-3 rounded-xl bg-red-500 bg-opacity-10 text-red-600">
                    <i data-lucide="alert-octagon" class="w-6 h-6"></i>
                </div>
            </div>
             <!-- Selesai -->
             <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:-translate-y-1 transition-transform">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Mitigasi Selesai</p>
                    <h3 class="text-3xl font-bold text-slate-800" x-text="riskData.filter(r => r.status === 'Completed').length"></h3>
                </div>
                <div class="p-3 rounded-xl bg-emerald-500 bg-opacity-10 text-emerald-600">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>
             <!-- Berjalan -->
             <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:-translate-y-1 transition-transform">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Mitigasi Berjalan</p>
                    <h3 class="text-3xl font-bold text-slate-800" x-text="riskData.filter(r => r.status === 'In-Progress').length"></h3>
                </div>
                <div class="p-3 rounded-xl bg-amber-500 bg-opacity-10 text-amber-600">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Bar Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-2 text-teal-600"></i>
                    Distribusi Kategori Risiko
                </h3>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <!-- Pie Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                    <i data-lucide="activity" class="w-5 h-5 mr-2 text-teal-600"></i>
                    Status Mitigasi
                </h3>
                <div class="h-64 flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
