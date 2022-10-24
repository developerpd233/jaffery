<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentApiController extends Controller
{
    public function store(Request $request) {

        $data = $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string'
        ]);
        
        $comment = Comment::create($data);

        if ($comment) {
            $res = ['status' => 'success', 'message' => 'Comment created.'];
            return response($res, 200);
        }

        $res = ['status' => 'error', 'message' => 'Comment not created!'];
        return response($res, 400);
    }

    public function update(Request $request, Comment $comment) {

        $data = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->comment = $data['comment'];
        $comment->save();

        if ($comment) {
            $res = ['status' => 'success', 'message' => 'Comment updated.'];
            return response($res, 200);
        }

        $res = ['status' => 'error', 'message' => 'Comment not updated!'];
        return response($res, 400);
    }

    public function destroy(Request $request, Comment $comment) {

        $comment = $comment->delete();

        if ($comment) {
            $res = ['status' => 'success', 'message' => 'Comment deleted.'];
            return response($res, 200);
        }

        $res = ['status' => 'error', 'message' => 'Comment not deleted!'];
        return response($res, 400);
    }
}
