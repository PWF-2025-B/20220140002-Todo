<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection ;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->user()->id)

            ->orderBy('is_complete','asc')

            ->orderBy('created_at','desc')

            ->get();
     
        //dd($todos);
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->count();
        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id){
            //dd($todo);
            return view('todo.edit', compact('todo'));
        } else{
            //abort(403);
            // abort(403,'not authorized');
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'tittle' => 'required|max:255',
        ]);

        // Practical
        //$todo->tittle = $request->tittle;
        // $todo->save()

        //Eloquent Way - Readable
        $todo->update([
            'tittle' => ucfirst($request->tittle),
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }

    public function complete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_complete' => true,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_complete' => false,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function deleteallcomplete()
    {
        // get all todos for current user where is_completed is true
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }
        
        // dd($todosCompleted);
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }



    public function store(Request $request, Todo $todo)
    {
        $request->validate([

            'tittle' => 'required|max:255',

        ]);

        //Practical

        //$todo = new Todo;

        //$todo->title = $request->title;

        //$todo->user_id = auth()->user()->id();

        //$todo->save();



        // Query Builder way
        // DB::table('todos')->insert([
        //      'title' => $request->title,
        //      'user_id' => auth()->user()->id,
        //      'created_at' => now(),
        //      'updated_at' => now(),
        // ]);

        // Eloquent Way - Readable

        $todo = Todo::create([
            'tittle' => ucfirst($request->tittle),
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }
}
