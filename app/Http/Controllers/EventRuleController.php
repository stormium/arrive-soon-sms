<?php

namespace App\Http\Controllers;

use App\EventRule;

use Illuminate\Http\Request;
use Carbon\Carbon;

class EventRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::createFromFormat('H:i', '18:24')->toTimeString();
        var_dump($now);
        $rules = EventRule::where('notification_at', $now)->get();

        $notificationsArray = [];
        foreach ($rules as $rule) {
          $notification = $rule->notifications;
          dump($notification);
          $notificationsArray[] = $notification;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $userTimeZone = 'Europe/Vilnius';

      $weekday = $request->get('departuresDayOption');

      $offset = $request->get('offset');

      $departureAtString = $request->get('departures');

      $departureAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->toTimeString();

      $notificationAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->subMinutes($offset+5)->toTimeString();

      //$notificationAt = $time->subMinutes($offset);
      $post = [
        'stop' => $request->get('stop'),
        'direction' => $request->get('directions'),
        'departure_at' => $departureAt,
        'weekday' => $request->get('departuresDayOption'),
        'notification_at' => $notificationAt
      ];

      EventRule::create($post);

      return view('index1');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EventRule  $eventRule
     * @return \Illuminate\Http\Response
     */
    public function show(EventRule $eventRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EventRule  $eventRule
     * @return \Illuminate\Http\Response
     */
    public function edit(EventRule $eventRule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EventRule  $eventRule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventRule $eventRule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventRule  $eventRule
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventRule $eventRule)
    {
        //
    }



    // public function generateNotifications(Array cronResponse) {
    //   foreach ($cronResponse as $item) {
    //     if (condition) {
    //       # code...
    //     }
    //   }
    //
    // }

    // protected function validator($data)
    // {
    //     return $data->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string|max:255',
    //         'price' => 'required|string|max:255',
    //         'imageUrl' => 'required|mimes:jpeg,bmp,png|max:6000'
    //       ]);
    // }
}
