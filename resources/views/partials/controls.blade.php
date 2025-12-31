<!-- 4. CONTROLS VIEW -->
<div x-show="activeTab === 'controls'" x-transition:enter="transition ease-out duration-300">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <div class="flex items-center mb-6 text-amber-600 bg-amber-50 p-4 rounded-xl border border-amber-100">
           <i data-lucide="alert-triangle" class="w-6 h-6 mr-3"></i>
           <div>
             <h3 class="font-bold">Modul Pengendalian</h3>
             <p class="text-sm text-amber-800">Modul ini akan aktif setelah Anda mengisi data risiko pada menu Daftar Risiko.</p>
           </div>
        </div>

        <div x-show="riskData.length > 0">
            <h3 class="font-bold text-lg mb-4">Rencana Tindak Lanjut (Gap Analysis)</h3>
            <div class="space-y-4">
                <template x-for="item in riskData.filter(r => r.status !== 'Completed')" :key="item.id">
                    <div class="border border-slate-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                          <div>
                              <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded mb-1" x-text="item.unit"></span>
                              <h4 class="font-bold text-slate-800" x-text="item.risiko"></h4>
                          </div>
                          <span class="text-xs font-semibold px-2 py-1 bg-slate-100 rounded text-slate-600" x-text="'Target: ' + item.triwulan"></span>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4 text-sm mt-3">
                          <div>
                              <p class="text-xs text-slate-400 uppercase font-semibold mb-1">Pengendalian Saat Ini</p>
                              <p class="text-slate-700 bg-green-50 p-2 rounded border border-green-100" x-text="item.pengendalian || '-'"></p>
                          </div>
                          <div>
                              <p class="text-xs text-slate-400 uppercase font-semibold mb-1">Status</p>
                              <p class="text-slate-700 bg-blue-50 p-2 rounded border border-blue-100" x-text="item.status + ' - PJ: ' + item.pj"></p>
                          </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
