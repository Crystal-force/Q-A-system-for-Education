<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subjects;

class SubjectController extends Controller
{
    public function Index(Request $request)
    {
        $select_id = $request->id;
        $subject = DB::table('subjects')->get();
        if (isset($subject)) {
            return view('question-subject')->with([
                'subject' => $subject
            ]);
        }
    }

    public function SolutionSubject(Request $request)
    {
        $select_id = $request->id;
        $subject = DB::table('subjects')->get();
        if (isset($subject)) {
            return view('solution-subject')->with([
                'subject' => $subject
            ]);
        }
    }

    public function Simulate()
    {
        $categories = SCategory::all();
        return view('simulates')->with([
            'categories' => $categories
        ]);
    }
}
