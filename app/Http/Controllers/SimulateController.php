<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Models\Admin\SCategory;
use App\Models\Admin\SQuestion;
use App\Models\Simulate\SAnswer;

use Exception;

class SimulateController extends Controller
{
    public function Institution(Request $request)
    {
        $institutionID = $request->id;

        return view('institution');
    }

    public function simulate()
    {
        $categories = SCategory::all();
        return view('simulates')->with(['categories' => $categories]);
    }

    public function questions($id)
    {
        $user_id = \Auth::user()->id;
        $category = SCategory::find($id);
        $questions = SQuestion::where('sc_id', '=', $id)
            ->with(['answers' => function($answer) {
                $answer->where('u_id', \Auth::user()->id);
            }])->get();

        return view('simulate.question')->with(['category' => $category, 'questions' => $questions]);
    }

    public function answers($sc_id, $id)
    {
        $question = SQuestion::find($id);

        // get next question id
        $next_qid = SQuestion::where('id', '>', $id)
            ->whereNotIn('id', SAnswer::where('u_id', '=', \Auth::user()->id)
                                        ->select('q_id')->get()
            )->select('id')->first();

        if(empty($next_qid)) {
            $next_qid = SQuestion::where('id', '<', $id)
                ->whereNotIn('id', SAnswer::where('u_id', '=', \Auth::user()->id)
                                            ->select('q_id')->get()
                )->select('id')->first();
        }

        $next_qid = empty($next_qid) ? 0 : $next_qid->id;

        return view('simulate.answer')->with(['question' => $question, 'next_qid' => $next_qid]);
    }

    public function answered(Request $request)
    {
        $result = [];
        try {
            $answered = SAnswer::create([
                'u_id' => \Auth::user()->id,
                'q_id' => $request['q_id'],
                'a_no' => $request['a_no'],
                'status' => $request['status'],
                'score' => $request['score']
            ]);

            $result['status'] = 'ok';
            $result['message'] = 'created';
            $result['data'] = $answered;
        } catch (Exception $e) {
            $result['status'] = 'exception';
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
