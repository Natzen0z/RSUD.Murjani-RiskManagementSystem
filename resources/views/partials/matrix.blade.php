<!-- 3. MATRIX VIEW -->
<div x-show="activeTab === 'matrix'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
            <i data-lucide="target" class="w-6 h-6 mr-2 text-teal-600"></i>
            Peta Panas Risiko (Risk Heatmap)
        </h2>

        <div x-show="riskData.length === 0" class="text-center py-12 text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">
            Belum ada data untuk dipetakan.
        </div>

        <div x-show="riskData.length > 0">
            <div class="flex flex-col items-center overflow-x-auto">
                <div class="flex items-center">
                    <!-- Y Axis Label -->
                    <div class="-rotate-90 font-bold text-slate-500 tracking-wider text-sm w-8 text-center mr-4">PROBABILITAS</div>
                    
                    <!-- Y Labels -->
                    <div class="grid grid-rows-5 gap-1">
                        <div class="h-20 flex items-center justify-end pr-2 font-semibold text-slate-400 text-sm">Sgt Tinggi</div>
                        <div class="h-20 flex items-center justify-end pr-2 font-semibold text-slate-400 text-sm">Tinggi</div>
                        <div class="h-20 flex items-center justify-end pr-2 font-semibold text-slate-400 text-sm">Sedang</div>
                        <div class="h-20 flex items-center justify-end pr-2 font-semibold text-slate-400 text-sm">Rendah</div>
                        <div class="h-20 flex items-center justify-end pr-2 font-semibold text-slate-400 text-sm">Sgt Rendah</div>
                    </div>

                    <!-- The Matrix Grid -->
                    <div class="grid grid-cols-5 grid-rows-5 gap-2 ml-2">
                        <!-- Row 5 (Prob 5) -->
                        <div class="w-24 h-20 bg-yellow-400 rounded-lg flex items-center justify-center relative shadow-sm" title="P:5 D:1">
                            <span x-text="getMatrixCount(5,1)" x-show="getMatrixCount(5,1) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:5 D:2">
                            <span x-text="getMatrixCount(5,2)" x-show="getMatrixCount(5,2) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:5 D:3">
                            <span x-text="getMatrixCount(5,3)" x-show="getMatrixCount(5,3) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-lg flex items-center justify-center relative shadow-sm" title="P:5 D:4">
                            <span x-text="getMatrixCount(5,4)" x-show="getMatrixCount(5,4) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-lg flex items-center justify-center relative shadow-sm" title="P:5 D:5">
                            <span x-text="getMatrixCount(5,5)" x-show="getMatrixCount(5,5) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 4 (Prob 4) -->
                        <div class="w-24 h-20 bg-yellow-400 rounded-lg flex items-center justify-center relative shadow-sm" title="P:4 D:1">
                            <span x-text="getMatrixCount(4,1)" x-show="getMatrixCount(4,1) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:4 D:2">
                            <span x-text="getMatrixCount(4,2)" x-show="getMatrixCount(4,2) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:4 D:3">
                            <span x-text="getMatrixCount(4,3)" x-show="getMatrixCount(4,3) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-lg flex items-center justify-center relative shadow-sm" title="P:4 D:4">
                            <span x-text="getMatrixCount(4,4)" x-show="getMatrixCount(4,4) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-lg flex items-center justify-center relative shadow-sm" title="P:4 D:5">
                            <span x-text="getMatrixCount(4,5)" x-show="getMatrixCount(4,5) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 3 (Prob 3) -->
                        <div class="w-24 h-20 bg-green-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:3 D:1">
                            <span x-text="getMatrixCount(3,1)" x-show="getMatrixCount(3,1) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-lg flex items-center justify-center relative shadow-sm" title="P:3 D:2">
                            <span x-text="getMatrixCount(3,2)" x-show="getMatrixCount(3,2) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:3 D:3">
                            <span x-text="getMatrixCount(3,3)" x-show="getMatrixCount(3,3) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-lg flex items-center justify-center relative shadow-sm" title="P:3 D:4">
                            <span x-text="getMatrixCount(3,4)" x-show="getMatrixCount(3,4) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-lg flex items-center justify-center relative shadow-sm" title="P:3 D:5">
                            <span x-text="getMatrixCount(3,5)" x-show="getMatrixCount(3,5) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 2 (Prob 2) -->
                        <div class="w-24 h-20 bg-green-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:2 D:1">
                            <span x-text="getMatrixCount(2,1)" x-show="getMatrixCount(2,1) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-green-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:2 D:2">
                            <span x-text="getMatrixCount(2,2)" x-show="getMatrixCount(2,2) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-lg flex items-center justify-center relative shadow-sm" title="P:2 D:3">
                            <span x-text="getMatrixCount(2,3)" x-show="getMatrixCount(2,3) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:2 D:4">
                            <span x-text="getMatrixCount(2,4)" x-show="getMatrixCount(2,4) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:2 D:5">
                            <span x-text="getMatrixCount(2,5)" x-show="getMatrixCount(2,5) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 1 (Prob 1) -->
                        <div class="w-24 h-20 bg-green-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:1 D:1">
                            <span x-text="getMatrixCount(1,1)" x-show="getMatrixCount(1,1) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-green-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:1 D:2">
                            <span x-text="getMatrixCount(1,2)" x-show="getMatrixCount(1,2) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-lg flex items-center justify-center relative shadow-sm" title="P:1 D:3">
                            <span x-text="getMatrixCount(1,3)" x-show="getMatrixCount(1,3) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-lg flex items-center justify-center relative shadow-sm" title="P:1 D:4">
                            <span x-text="getMatrixCount(1,4)" x-show="getMatrixCount(1,4) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-lg flex items-center justify-center relative shadow-sm" title="P:1 D:5">
                            <span x-text="getMatrixCount(1,5)" x-show="getMatrixCount(1,5) > 0" class="text-2xl font-bold text-white drop-shadow-md"></span>
                        </div>
                    </div>
                </div>

                 <!-- X Axis -->
                 <div class="flex mt-2 ml-24">
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-sm">Sgt Ringan</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-sm">Ringan</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-sm">Sedang</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-sm">Berat</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-sm">Sgt Berat</div>
                </div>
                <div class="mt-2 font-bold text-slate-500 tracking-wider text-sm ml-24">DAMPAK</div>
            </div>
            
            <div class="mt-8 grid grid-cols-4 gap-4 max-w-2xl mx-auto">
                <div class="flex items-center"><div class="w-4 h-4 bg-green-500 rounded mr-2"></div> <span class="text-sm text-slate-600">Rendah (1-4)</span></div>
                <div class="flex items-center"><div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div> <span class="text-sm text-slate-600">Sedang (5-9)</span></div>
                <div class="flex items-center"><div class="w-4 h-4 bg-orange-500 rounded mr-2"></div> <span class="text-sm text-slate-600">Tinggi (10-14)</span></div>
                <div class="flex items-center"><div class="w-4 h-4 bg-red-600 rounded mr-2"></div> <span class="text-sm text-slate-600">Kritis (15-25)</span></div>
            </div>
        </div>
    </div>
</div>
