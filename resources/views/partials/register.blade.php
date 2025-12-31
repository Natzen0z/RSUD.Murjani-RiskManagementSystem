<!-- 2. REGISTER VIEW -->
<div x-show="activeTab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    
    <!-- Period Settings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Pengaturan Periode Laporan</h3>
                <p class="text-sm text-slate-500">Tentukan periode laporan dokumen ini.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <select x-model="periodType" @change="updatePeriod()" class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium">
                <option value="Triwulan">Triwulan</option>
                <option value="Semester">Semester</option>
                <option value="Tahun">Tahun</option>
            </select>
            <select x-show="periodType !== 'Tahun'" x-model="periodValue" @change="updatePeriod()" class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium">
                <template x-if="periodType === 'Triwulan'">
                    <optgroup>
                        <option value="I">I (Jan-Mar)</option>
                        <option value="II">II (Apr-Jun)</option>
                        <option value="III">III (Jul-Sep)</option>
                        <option value="IV">IV (Okt-Des)</option>
                    </optgroup>
                </template>
                <template x-if="periodType === 'Semester'">
                    <optgroup>
                        <option value="I">I (Jan-Jun)</option>
                        <option value="II">II (Jul-Des)</option>
                    </optgroup>
                </template>
            </select>
            <select x-model="periodYear" @change="updatePeriod()" class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium">
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <i data-lucide="plus" class="w-5 h-5 mr-2 text-teal-600"></i>
            Input Risiko Baru
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 text-sm">
            <!-- Column 1 -->
            <div class="md:col-span-3 space-y-3">
                <div>
                    <label class="block text-slate-500 mb-1">Unit/Bagian</label>
                    <input type="text" x-model="newRisk.unit" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="Ketik nama unit...">
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Risiko</label>
                    <textarea x-model="newRisk.risiko" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-teal-500" rows="2" placeholder="Contoh: Pasien Kabur"></textarea>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Deskripsi Dampak</label>
                    <textarea x-model="newRisk.dampakDeskripsi" class="w-full p-2 border rounded-lg" rows="2" placeholder="Uraian dampak..."></textarea>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Kategori</label>
                    <select x-model="newRisk.kategori" class="w-full p-2 border rounded-lg">
                        <option>Strategis</option>
                        <option>Operasional</option>
                        <option>Fraud</option>
                        <option>Hukum</option>
                        <option>Keuangan</option>
                        <option>SDM</option>
                        <option>Teknologi Informasi</option>
                        <option>Klinis</option>
                        <option>HAZARD</option>
                    </select>
                </div>
            </div>
            <!-- Column 2 -->
            <div class="md:col-span-3 space-y-3">
                <div>
                    <label class="block text-slate-500 mb-1">Penyebab</label>
                    <textarea x-model="newRisk.penyebab" class="w-full p-2 border rounded-lg" rows="2" placeholder="Penyebab utama..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2 rounded-lg border border-slate-200">
                    <div class="text-center font-semibold text-xs col-span-2 mb-1 text-slate-600">SKOR AWAL</div>
                    <div>
                        <label class="text-xs text-slate-400 block">Dampak (1-5)</label>
                        <input type="number" min="1" max="5" x-model="newRisk.awalD" class="w-full p-1 border rounded text-center">
                    </div>
                    <div>
                        <label class="text-xs text-slate-400 block">Prob (1-5)</label>
                        <input type="number" min="1" max="5" x-model="newRisk.awalP" class="w-full p-1 border rounded text-center">
                    </div>
                    <div class="col-span-2 text-center text-xs font-bold text-teal-600 mt-1">
                        Skor: <span x-text="(newRisk.awalD || 1) * (newRisk.awalP || 1)"></span> (<span x-text="calculateLevel((newRisk.awalD || 1) * (newRisk.awalP || 1))"></span>)
                    </div>
                </div>
            </div>
            <!-- Column 3 -->
            <div class="md:col-span-3 space-y-3">
                <div>
                    <label class="block text-slate-500 mb-1">Rencana Pengendalian</label>
                    <textarea x-model="newRisk.pengendalian" class="w-full p-2 border rounded-lg" rows="2" placeholder="Tindakan mitigasi..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2 rounded-lg border border-slate-200">
                    <div class="text-center font-semibold text-xs col-span-2 mb-1 text-slate-600">SKOR RESIDUAL</div>
                    <div>
                        <label class="text-xs text-slate-400 block">Dampak</label>
                        <input type="number" min="1" max="5" x-model="newRisk.residualD" class="w-full p-1 border rounded text-center">
                    </div>
                    <div>
                        <label class="text-xs text-slate-400 block">Prob</label>
                        <input type="number" min="1" max="5" x-model="newRisk.residualP" class="w-full p-1 border rounded text-center">
                    </div>
                    <div class="col-span-2 text-center text-xs font-bold text-teal-600 mt-1">
                        Skor: <span x-text="(newRisk.residualD || 1) * (newRisk.residualP || 1)"></span> (<span x-text="calculateLevel((newRisk.residualD || 1) * (newRisk.residualP || 1))"></span>)
                    </div>
                </div>
            </div>
            <!-- Column 4 -->
            <div class="md:col-span-3 space-y-3 flex flex-col justify-between">
                 <div>
                    <label class="block text-slate-500 mb-1">PJ (Penanggung Jawab)</label>
                    <input type="text" x-model="newRisk.pj" class="w-full p-2 border rounded-lg" placeholder="Nama/Jabatan">
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Status</label>
                    <select x-model="newRisk.status" class="w-full p-2 border rounded-lg">
                        <option value="Not Started">Belum Mulai</option>
                        <option value="In-Progress">Berjalan</option>
                        <option value="Completed">Selesai</option>
                    </select>
                </div>
                <button @click="addRisk()" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg flex items-center justify-center font-semibold transition-colors mt-2">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Simpan Risiko
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Daftar Risiko & Validasi</h2>
                <div class="flex items-center mt-2">
                    <span class="text-sm text-slate-500 mr-2">Filter Unit:</span>
                    <input type="text" x-model="filterUnit" class="p-1 text-sm bg-slate-50 border border-slate-200 rounded text-slate-700 w-40 focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Ketik unit...">
                </div>
            </div>
            <div class="relative w-full md:w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input x-model="searchTerm" type="text" placeholder="Cari risiko..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>
        </div>

        <div x-show="riskData.length === 0" class="p-12 text-center text-slate-400">
            Belum ada data yang tersimpan. Gunakan formulir di atas untuk menambahkan risiko.
        </div>

        <div x-show="riskData.length > 0" class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-4 w-20">Kode</th>
                        <th class="px-4 py-4 w-32">Unit & Kategori</th>
                        <th class="px-4 py-4 w-64">Risiko, Penyebab & Dampak</th>
                        <th class="px-4 py-4 w-24 text-center">Skor Awal</th>
                        <th class="px-4 py-4 w-48">Pengendalian</th>
                        <th class="px-4 py-4 w-24 text-center">Skor Sisa</th>
                        <th class="px-4 py-4 w-32">PJ & Status</th>
                        <th class="px-4 py-4 w-40 text-center bg-indigo-50 text-indigo-700 border-l border-indigo-100">Validasi Unit</th>
                        <th class="px-4 py-4 w-32 text-center bg-indigo-50 text-indigo-700 border-l border-indigo-100">Validator</th>
                        <th class="px-4 py-4 w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="item in filteredData()" :key="item.id">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 font-medium text-slate-900 align-top" x-text="item.kode"></td>
                            <td class="px-4 py-4 align-top">
                                <div class="font-bold text-slate-700" x-text="item.unit"></div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600 mt-1" x-text="item.kategori"></span>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <div class="font-bold text-slate-800 mb-1" x-text="item.risiko"></div>
                                <div class="text-xs text-slate-500 mb-1"><span class="font-semibold">Penyebab:</span> <span x-text="item.penyebab"></span></div>
                                <div class="text-xs text-slate-500" x-show="item.dampak_deskripsi"><span class="font-semibold">Dampak:</span> <span x-text="item.dampak_deskripsi"></span></div>
                            </td>
                            <td class="px-4 py-4 text-center align-top">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-1 rounded text-xs font-bold" :class="getRiskColor(item.awal_level)" x-text="item.awal_skor"></span>
                                    <div class="text-xs text-slate-400" x-text="item.awal_level"></div>
                                </div>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <div class="text-slate-700 mb-2 text-xs" x-text="item.pengendalian"></div>
                            </td>
                            <td class="px-4 py-4 text-center align-top">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-1 rounded text-xs font-bold" :class="getRiskColor(item.residual_level)" x-text="item.residual_skor"></span>
                                    <div class="text-xs text-slate-400" x-text="item.residual_level"></div>
                                </div>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <div class="text-slate-900 font-medium mb-1 text-xs" x-text="item.pj"></div>
                                <select x-model="item.status" @change="updateRiskStatus(item)"
                                    class="text-[10px] p-1 rounded-full border font-medium cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-300"
                                    :class="getStatusColor(item.status)">
                                    <option value="Not Started">Belum Mulai</option>
                                    <option value="In-Progress">Berjalan</option>
                                    <option value="Completed">Selesai</option>
                                </select>
                            </td>
                            <!-- Kolom Validasi -->
                            <td class="px-4 py-4 text-center align-top border-l border-slate-100 bg-slate-50/50">
                                <select x-model="item.validasi" @change="updateRisk(item)"
                                    class="w-full text-xs p-1 rounded border cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    :class="getValidationColor(item.validasi)">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Valid">Valid</option>
                                    <option value="Revisi">Revisi</option>
                                </select>
                                <div x-show="item.validasi === 'Valid'" class="mt-1 text-[10px] text-teal-600 flex items-center justify-center gap-1">
                                    <i data-lucide="check-check" class="w-3 h-3"></i> Terverifikasi
                                </div>
                            </td>
                            <!-- Kolom Validator -->
                            <td class="px-4 py-4 text-center align-top border-l border-slate-100 bg-slate-50/50">
                                <input type="text" x-model="item.validator" @change="updateRisk(item)" class="w-full text-xs p-1 rounded border focus:outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Nama Validator">
                            </td>
                            <td class="px-4 py-4 text-center align-top">
                                <button @click="deleteRisk(item.id)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
