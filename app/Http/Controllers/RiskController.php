<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RiskController extends Controller
{
    /**
     * Display the main risk management view
     */
    public function index(): View
    {
        $risks = Risk::orderBy('created_at', 'desc')->get();
        
        return view('risk', [
            'risks' => $risks,
        ]);
    }

    /**
     * Store a new risk
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unit' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'risiko' => 'required|string',
            'dampak_deskripsi' => 'nullable|string',
            'penyebab' => 'nullable|string',
            'awal_d' => 'required|integer|min:1|max:5',
            'awal_p' => 'required|integer|min:1|max:5',
            'pengendalian' => 'nullable|string',
            'evaluasi' => 'nullable|string|max:255',
            'residual_d' => 'required|integer|min:1|max:5',
            'residual_p' => 'required|integer|min:1|max:5',
            'pj' => 'nullable|string|max:255',
            'status' => 'required|in:Not Started,In-Progress,Completed',
            'triwulan' => 'nullable|string|max:255',
            'period_year' => 'nullable|integer',
        ]);

        $validated['kode'] = Risk::generateNextKode();
        $validated['validasi'] = 'Menunggu';
        
        $risk = Risk::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Risiko berhasil ditambahkan',
            'risk' => $risk,
        ]);
    }

    /**
     * Update an existing risk (for validation, status, etc.)
     */
    public function update(Request $request, Risk $risk): JsonResponse
    {
        $validated = $request->validate([
            'unit' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string|max:255',
            'risiko' => 'sometimes|required|string',
            'dampak_deskripsi' => 'nullable|string',
            'penyebab' => 'nullable|string',
            'awal_d' => 'sometimes|required|integer|min:1|max:5',
            'awal_p' => 'sometimes|required|integer|min:1|max:5',
            'pengendalian' => 'nullable|string',
            'evaluasi' => 'nullable|string|max:255',
            'residual_d' => 'sometimes|required|integer|min:1|max:5',
            'residual_p' => 'sometimes|required|integer|min:1|max:5',
            'pj' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:Not Started,In-Progress,Completed',
            'validasi' => 'sometimes|required|in:Menunggu,Valid,Revisi',
            'validator' => 'nullable|string|max:255',
            'triwulan' => 'nullable|string|max:255',
            'period_year' => 'nullable|integer',
        ]);

        $risk->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Risiko berhasil diperbarui',
            'risk' => $risk->fresh(),
        ]);
    }

    /**
     * Delete a risk
     */
    public function destroy(Risk $risk): JsonResponse
    {
        $risk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Risiko berhasil dihapus',
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function statistics(): JsonResponse
    {
        $risks = Risk::all();
        
        $total = $risks->count();
        $highRisk = $risks->filter(fn($r) => in_array($r->awal_level, ['Tinggi', 'Kritis']))->count();
        $completed = $risks->where('status', 'Completed')->count();
        $inProgress = $risks->where('status', 'In-Progress')->count();
        
        // Category counts
        $categoryStats = $risks->groupBy('kategori')->map->count();
        
        return response()->json([
            'total' => $total,
            'highRisk' => $highRisk,
            'completed' => $completed,
            'inProgress' => $inProgress,
            'categoryStats' => $categoryStats,
        ]);
    }
}
