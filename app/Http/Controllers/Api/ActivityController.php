<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activityIncome = Income::where('user_id', $request->user()->id)->get();

        foreach ($activityIncome as $income) {
            $income->type = 'income';
        }

        $activityOutcome = Outcome::where('user_id', $request->user()->id)->get();

        foreach ($activityOutcome as $outcome) {
            $outcome->type = 'outcome';
        }

        $activity = $activityIncome->merge($activityOutcome);

        return response()->json([
            'activity' => $activity->sortByDesc('created_at')->values()->all(),
        ], 200);
    }
}
