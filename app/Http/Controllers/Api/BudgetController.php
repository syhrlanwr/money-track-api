<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budget = Budget::where('user_id', $request->user()->id)->get();
        return response()->json([
            'budget' => $budget,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_budget' => 'required|numeric',
        ]);

        $budget = Budget::create([
            'total_budget' => $request->total_budget,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'budget' => $budget->total_budget,
        ], 201);
    }

    public function update(Request $request)
    {
        $request->validate([
            'total_budget' => 'required|numeric',
        ]);

        $budget = Budget::where('user_id', $request->user()->id)->first();

        $budget->update([
            'total_budget' => $request->total_budget,
        ]);

        return response()->json([
            'budget' => $budget->total_budget,
        ], 201);
    }
}
