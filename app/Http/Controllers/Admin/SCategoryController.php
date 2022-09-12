<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\SCategory;
use App\Models\Admin\SQuestion;

use Exception;

class SCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin/category')->with(['categories' => SCategory::select('id', 'name', 'color')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/category-create');
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
        $exist = SCategory::where('name', $request['name'])->first();
        if(empty($exist)){
            SCategory::create([
                'name' => $request['name'],
                'color' => $request['color']
            ]);
    
            return response()->json(['status' => 'ok', 'result' => 'created']);
        }else{
            return response()->json(['status' => 'fail', 'result' => 'existed']);
        }
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
        $category = SCategory::find($id);
        return view('admin/category-edit')->with(['category' => $category]);
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
        $exist = SCategory::where('id', '<>', $id)
                          ->where('name', '=', $request['name'])
                          ->first();

        if(empty($exist)){
            SCategory::where('id', $id)->update([
                'name' => $request['name'],
                'color' => $request['color']
            ]);
    
            return response()->json(['status' => 'ok', 'result' => 'updated']);
        }else{
            return response()->json(['status' => 'fail', 'result' => 'existed']);
        }
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
        
        try{
            $category = SCategory::find($id);
            $questions = $category->sQuestions()->get();
            if(count($questions) == 0){
                $category->delete();

                $result['status'] = 'ok';
                $result['message'] = 'deleted';
            }else{
                $result['status'] = 'fail';
                $result['message'] = $questions; //'There exists an answer.'
            }
        }catch(Exception $e){
            $result['status'] = 'exception';
            $result['message'] = $e->getMessage();
        }
        

        return response()->json($result);
    }
}
