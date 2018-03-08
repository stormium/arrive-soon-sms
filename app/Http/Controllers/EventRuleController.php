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
    {   $now = Carbon::now('UTC')->toTimeString();
        //dd($carbonNow);
        //$now = Carbon::createFromFormat('H:i', $carbonNow)->toTimeString();
        var_dump($now);
        $today = Carbon::now('UTC')->toDateString();
        var_dump($today);
        $rules = EventRule::where('notification_at', '<=' , $now)
        ->where('updated_at', '<', $today)
        ->get();
        dump($rules);
        $notificationsArray = [];
        foreach ($rules as $rule) {
          $notification = $rule->notifications;
          $notificationsArray[] = $notification;
        }
        $return = $this->getLiveArivals('vln_1921');

        dump($return);

    }

    protected function getLiveArivals($stopId) {

      $url='http://api-ext.trafi.com/departures?api_key=4194f417c45ce354aa7994dcd6594cc7&region=vilnius';
      $fullUrl=$url . "&stopId=" . $stopId;
      $json=file_get_contents($fullUrl);
      $array = json_decode($json, true);
      return $array['Schedules'];
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
      $convertedWeekday = $this->convertToWeekdayName($weekday);

      $offset = $request->get('offset');

      $departureAtString = $request->get('departures');

      $departureAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->toTimeString();

      $notificationAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->subMinutes($offset+5)->toTimeString();



      $post = [
        'stop' => $request->get('stop'),
        'direction' => $request->get('directions'),
        'departure_at' => $departureAt,
        'weekday' => $convertedWeekday,
        'notification_at' => $notificationAt,
        'offset' => $request->get('offset'),
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

    protected function convertToWeekdayName($value) {
      if ($value == 0) {
        $result = getCurrentWeekday();
        return $result;
      } elseif ($value == 1) {
        return 'Workday';
      } elseif ($value == 2) {
          return 'Saturday';
        } else {
          return 'Sunday';
        }
    }

    protected function getCurrentWeekday() {
      $result = '';
      $workdays = [
        'Monday',
        'Tuesday',
        'Thursday',
        'Wednesday',
        'Friday'
      ];
      $weekday = Carbon::now()->format('l');
      if (in_array($weekday, $workdays)) {
        return 'Workday';
      } else {
        return $weekday;
      }
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
