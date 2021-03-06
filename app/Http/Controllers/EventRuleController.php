<?php

namespace App\Http\Controllers;

use App\EventRule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\TimeConvertHelper;
use App\Helpers\EventRuleCheckHelper;
use Auth;

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
      $this->middleware('auth');
    }

    //for manual refreshing
    public function index2()
    {
      $this->eventRuleCheckHelper->check();
    }


    public function index()
    {
      $myRules = EventRule::where('user_id', Auth::user()->id)->get();
      return view('index1')->with('myRules', $myRules);
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
      $this->validator($request);
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
        'search_value'=> $request->get('searchValue'),
        'stop' => $request->get('stop'),
        'object_name' => $request->get('objectName'),
        'transport_type' => $transportType,
        'schedule_id' => $request->get('directions'),
        'departure_at' => $departureAt,
        'weekday' => $convertedWeekday,
        'notification_at' => $notificationAt,
        'offset' => $request->get('offset'),
        'icon_url' => $request->get('iconUrl'),
        'user_id' => Auth::user()->id
      ];

      EventRule::create($post);

      return redirect()->route('index1');
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
    public function edit($id)
    {
        $ruleItem = EventRule::where('id', '=', $id)
        ->where('user_id', '=', Auth::user()->id)->firstOrFail();
        $rule = $ruleItem;

        return view('editRule', [
          'rule' => $rule
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EventRule  $eventRule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      $this->validator($request);
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
        'search_value'=> $request->get('searchValue'),
        'stop' => $request->get('stop'),
        'object_name' => $request->get('objectName'),
        'transport_type' => $transportType,
        'schedule_id' => $request->get('directions'),
        'departure_at' => $departureAt,
        'weekday' => $convertedWeekday,
        'notification_at' => $notificationAt,
        'offset' => $request->get('offset'),
        'icon_url' => $request->get('iconUrl'),
      ];

      $rule = EventRule::findOrFail($id);
      $rule->update($post);
      return redirect()->route('index1');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventRule  $eventRule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rule = EventRule::findOrFail($id);
        $rule->delete();

        //redirect
        return redirect()->route('index1');
    }

    protected function validator($data) {
      return $data->validate([
        'searchValue' => 'required|string|max:255',
        'stop' => 'required|string|max:255',
        'objectName' => 'required|string|max:255',
        'directions' => 'required|string|max:255',
        'departures' => 'required|string|date_format:H:i|max:255',
        'offset' => 'required|integer|between:5,20'
      ]);
    }

}
