<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $budget = Budget::where('user_id', $request->user()->id)->get();
        $income = Income::where('user_id', $request->user()->id)->where('budget_id', $budget[0]->id)->get();
        return response()->json([
            'income' => $income,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $budget = Budget::where('user_id', $request->user()->id)->get();

        $income = Income::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'budget_id' => $budget[0]->id,
            'user_id' => $request->user()->id,
        ]);

        $budget[0]->update([
            'total_budget' => $budget[0]->total_budget + $request->amount,
        ]);

        return response()->json([
            'income' => $income,
            'budget' => 'Rp. ' . number_format($budget[0]->total_budget, 0, ',', '.'),
        ], 201);
    }

    public function update(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $budget = Budget::where('user_id', $request->user()->id)->get();
        $income = Income::where('user_id', $request->user()->id)->where('budget_id', $budget[0]->id)->first();

        $budget[0]->update([
            'total_budget' => $budget[0]->total_budget - $income->amount,
        ]);

        $income->update([
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        $budget[0]->update([
            'total_budget' => $budget[0]->total_budget + $request->amount,
        ]);

        return response()->json([
            'income' => $income,
            'budget' => 'Rp. ' . number_format($budget[0]->total_budget, 0, ',', '.'),
        ], 201);
    }

    public function destroy(Request $request)
    {
        $budget = Budget::where('user_id', $request->user()->id)->get();
        $income = Income::where('user_id', $request->user()->id)->where('budget_id', $budget[0]->id)->first();

        $budget[0]->update([
            'total_budget' => $budget[0]->total_budget - $income->amount,
        ]);

        $income->delete();

        return response()->json([
            'budget' => 'Rp. ' . number_format($budget[0]->total_budget, 0, ',', '.'),
        ], 201);
    }
}
