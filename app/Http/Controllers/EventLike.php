<?php

namespace App\Http\Controllers;
use App\EventsLike;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventLike extends Controller
{
    public function getLikeForEvents(Request $request, $events_id)
    {
        $like = EventsLike::where('events_id', $events_id)->orderByDesc('id')->paginate();;
        return response()->json($like, 200);
    }

    public function toggleLikeForEvents(Request $request, $events_id)
    {
        $like = EventsLike::where('events_id', $events_id)
            ->where('id_user', $request->get('id_user'))->first();
        if($like == null){
            $like_events = new EventsLike($request->all());
            $like_events->events_id = $events_id;
            $like_events->date = Carbon::now();
//            dd($like_events,$request->all());
            $like_events->save();
            return response()->json([
                'message' => 'like create',
                'data' => $like_events,
                'status' => 201
            ], 201);
        };
        $like->delete();
        return response()->json([
            'message' => 'like delete',
            'status' => '200'
        ], 200);
    }
}
