<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Participant;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $comment = Comment::create([
            'participant_id' => $request->participant_id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment
        ]);

        return response()->json(['code'=>200, 'message'=>'Comment Created successfully','data' => [$comment,$comment->user]], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd($id);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->participant_id = $request->participant_id;
        $comment->user_id = auth()->user()->id;
        $comment->comment = $request->comment;
        $comment = $comment->save();

        return response()->json(['code'=>200, 'message'=>'Comment updated successfully','data' => $comment], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['code'=>200, 'message'=>'Comment Deleted successfully','data' => '1'], 200);
    }
}
