<?php

namespace App\Http\Controllers;
use App\Event;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
//    get
    public function getCommentsForEvent(Request $request, $id_events)
    {
        $comments = Comment::where('event_id', $id_events)->orderByDesc('id')->paginate();
        return response()->json($comments, 200);
    }
    public function getOneCommentForEvent(Request $request, $id_events, $id_comment)
    {
        $comment = Comment::where('event_id', $id_events)->get()->find($id_comment);
        return response()->json($comment, 200);
    }

    public function addCommentForEvent(Request $request, $id_event)
    {
        $request->validate([
            'text_comment' => 'required',
        ]);
        $comment = new Comment($request->all());
        $comment->event_id = $id_event;
        $comment->save();
        return response()->json([
            'data' => $comment,
            'message' => 'ok'
        ], 200);
    }

    public function updateCommentForEvent(Request $request, $id_comments)
    {

        $request->validate([
            'text_comment' => 'required',
        ]);
        $comment = Comment::find($id_comments);
        $comment->update($request->all());
        return response()->json($comment, 200);
    }

    public function removeCommentForEvent(Request $request, $id_comments)
    {
        $comment = Comment::find($id_comments);
        if($comment == null){
            return response()->json(['message' => 'no found comment'], 400);
        }
        $comment->delete();
        return response()->json(['message' => 'succesfully delete comment'], 200);
    }

}
