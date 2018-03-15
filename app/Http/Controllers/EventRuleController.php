<?php

namespace App\Http\Controllers;

use App\EventRule;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Helpers\EventRuleCheckHelper;
use App\Helpers\TimeConvertHelper;

class EventRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $smsHelper;
    private $eventRuleCheckHelper;
    private $timeConvertHelper;

    public function __construct(TimeConvertHelper $timeConvertHelper, EventRuleCheckHelper $eventRuleCheckHelper) {

      $this->eventRuleCheckHelper = $eventRuleCheckHelper;
      $this->timeConvertHelper = $timeConvertHelper;
    }


    public function index()
    {
      $this->eventRuleCheckHelper->check();

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
      $convertedWeekday = $this->timeConvertHelper->convertToWeekdayName($weekday);

      $offset = $request->get('offset');

      $departureAtString = $request->get('departures');

      $departureAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->toTimeString();

      $notificationAt = Carbon::createFromFormat('H:i', $departureAtString, $userTimeZone)->setTimezone('UTC')->subMinutes($offset+3)->toTimeString();

      $scheduleId = $request->get('directions');

      $transportType = $this->timeConvertHelper->getTransportType($scheduleId);

      $post = [
        'stop' => $request->get('stop'),
        'object_name' => $request->get('objectName'),
        'transport_type' => $transportType,
        'schedule_id' => $request->get('directions'),
        'departure_at' => $departureAt,
        'weekday' => $convertedWeekday,
        'notification_at' => $notificationAt,
        'offset' => $request->get('offset'),
      ];

      EventRule::create($post);

      return route('index1');
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

}
