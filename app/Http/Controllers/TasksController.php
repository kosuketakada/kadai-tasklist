<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $user = \Auth::user();
        $tasks = $user->tasks()->orderBy('created_at' , 'desc')->paginate(10);
        
        $data = [
            'user' => $user,
            'tasks' => $tasks,
            ];
        
        return view('tasks.index', $data);
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = new Task;

        return view('tasks.create', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:191',
        ]);

        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect('/');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tasks = Task::find($id);
        if($tasks ===null){
            return view('welcome');
        }
        if(\Auth::user()->id===$tasks->user_id){
        return view('tasks.show', [
            'tasks' =>$tasks,
        ]);
        }
        else{
            return redirect('/');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $tasks = Task::find($id);
         if($tasks===null){
                return view ('welcome');
         }        
        if(\Auth::user()->id===$tasks->user_id){
        return view('tasks.edit', [
            'tasks' => $tasks,
        ]);
        }
        else{
            return redirect('/');
        }
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
        $this->validate($request, [
            
            'status'=>'required|max:10',
            'content' => 'required|max:191',
        ]); 
        
        $tasks = Task::find($id);
       
        $tasks->status = $request->status;
        $tasks->content = $request->content;
        $tasks->save();

        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $tasks = Task::find($id);
        $tasks->delete();

        return redirect('/tasks');
    }
}