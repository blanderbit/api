<?php

namespace App\Http\Controllers;
use App\Event;
//use App\Comment;
//use App\Rent;
use App\User;
use DateTime;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function getEvents(Request $request)
    {
        $old_events = Event::orderByDesc('id')
            ->paginate();
        return response()->json($old_events);
    }

    public function getOneEvent(Request $request, $id)
    {
        $old_event = Event::all()->find($id);
//        all()->find($id)
//            ->get();
//        dd($old_event);
        return response()->json($old_event);
    }


    public function getUserEvents(Request $request, $id)
    {
//
        $old_events = Event::where('user_id', $id)
            ->get();
//        $like = EventsLike::where('events_id', $events_id)->get();
//        $old_events->your_like =
//        $old_events = Event::where('user_id', $id)
//            ->with(['profile'])->get();
        return response()->json($old_events, 200);
    }
    public function getUserOneEvent(Request $request, $id, $id_event)
    {
        $old_events = Event::where('user_id', $id)->get()->find($id_event);
        return response()->json([$old_events]);
//        $post = Post::with(['rent', 'comment'])->find($id);
//        unset($post->comment_count);
//        unset($post->rent_count);
//        $rent = $post->rent;
//        $comment = Post::with('comment');
//        if($post == null || count($post) == 0){
//            return response()->json([
//                'message' => 'No such post',
//            ]);
//        }
//        dd($post);
//        return response()->json([$post],200);
//        compact('post', 'reviews', 'rents')
    }
    public function addEvent(Request $request, $id)
    {
        $request->validate([
            'event_name' => 'required',
            'about_event' => 'required',
            'location' => 'required',
        ]);
        $event = new Event($request->all());
        $date = new DateTime($request->get('deadline'));
        $event->deadline = date_format($date, 'Y-m-d H:i:s');
        $event->user_id = $id;
        $event->save();
        return response()->json([
            'message' => 'Successfully created event!',
            'event' => Event::find($event->id)
        ],200);
    }
    public function updateUserOneEvent(Request $request, $id, $id_event)
    {
        $event = Event::find($id_event);
        if($event == null ){
            return response()->json([
                'message' => 'No such post',
            ]);
        }
        $event->update($request->all());
        return response()->json([
            'message' => 'Successfully updated post!',
            "post" => $event
        ], 200);
    }
    public function removeUserOneEvent(Request $request, $id, $id_event )
    {
        $event = Event::find($id_event);
        if($event == null ){
            return response()->json([
                'message' => 'No such event',
            ]);
        }
        $event->delete();
        return response()->json([
            'message' => 'Successfully remove event with all data!',
        ], 200);
    }
}
