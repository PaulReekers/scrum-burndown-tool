<?php

namespace App\Http\Controllers;

use Auth;
use App\Board;
use Illuminate\Http\Request;

class BoardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $boards = Board::orderBy('slug', 'desc')->get();

        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        return view('boards.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'boardId' => 'required|numeric|unique:boards',
            'slug' => 'required|unique:boards',
            'totalTaskFilter' => 'required|numeric',
            'tasksDoneFilter' => 'required|numeric',
        ]);

        $board = new Board([
            'boardId' => $request->get('boardId'),
            'slug' => $request->get('slug'),
            'totalTaskFilter' => $request->get('totalTaskFilter'),
            'tasksDoneFilter' => $request->get('tasksDoneFilter'),
        ]);

        $board->slug = str_slug($request->get('slug'), '-');

        $board->save();

        return redirect('/board/');
    }

    public function edit($id)
    {
        $boards = Board::where('id', $id)->first();

        return view('boards.edit', compact('boards'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'slug' => 'required|alpha_dash|min:2|unique:boards,id',
            'totalTaskFilter' => 'required|numeric',
            'tasksDoneFilter' => 'required|numeric',
        ]);
        $board = Board::find($id);

        $board->update($request->all());

        $request->session()->flash('success', 'Board updated.');

        return redirect('/board/' . $board->id . '/edit/');
    }

    public function destroy($id)
    {
        Board::find($id)->delete();

        return redirect('/board');
    }
}
