<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoalController extends Controller
{
    /**
     * Display the goal settings page.
     */
    public function index()
    {
        $userId = auth()->id();
        $goal = Goal::where('user_id', $userId)->first();
        return view('goal.index', compact('goal'));
    }

    /**
     * Store or update user target goal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_name' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'target_score' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $userId = auth()->id();
        $goal = Goal::where('user_id', $userId)->first() ?? new Goal(['user_id' => $userId]);

        $goal->university_name = $validated['university_name'];
        $goal->study_program = $validated['study_program'];
        $goal->target_score = $validated['target_score'] ?? null;

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($goal->photo_path && Storage::disk('public')->exists($goal->photo_path)) {
                Storage::disk('public')->delete($goal->photo_path);
            }
            $path = $request->file('photo')->store('goals', 'public');
            $goal->photo_path = $path;
        }

        $goal->save();

        return redirect()->route('dashboard')->with('success', 'Tujuan impian berhasil disimpan! Semangat meraih PTN impianmu! 🎯');
    }
}
