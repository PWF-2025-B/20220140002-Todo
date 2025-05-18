<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::all();
        // $todos = Todo::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        // dd($todos);
        // $todos = Todo::where('user_id', Auth::id())
        //     ->orderBy('is_complete', 'asc')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);
    
        $todos = Todo::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('is_complete', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        $todoCompleted = Todo::where('user_id', Auth::id())
            ->where('is_complete', true)
            ->count();
    
        return view('todo.index', compact('todos', 'todoCompleted'));
    }
    

    public function create()
    {
        $categories = Category::where('user_id', auth()->user()->id)->get();
        return view('todo.create', compact('categories'));
    }

    public function edit(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id){
            $categories = Category::where('user_id', auth()->user()->id)->get();
            return view('todo.edit', compact('todo', 'categories'));
        } else{
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'tittle' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $todo->update([
            'tittle' => ucfirst($request->tittle),
            'category_id' => $request->category_id,
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
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_complete', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }
        
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }

    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'tittle' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $todo = Todo::create([
            'tittle' => ucfirst($request->tittle),
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }
}