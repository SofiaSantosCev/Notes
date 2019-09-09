<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index(){

    }

    public function store(Request $request){
        $title = $request->title;
        $content = $request->content;

        if (empty($title)) {
            return response("You need to write a title for the note", 400); 
        }

        $newNote = new Note;
        $newNote->title = $title;
        $newNote->content = $content;

        $newNote->save();

        return parent::response('Note created successfully', 200);
    }

    public function update(Request $request, Note $note){

    }

    public function destroy($id){
        $note = Note::where('id',$id)->first();
        $note->delete();
        return parent::response('Note deleted', 201);
    }
}
