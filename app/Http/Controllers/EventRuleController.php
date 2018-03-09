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
    {   $nowRaw = Carbon::now('UTC')->toTimeString();
        dump($nowRaw);
        $now = Carbon::createFromFormat('H:i', $nowRaw)->toTimeString();
        var_dump($now);
        $today = Carbon::now('UTC')->toDateString();
        var_dump($today);
        $rules = EventRule::where('notification_at', '<=' , $now)
        ->where('updated_at', '<', $today)
        ->get();
        dump($rules);


        foreach ($rules as $rule) {
          $stopId = $rule->stop;
          $scheduleId = $rule->direction;
          $offset = $rule->offset;
          $ruleId = $rule->id;

          $arivalsArray = $this->getLiveArivals($stopId, $scheduleId);
          $notificationNeeded = $this->checkNotificationOffsetRule($offset, $arivalsArray);
          if ($notificationNeeded) {
            $this->updateDatetimeWhenNotificationSent($ruleId);
            dump('notification sent');
          } else {
            dump('no notification needed for '. $scheduleId);
          }

        }

        // $return = $this->getLiveArivals('vln_1921', 'vln_expressbus_3G');
        //
        // dump($return);
        //
        // $response = $this->checkNotificationOffsetRule(15, $return);
        // dump($response);
        //
        // $this->updateDatetimeWhenNotificationSent(2);

    }

    protected function getLiveArivals($stopId, $scheduleId) {

      $url='http://api-ext.trafi.com/departures?api_key=4194f417c45ce354aa7994dcd6594cc7&region=vilnius';
      $fullUrl=$url . "&stopId=" . $stopId;
      $json=file_get_contents($fullUrl);
      $array = json_decode($json, true);
      $foundScheduleIdIndex = 0;
      $schedulesArray = $array['Schedules'];

      for ($i=0; $i < count($schedulesArray) ; $i++) {
        $collection = collect($schedulesArray[$i]);
        if ($collection->contains($scheduleId)) {
          $foundScheduleIdIndex = $i;
        }
      }
      $departureTimeArray = [];
      foreach ($schedulesArray[$foundScheduleIdIndex]['Departures'] as $item) {
        array_push($departureTimeArray, $item['RemainingMinutes']);
      }
      dump($departureTimeArray);
      return $departureTimeArray;
    }

    protected function checkNotificationOffsetRule($offset, Array $arivals) {
      if (in_array($offset, $arivals)) {
        return true;
      } else {
        return false;
      }
    }

    protected function updateDatetimeWhenNotificationSent($ruleId) {
      $now = Carbon::now('UTC')->toDateTimeString();
      $item = EventRule::find($ruleId);
      $item->updated_at = $now;
      $item->save();
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

      $notificationAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->subMinutes($offset+4)->toTimeString();



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
        $result = $this->getCurrentWeekday();
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
