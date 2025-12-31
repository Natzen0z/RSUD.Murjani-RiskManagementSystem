@extends('layouts.app')

@section('title', 'Risk Management System - RSUD dr. Murjani')

@section('content')
<div x-data="riskApp()" x-init="init()">

    <!-- MOBILE HEADER -->
    <div class="md:hidden fixed top-0 w-full bg-slate-900 text-white z-50 px-4 py-3 flex justify-between items-center shadow-md">
        <div class="flex items-center space-x-2">
            <i data-lucide="activity" class="w-6 h-6 text-teal-400"></i>
            <span class="font-bold">Risk Management</span>
        </div>
        <button @click="isMobileMenuOpen = !isMobileMenuOpen">
            <i data-lucide="menu" class="w-6 h-6 text-white"></i>
        </button>
    </div>

    <!-- MOBILE MENU DROPDOWN -->
    <div x-show="isMobileMenuOpen" x-cloak class="md:hidden fixed top-14 left-0 w-full bg-slate-800 text-white z-40 p-4 space-y-2 shadow-xl transition-all">
        <button @click="setActiveTab('dashboard'); isMobileMenuOpen = false" class="block w-full text-left py-2 px-4 hover:bg-slate-700 rounded">Dashboard</button>
        <button @click="setActiveTab('register'); isMobileMenuOpen = false" class="block w-full text-left py-2 px-4 hover:bg-slate-700 rounded">Daftar Risiko</button>
        <button @click="setActiveTab('matrix'); isMobileMenuOpen = false" class="block w-full text-left py-2 px-4 hover:bg-slate-700 rounded">Matriks Risiko</button>
        <button @click="setActiveTab('controls'); isMobileMenuOpen = false" class="block w-full text-left py-2 px-4 hover:bg-slate-700 rounded">Pengendalian</button>
        <hr class="border-slate-600 my-2">
        <button @click="exportToExcel(); isMobileMenuOpen = false" class="block w-full text-left py-2 px-4 bg-emerald-600 hover:bg-emerald-700 rounded font-semibold">
            <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>Download Excel
        </button>
    </div>

    <!-- SIDEBAR (DESKTOP) -->
    <div class="w-64 bg-slate-900 text-white h-screen fixed left-0 top-0 p-4 flex flex-col shadow-xl z-50 hidden md:flex">
        <div class="mb-8 flex items-center space-x-2 px-2">
            <i data-lucide="activity" class="w-8 h-8 text-teal-400"></i>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Risk Management</h1>
                <p class="text-xs text-slate-400">RSUD dr. Murjani</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-2">
            <button @click="setActiveTab('dashboard')" :class="activeTab === 'dashboard' ? 'bg-teal-600 text-white shadow-lg shadow-teal-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="font-medium">Dashboard</span>
            </button>
            <button @click="setActiveTab('register')" :class="activeTab === 'register' ? 'bg-teal-600 text-white shadow-lg shadow-teal-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="font-medium">Daftar Risiko</span>
            </button>
            <button @click="setActiveTab('matrix')" :class="activeTab === 'matrix' ? 'bg-teal-600 text-white shadow-lg shadow-teal-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="target" class="w-5 h-5"></i>
                <span class="font-medium">Matriks Risiko</span>
            </button>
            <button @click="setActiveTab('controls')" :class="activeTab === 'controls' ? 'bg-teal-600 text-white shadow-lg shadow-teal-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
                <span class="font-medium">Pengendalian</span>
            </button>
        </nav>

        <div class="mt-auto space-y-4">
            <button @click="exportToExcel()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-4 rounded-xl flex items-center justify-center font-semibold shadow-lg shadow-emerald-900/20 transition-all">
                <i data-lucide="file-spreadsheet" class="w-5 h-5 mr-2"></i>
                <span>Download Excel</span>
            </button>
            <div class="px-4 py-4 bg-slate-800 rounded-xl border border-slate-700">
                <div class="flex items-center mb-2 text-teal-400">
                   <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                   <p class="text-xs font-semibold uppercase tracking-wider">Periode Laporan</p>
                </div>
                <p class="font-bold text-sm text-white" x-text="globalPeriod"></p>
            </div>
            <!-- User Info -->
            <div class="px-4 py-4 bg-slate-800 rounded-xl border border-slate-700">
                <div class="flex items-center mb-2 text-teal-400">
                   <i data-lucide="user-circle" class="w-4 h-4 mr-2"></i>
                   <p class="text-xs font-semibold uppercase tracking-wider">User</p>
                </div>
                <p class="font-bold text-sm text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
            </div>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="w-full bg-amber-600 hover:bg-amber-700 text-white py-3 px-4 rounded-xl flex items-center justify-center font-semibold shadow-lg transition-all">
                <i data-lucide="shield-check" class="w-5 h-5 mr-2"></i>
                <span>Admin Panel</span>
            </a>
            @endif
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
    <main class="flex-1 md:ml-64 p-4 md:p-8 mt-14 md:mt-0 transition-all duration-300 ease-in-out">
        
        <!-- HEADER -->
        <header class="mb-8 flex justify-between items-center">
            <div>
               <h1 class="text-2xl md:text-3xl font-bold text-slate-800" x-text="getHeaderTitle()"></h1>
               <p class="text-slate-500 mt-1">Sistem Manajemen Risiko RSUD dr. Murjani</p>
            </div>
        </header>

        @include('partials.dashboard')
        @include('partials.register')
        @include('partials.matrix')
        @include('partials.controls')

    </main>
</div>
@endsection

@push('scripts')
<script>
    function riskApp() {
        return {
            activeTab: 'dashboard',
            isMobileMenuOpen: false,
            riskData: @json($risks),
            searchTerm: '',
            filterUnit: '', 
            
            periodType: 'Triwulan',
            periodValue: 'II',
            periodYear: '2024',
            globalPeriod: 'Triwulan II - 2024',

            newRisk: {
                risiko: '', dampakDeskripsi: '', kategori: 'Strategis', unit: '', penyebab: '',
                awalD: 1, awalP: 1, pengendalian: '', evaluasi: 'Dibagi',
                residualD: 1, residualP: 1, pj: '', status: 'Not Started'
            },

            catChartInstance: null,
            statChartInstance: null,

            init() {
                this.$watch('activeTab', (value) => {
                    if (value === 'dashboard') setTimeout(() => this.updateCharts(), 100);
                    setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
                });
                this.$watch('riskData', () => {
                    if (this.activeTab === 'dashboard') this.updateCharts();
                    setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
                });
                this.updatePeriod();
                setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
                if (this.activeTab === 'dashboard') setTimeout(() => this.updateCharts(), 100);
            },

            setActiveTab(tab) { this.activeTab = tab; },
            
            getHeaderTitle() {
                if (this.activeTab === 'dashboard') return 'Dashboard Eksekutif';
                if (this.activeTab === 'register') return 'Daftar Risiko & Validasi Unit';
                if (this.activeTab === 'matrix') return 'Analisis Matriks Risiko';
                if (this.activeTab === 'controls') return 'Pengendalian & Evaluasi';
                return '';
            },

            updatePeriod() {
                this.globalPeriod = this.periodType === "Tahun" 
                    ? `Tahun ${this.periodYear}` 
                    : `${this.periodType} ${this.periodValue} - ${this.periodYear}`;
            },

            calculateLevel(score) {
                if (score >= 15) return 'Kritis';
                if (score >= 10) return 'Tinggi';
                if (score >= 5) return 'Sedang';
                return 'Rendah';
            },

            getRiskColor(level) {
                const colors = { 'Kritis': 'bg-red-600 text-white', 'Tinggi': 'bg-orange-500 text-white', 'Sedang': 'bg-yellow-400 text-black', 'Rendah': 'bg-green-500 text-white' };
                return colors[level] || 'bg-slate-200 text-slate-800';
            },

            getStatusColor(status) {
                const colors = { 'Completed': 'bg-emerald-100 text-emerald-800 border-emerald-200', 'In-Progress': 'bg-blue-100 text-blue-800 border-blue-200', 'Not Started': 'bg-gray-100 text-gray-800 border-gray-200' };
                return colors[status] || 'bg-gray-100 text-gray-800';
            },

            getValidationColor(status) {
                const colors = { 'Valid': 'bg-teal-100 text-teal-800 border-teal-200', 'Revisi': 'bg-red-100 text-red-800 border-red-200' };
                return colors[status] || 'bg-slate-100 text-slate-600 border-slate-200';
            },

            async addRisk() {
                if (!this.newRisk.risiko) { alert("Uraian Risiko harus diisi!"); return; }
                if (!this.newRisk.unit) { alert("Nama Unit harus diisi!"); return; }

                const formData = {
                    unit: this.newRisk.unit, kategori: this.newRisk.kategori, risiko: this.newRisk.risiko,
                    dampak_deskripsi: this.newRisk.dampakDeskripsi, penyebab: this.newRisk.penyebab,
                    awal_d: parseInt(this.newRisk.awalD) || 1, awal_p: parseInt(this.newRisk.awalP) || 1,
                    pengendalian: this.newRisk.pengendalian, evaluasi: this.newRisk.evaluasi,
                    residual_d: parseInt(this.newRisk.residualD) || 1, residual_p: parseInt(this.newRisk.residualP) || 1,
                    pj: this.newRisk.pj, status: this.newRisk.status,
                    triwulan: this.periodType === "Tahun" ? "Tahunan" : `${this.periodType} ${this.periodValue}`,
                    period_year: parseInt(this.periodYear)
                };

                try {
                    const response = await fetch('/risks', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(formData)
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.riskData.unshift(data.risk);
                        this.newRisk = { risiko: '', dampakDeskripsi: '', kategori: 'Strategis', unit: '', penyebab: '', awalD: 1, awalP: 1, pengendalian: '', evaluasi: 'Dibagi', residualD: 1, residualP: 1, pj: '', status: 'Not Started' };
                    }
                } catch (error) { console.error('Error:', error); }
            },

            async deleteRisk(id) {
                if (!confirm("Hapus data risiko ini?")) return;
                try {
                    const response = await fetch(`/risks/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const data = await response.json();
                    if (data.success) this.riskData = this.riskData.filter(item => item.id !== id);
                } catch (error) { console.error('Error:', error); }
            },

            async updateRisk(risk) {
                try {
                    await fetch(`/risks/${risk.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ validasi: risk.validasi, validator: risk.validator })
                    });
                } catch (error) { console.error('Error:', error); }
            },

            filteredData() {
                return this.riskData.filter(item => {
                    const matchesSearch = (item.risiko && item.risiko.toLowerCase().includes(this.searchTerm.toLowerCase())) ||
                                        (item.kategori && item.kategori.toLowerCase().includes(this.searchTerm.toLowerCase()));
                    const matchesUnit = this.filterUnit === '' || item.unit === this.filterUnit;
                    return matchesSearch && matchesUnit;
                });
            },

            getHighRiskCount() { return this.riskData.filter(d => d.awal_level === 'Tinggi' || d.awal_level === 'Kritis').length; },
            getMatrixCount(p, d) { return this.riskData.filter(item => item.awal_p === p && item.awal_d === d).length; },

            updateCharts() {
                if (typeof Chart === 'undefined') return;
                const canvasCat = document.getElementById('categoryChart');
                const canvasStat = document.getElementById('statusChart');
                if (!canvasCat || !canvasStat) return;

                const counts = {};
                this.riskData.forEach(d => { counts[d.kategori] = (counts[d.kategori] || 0) + 1; });
                const catLabels = Object.keys(counts);
                const catValues = Object.values(counts);

                const completed = this.riskData.filter(d => d.status === 'Completed').length;
                const active = this.riskData.filter(d => d.status === 'In-Progress').length;
                const notStarted = this.riskData.length - completed - active;

                if (this.catChartInstance) this.catChartInstance.destroy();
                this.catChartInstance = new Chart(canvasCat.getContext('2d'), {
                    type: 'bar',
                    data: { labels: catLabels.length > 0 ? catLabels : ['Belum ada data'], datasets: [{ label: 'Jumlah Risiko', data: catValues.length > 0 ? catValues : [0], backgroundColor: '#0d9488', borderRadius: 4 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } }
                });

                if (this.statChartInstance) this.statChartInstance.destroy();
                this.statChartInstance = new Chart(canvasStat.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: ['Selesai', 'Berjalan', 'Belum Mulai'], datasets: [{ data: this.riskData.length > 0 ? [completed, active, notStarted] : [0, 0, 1], backgroundColor: this.riskData.length > 0 ? ['#10b981', '#3b82f6', '#94a3b8'] : ['#e2e8f0', '#e2e8f0', '#e2e8f0'] }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                });
            },

            async exportToExcel() {
                if (this.riskData.length === 0) { alert("Belum ada data untuk diekspor!"); return; }
                
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Risk Management System - RSUD dr. Murjani';
                workbook.created = new Date();

                // ============ SHEET 1: DASHBOARD ============
                const dashSheet = workbook.addWorksheet('Dashboard');
                
                // Title
                dashSheet.mergeCells('A1:E1');
                dashSheet.getCell('A1').value = 'DASHBOARD MANAJEMEN RISIKO';
                dashSheet.getCell('A1').font = { size: 16, bold: true, color: { argb: 'FF0D9488' } };
                dashSheet.getCell('A2').value = `Periode: ${this.globalPeriod}`;
                dashSheet.getCell('A2').font = { italic: true, color: { argb: 'FF64748B' } };
                
                // Summary Stats
                dashSheet.getCell('A4').value = 'RINGKASAN STATISTIK';
                dashSheet.getCell('A4').font = { bold: true, size: 12 };
                
                const completed = this.riskData.filter(r => r.status === 'Completed').length;
                const inProgress = this.riskData.filter(r => r.status === 'In-Progress').length;
                const highRisk = this.getHighRiskCount();
                
                const stats = [
                    ['Total Risiko', this.riskData.length],
                    ['Risiko Tinggi/Kritis', highRisk],
                    ['Mitigasi Selesai', completed],
                    ['Mitigasi Berjalan', inProgress],
                    ['Belum Dimulai', this.riskData.length - completed - inProgress]
                ];
                
                stats.forEach((stat, index) => {
                    dashSheet.getCell(`A${6 + index}`).value = stat[0];
                    dashSheet.getCell(`B${6 + index}`).value = stat[1];
                    dashSheet.getCell(`B${6 + index}`).font = { bold: true };
                });
                
                // Category Distribution
                dashSheet.getCell('A13').value = 'DISTRIBUSI KATEGORI';
                dashSheet.getCell('A13').font = { bold: true, size: 12 };
                
                const categoryCounts = {};
                this.riskData.forEach(d => { categoryCounts[d.kategori] = (categoryCounts[d.kategori] || 0) + 1; });
                
                let catRow = 14;
                Object.entries(categoryCounts).forEach(([cat, count]) => {
                    dashSheet.getCell(`A${catRow}`).value = cat;
                    dashSheet.getCell(`B${catRow}`).value = count;
                    catRow++;
                });
                
                dashSheet.getColumn(1).width = 25;
                dashSheet.getColumn(2).width = 15;

                // ============ SHEET 2: RISK REGISTER ============
                const regSheet = workbook.addWorksheet('Risk Register');
                
                // Title
                regSheet.mergeCells('A1:S1');
                regSheet.getCell('A1').value = 'DAFTAR RISIKO TERINTEGRASI';
                regSheet.getCell('A1').font = { size: 14, bold: true };
                regSheet.getCell('A2').value = `Periode: ${this.globalPeriod} | Diekspor: ${new Date().toLocaleDateString('id-ID')}`;
                
                // Headers
                const headers = ['Kode', 'Unit', 'Kategori', 'Uraian Risiko', 'Penyebab', 'Deskripsi Dampak', 
                    'Dampak (D)', 'Probabilitas (P)', 'Skor Awal', 'Level Awal',
                    'Rencana Pengendalian', 'Evaluasi',
                    'Dampak Sisa', 'Prob Sisa', 'Skor Sisa', 'Level Sisa',
                    'Penanggung Jawab', 'Status', 'Validasi', 'Validator'];
                
                regSheet.getRow(4).values = headers;
                regSheet.getRow(4).font = { bold: true, color: { argb: 'FFFFFFFF' } };
                regSheet.getRow(4).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0D9488' } };
                regSheet.getRow(4).alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
                regSheet.getRow(4).height = 30;
                
                // Data rows
                this.riskData.forEach((item, index) => {
                    const row = regSheet.addRow([
                        item.kode, item.unit, item.kategori, item.risiko, item.penyebab, item.dampak_deskripsi,
                        item.awal_d, item.awal_p, item.awal_skor, item.awal_level,
                        item.pengendalian, item.evaluasi,
                        item.residual_d, item.residual_p, item.residual_skor, item.residual_level,
                        item.pj, item.status, item.validasi, item.validator || ''
                    ]);
                    
                    // Color code risk levels
                    const levelColors = { 'Kritis': 'FFDC2626', 'Tinggi': 'FFF97316', 'Sedang': 'FFFACC15', 'Rendah': 'FF22C55E' };
                    
                    // Color initial level cell
                    row.getCell(10).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: levelColors[item.awal_level] || 'FFE2E8F0' } };
                    row.getCell(10).font = { bold: true, color: { argb: item.awal_level === 'Sedang' ? 'FF000000' : 'FFFFFFFF' } };
                    
                    // Color residual level cell
                    row.getCell(16).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: levelColors[item.residual_level] || 'FFE2E8F0' } };
                    row.getCell(16).font = { bold: true, color: { argb: item.residual_level === 'Sedang' ? 'FF000000' : 'FFFFFFFF' } };
                    
                    row.alignment = { vertical: 'top', wrapText: true };
                });
                
                // Column widths
                regSheet.getColumn(1).width = 10;  // Kode
                regSheet.getColumn(2).width = 15;  // Unit
                regSheet.getColumn(3).width = 15;  // Kategori
                regSheet.getColumn(4).width = 35;  // Risiko
                regSheet.getColumn(5).width = 25;  // Penyebab
                regSheet.getColumn(6).width = 25;  // Dampak Deskripsi
                regSheet.getColumn(7).width = 8;   // D
                regSheet.getColumn(8).width = 8;   // P
                regSheet.getColumn(9).width = 8;   // Skor
                regSheet.getColumn(10).width = 10; // Level
                regSheet.getColumn(11).width = 35; // Pengendalian
                regSheet.getColumn(12).width = 12; // Evaluasi
                regSheet.getColumn(13).width = 8;  // D Sisa
                regSheet.getColumn(14).width = 8;  // P Sisa
                regSheet.getColumn(15).width = 8;  // Skor Sisa
                regSheet.getColumn(16).width = 10; // Level Sisa
                regSheet.getColumn(17).width = 20; // PJ
                regSheet.getColumn(18).width = 12; // Status
                regSheet.getColumn(19).width = 12; // Validasi
                regSheet.getColumn(20).width = 15; // Validator

                // ============ SIGNATURE SECTION ============
                const lastDataRow = regSheet.lastRow.number;
                const signatureStartRow = lastDataRow + 4;
                
                // Add spacing rows
                regSheet.addRow([]);
                regSheet.addRow([]);
                regSheet.addRow([]);
                
                // Signature header row - right to left: Risk Owner, Reviewer, Validator, Approval
                const signatureRow = signatureStartRow;
                
                // Column positions for 4 signatures (using columns: A-E, F-J, K-O, P-T)
                // Order from left to right: Approval, Validator, Reviewer, Risk Owner
                
                // Approval (Director) - Columns A-E
                regSheet.mergeCells(signatureRow, 1, signatureRow, 5);
                regSheet.getCell(signatureRow, 1).value = 'Approval';
                regSheet.getCell(signatureRow, 1).font = { bold: true, size: 11 };
                regSheet.getCell(signatureRow, 1).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 1, 1, signatureRow + 1, 5);
                regSheet.getCell(signatureRow + 1, 1).value = '(Direktur)';
                regSheet.getCell(signatureRow + 1, 1).font = { italic: true, size: 10, color: { argb: 'FF64748B' } };
                regSheet.getCell(signatureRow + 1, 1).alignment = { horizontal: 'center' };
                
                // Signature line
                regSheet.mergeCells(signatureRow + 5, 1, signatureRow + 5, 5);
                regSheet.getCell(signatureRow + 5, 1).value = '________________________';
                regSheet.getCell(signatureRow + 5, 1).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 6, 1, signatureRow + 6, 5);
                regSheet.getCell(signatureRow + 6, 1).value = '(                                        )';
                regSheet.getCell(signatureRow + 6, 1).alignment = { horizontal: 'center' };
                
                // Validator (Head of Quality Committee) - Columns F-J
                regSheet.mergeCells(signatureRow, 6, signatureRow, 10);
                regSheet.getCell(signatureRow, 6).value = 'Validator';
                regSheet.getCell(signatureRow, 6).font = { bold: true, size: 11 };
                regSheet.getCell(signatureRow, 6).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 1, 6, signatureRow + 1, 10);
                regSheet.getCell(signatureRow + 1, 6).value = '(Ketua Komite Mutu)';
                regSheet.getCell(signatureRow + 1, 6).font = { italic: true, size: 10, color: { argb: 'FF64748B' } };
                regSheet.getCell(signatureRow + 1, 6).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 5, 6, signatureRow + 5, 10);
                regSheet.getCell(signatureRow + 5, 6).value = '________________________';
                regSheet.getCell(signatureRow + 5, 6).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 6, 6, signatureRow + 6, 10);
                regSheet.getCell(signatureRow + 6, 6).value = '(                                        )';
                regSheet.getCell(signatureRow + 6, 6).alignment = { horizontal: 'center' };
                
                // Reviewer (Risk Management Subcommittee) - Columns K-O
                regSheet.mergeCells(signatureRow, 11, signatureRow, 15);
                regSheet.getCell(signatureRow, 11).value = 'Reviewer';
                regSheet.getCell(signatureRow, 11).font = { bold: true, size: 11 };
                regSheet.getCell(signatureRow, 11).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 1, 11, signatureRow + 1, 15);
                regSheet.getCell(signatureRow + 1, 11).value = '(Sub Komite Manajemen Risiko)';
                regSheet.getCell(signatureRow + 1, 11).font = { italic: true, size: 10, color: { argb: 'FF64748B' } };
                regSheet.getCell(signatureRow + 1, 11).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 5, 11, signatureRow + 5, 15);
                regSheet.getCell(signatureRow + 5, 11).value = '________________________';
                regSheet.getCell(signatureRow + 5, 11).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 6, 11, signatureRow + 6, 15);
                regSheet.getCell(signatureRow + 6, 11).value = '(                                        )';
                regSheet.getCell(signatureRow + 6, 11).alignment = { horizontal: 'center' };
                
                // Risk Owner - Columns P-T
                regSheet.mergeCells(signatureRow, 16, signatureRow, 20);
                regSheet.getCell(signatureRow, 16).value = 'Risk Owner';
                regSheet.getCell(signatureRow, 16).font = { bold: true, size: 11 };
                regSheet.getCell(signatureRow, 16).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 1, 16, signatureRow + 1, 20);
                regSheet.getCell(signatureRow + 1, 16).value = '(Pemilik Risiko)';
                regSheet.getCell(signatureRow + 1, 16).font = { italic: true, size: 10, color: { argb: 'FF64748B' } };
                regSheet.getCell(signatureRow + 1, 16).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 5, 16, signatureRow + 5, 20);
                regSheet.getCell(signatureRow + 5, 16).value = '________________________';
                regSheet.getCell(signatureRow + 5, 16).alignment = { horizontal: 'center' };
                
                regSheet.mergeCells(signatureRow + 6, 16, signatureRow + 6, 20);
                regSheet.getCell(signatureRow + 6, 16).value = '(                                        )';
                regSheet.getCell(signatureRow + 6, 16).alignment = { horizontal: 'center' };


                // ============ SHEET 3: MATRIX ============
                const matSheet = workbook.addWorksheet('Matriks Risiko');
                
                matSheet.mergeCells('A1:G1');
                matSheet.getCell('A1').value = 'MATRIKS ANALISIS RISIKO (HEATMAP)';
                matSheet.getCell('A1').font = { size: 14, bold: true };
                
                // Matrix labels
                matSheet.getCell('B3').value = 'DAMPAK →';
                matSheet.getCell('B3').font = { bold: true };
                for(let i = 1; i <= 5; i++) {
                    matSheet.getCell(3, i + 2).value = i;
                    matSheet.getCell(3, i + 2).font = { bold: true };
                    matSheet.getCell(3, i + 2).alignment = { horizontal: 'center' };
                }
                
                matSheet.getCell('A4').value = 'PROBABILITAS';
                matSheet.getCell('A4').font = { bold: true };
                matSheet.getCell('A4').alignment = { textRotation: 90, vertical: 'middle' };
                matSheet.mergeCells('A4:A8');
                
                // Matrix cells
                const levelColors = { 'Rendah': 'FF22C55E', 'Sedang': 'FFFACC15', 'Tinggi': 'FFF97316', 'Kritis': 'FFDC2626' };
                
                for(let p = 5; p >= 1; p--) {
                    const rowNum = 9 - p;
                    matSheet.getCell(rowNum, 2).value = p;
                    matSheet.getCell(rowNum, 2).font = { bold: true };
                    matSheet.getCell(rowNum, 2).alignment = { horizontal: 'center' };
                    
                    for(let d = 1; d <= 5; d++) {
                        const count = this.getMatrixCount(p, d);
                        const score = p * d;
                        const level = this.calculateLevel(score);
                        const cell = matSheet.getCell(rowNum, d + 2);
                        
                        cell.value = count > 0 ? count : '';
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: levelColors[level] } };
                        cell.font = { bold: true, color: { argb: level === 'Sedang' ? 'FF000000' : 'FFFFFFFF' }, size: 14 };
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.border = { top: {style:'thin'}, left: {style:'thin'}, bottom: {style:'thin'}, right: {style:'thin'} };
                    }
                }
                
                // Legend
                matSheet.getCell('A11').value = 'KETERANGAN:';
                matSheet.getCell('A11').font = { bold: true };
                const legend = [['Rendah', '1-4', 'FF22C55E'], ['Sedang', '5-9', 'FFFACC15'], ['Tinggi', '10-14', 'FFF97316'], ['Kritis', '15-25', 'FFDC2626']];
                legend.forEach((item, idx) => {
                    matSheet.getCell(12 + idx, 1).value = item[0];
                    matSheet.getCell(12 + idx, 2).value = `(Skor ${item[1]})`;
                    matSheet.getCell(12 + idx, 1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: item[2] } };
                    matSheet.getCell(12 + idx, 1).font = { color: { argb: item[0] === 'Sedang' ? 'FF000000' : 'FFFFFFFF' } };
                });
                
                for(let i = 1; i <= 7; i++) matSheet.getColumn(i).width = 12;

                // ============ SHEET 4: CONTROLS (GAP ANALYSIS) ============
                const ctrlSheet = workbook.addWorksheet('Pengendalian');
                
                ctrlSheet.mergeCells('A1:F1');
                ctrlSheet.getCell('A1').value = 'GAP ANALYSIS & RENCANA TINDAK LANJUT';
                ctrlSheet.getCell('A1').font = { size: 14, bold: true };
                ctrlSheet.getCell('A2').value = 'Risiko yang belum selesai dimitigasi';
                ctrlSheet.getCell('A2').font = { italic: true, color: { argb: 'FF64748B' } };
                
                ctrlSheet.getRow(4).values = ['Kode', 'Unit', 'Uraian Risiko', 'Pengendalian Saat Ini', 'Target Periode', 'Status', 'PJ'];
                ctrlSheet.getRow(4).font = { bold: true, color: { argb: 'FFFFFFFF' } };
                ctrlSheet.getRow(4).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF3B82F6' } };
                
                const activeRisks = this.riskData.filter(r => r.status !== 'Completed');
                activeRisks.forEach(item => {
                    ctrlSheet.addRow([item.kode, item.unit, item.risiko, item.pengendalian || '-', item.triwulan || '-', item.status, item.pj || '-']);
                });
                
                ctrlSheet.getColumn(1).width = 10;
                ctrlSheet.getColumn(2).width = 15;
                ctrlSheet.getColumn(3).width = 40;
                ctrlSheet.getColumn(4).width = 40;
                ctrlSheet.getColumn(5).width = 15;
                ctrlSheet.getColumn(6).width = 15;
                ctrlSheet.getColumn(7).width = 20;

                // Generate and download file
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                saveAs(blob, `Risk_Register_RSUD_${this.periodYear}_${new Date().toISOString().slice(0,10)}.xlsx`);
            }
        }
    }
</script>
@endpush
