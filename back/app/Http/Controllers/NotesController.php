<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    //show all user's notes
    public function index(){
        if(!parent::checkLogin()) 
        {
            return parent::response('You are not logged in', 301);
        } 
        
        $user = parent::getUserFromToken();
        $user_id = $user->id; 

        $notes = Note::where('user_id', $user_id)->get();

        if(empty($notes)) {
            return parent::response('No notes created yet', 400);
        }
        
        return response()->json([
            'notes'=> $notes,
        ], 200);
    }

    public function store(Request $request){
        
        if (parent::checkLogin() == false) 
        {
            return response("No permissions", 301);
        }
        
        $user = parent::getUserFromToken();
        $title = $request->input('title');
        $content = $request->input('content');
        $category_id = $request->input('category_id');

        if($category_id == "none")
        {
            $category_id = null;
        }

        if (empty($title)) {
            return response("You need to write a title for the note", 400); 
        }

        $user_id = $user->id;

        $newNote = new Note;
        
        $newNote->title = $title;
        $newNote->content = $content;
        $newNote->user_id = $user_id;
        $newNote->category_id = $category_id;

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
