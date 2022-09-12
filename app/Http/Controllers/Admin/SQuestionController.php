<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Admin\SCategory;
use App\Models\Admin\SQuestion;

use Exception;

class SQuestionController extends Controller
{
    public function clearTempDir()
    {
        $src_dir = "tmp/attached/admin/question/";
        if (is_dir($src_dir)) {
            $files = array_diff(scandir($src_dir), array('.', '..'));

            foreach ($files as $file)
                if (file_exists($src_dir . $file)) unlink($src_dir . $file);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->clearTempDir();

        $categories = SCategory::all();
        $questions = SQuestion::with('sCategory')->get();

        return view('admin/question')->with(['categories' => $categories, 'questions' => $questions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->clearTempDir();

        $categories = SCategory::all();

        return view('admin/question-create')->with(['categories' => $categories]);
    }

    public function uploadAttached(Request $request)
    {
        $target_dir = 'tmp/attached/admin/question/';
        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName());
        // $rand = rand();
        // $fileExt = $fileName['extension'];

        $ext_arr = array("png", "jpg", "gif", "pdf", "doc", "docx");
        // if(in_array($fileExt, $ext_arr)) {
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . $fileName['basename'];

        // Storage::disk('public')->put($target_file, $_FILES["file"]["tmp_name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        // $msg = "";
        // if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // $msg = "Successfully uploaded";
        // } else {
        // $msg = "Error while uploading";
        // }
        // echo $msg;
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $exist = SQuestion::where('sc_id', '=', $request['category'])
            ->where('title', '=', $request['title'])->first();

        $result = [];
        if (empty($exist)) {
            try {
                // get uploaded attach files
                $src_dir = "tmp/attached/admin/question/";
                $files = array_diff(scandir($src_dir), array('.', '..'));
                $attached_files = implode(":-:", $files);

                $question = SQuestion::create([
                    'sc_id' => $request['category'],
                    'title' => $request['title'],
                    'contents' => $request['contents'],
                    'score' => $request['score'],
                    'attached_files' => $attached_files,
                    'video_url' => $request['videoUrl'],
                    'sanswer1' => $request['sanswer1'],
                    'sanswer2' => $request['sanswer2'],
                    'sanswer3' => $request['sanswer3'],
                    'sanswer4' => $request['sanswer4'],
                    'sanswer5' => $request['sanswer5'],
                    'right_answer' => $request['right_answer']
                ]);

                $dest_dir = "attached/admin/question/" . $question->id . "/";
                if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);
                foreach ($files as $file) {
                    if (copy($src_dir . $file, $dest_dir . $file)) if (file_exists($src_dir . $file)) unlink($src_dir . $file);
                }

                $result['status'] = 'ok';
                $result['message'] = 'created';
            } catch (Exception $e) {
                $result['status'] = 'exception';
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['status'] = 'fail';
            $result['message'] = 'existed';
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $this->clearTempDir();

        $categories = SCategory::all();
        $question = SQuestion::find($id);

        return view('admin/question-show')->with(['categories' => $categories, 'question' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $this->clearTempDir();

        $categories = SCategory::all();
        $question = SQuestion::find($id);

        return view('admin/question-edit')->with(['categories' => $categories, 'question' => $question]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $exist = SQuestion::where('id', '<>', $id)
            ->where('sc_id', '=', $request['category'])
            ->where('title', '=', $request['title'])
            ->first();

        $result = [];
        if (empty($exist)) {
            try {
                $question = SQuestion::find($id);
                $src_dir = "tmp/attached/admin/question/";
                $dest_dir = "attached/admin/question/" . $id . "/";

                // check prev attached exists or not
                $prevAttached = explode(":-:", $request['prevAttached']);
                $curAttached = array_diff(scandir($dest_dir), array('.', '..'));

                foreach ($curAttached as $file) {
                    if (!in_array($file, $prevAttached)) { // delete prev file
                        unlink($dest_dir . $file);
                    }
                }

                // copy new uploaded
                $newFiles = array_diff(scandir($src_dir), array('.', '..'));
                foreach ($newFiles as $file) {
                    if (copy($src_dir . $file, $dest_dir . $file)) if (file_exists($src_dir . $file)) unlink($src_dir . $file);
                }

                $files = array_diff(scandir($dest_dir), array('.', '..'));
                $attached_files = implode(":-:", $files);

                // update query 
                if ($request['category'] != $question->sc_id || $request['score'] != $question->score || $request['title'] != $question->title || $request['contents'] != $question->contents || $attached_files != $question->attached_files) {
                    SQuestion::where('id', $id)->update([
                        'sc_id' => $request['category'],
                        'title' => $request['title'],
                        'contents' => $request['contents'],
                        'score' => $request['score'],
                        'attached_files' => $attached_files,
                        'video_url' => $request['videoUrl'],
                        'sanswer1' => $request['sanswer1'],
                        'sanswer2' => $request['sanswer2'],
                        'sanswer3' => $request['sanswer3'],
                        'sanswer4' => $request['sanswer4'],
                        'sanswer5' => $request['sanswer5'],
                        'right_answer' => $request['right_answer']
                    ]);
                }

                $result['status'] = 'ok';
                $result['message'] = 'updated';
            } catch (Exception $e) {
                $result['status'] = 'exception';
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['status'] = 'fail';
            $result['message'] = 'existed';
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $result = [];
        $question = SQuestion::find($id);
        if (strlen($question->sanswer_ids) == 0 && strlen($question->sreply_ids) == 0) {
            try {
                $question->delete();

                $result['status'] = 'ok';
                $result['message'] = 'deleted';
            } catch (Exception $e) {
                $result['status'] = 'exception';
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['status'] = 'fail';
            $result['message'] = 'There exists answered users.';
        }

        return response()->json($result);
    }
}
