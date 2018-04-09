<?php
namespace App\Helpers;

use App\EventRule;
use App\Helpers\SmsGatewayHelper;
use App\Helpers\TimeConvertHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EventRuleCheckHelper {
  private $timeConvertHelper;

  public function __construct(TimeConvertHelper $timeConvertHelper) {
    $this->smsHelper = new SmsGatewayHelper('your@email.com', 'pass321');
    $this->timeConvertHelper = $timeConvertHelper;
  }

  public function check()
  {   $nowRaw = Carbon::now('UTC')->toTimeString();
      $now = Carbon::createFromFormat('H:i:s', $nowRaw, 'UTC')->toTimeString();
      $today = Carbon::now('UTC')->toDateString();
      $currentWeekday = $this->timeConvertHelper->getCurrentWeekday();
      $rules = EventRule::where('notification_at', '<=' , $now)
      ->where(function ($q) use ($today) {
          $q->whereRaw('created_at = updated_at')
          ->orWhere('updated_at', '<', $today);
      })
      ->where('weekday', '=', $currentWeekday)
      ->get();

      Log::debug($rules);

      foreach ($rules as $rule) {
        $stopId = $rule->stop;
        $scheduleId = $rule->schedule_id;
        $offset = $rule->offset;
        $ruleId = $rule->id;
        $objectName =  $rule->object_name;
        $tranportType = $rule->transport_type;
        $phoneNo = $rule->owner->phone;

        $arivalsArray = $this->getLiveArivals($stopId, $scheduleId);
        Log::debug($arivalsArray);
        $notificationNeeded = $this->checkNotificationOffsetRule($offset, $arivalsArray);
        if ($notificationNeeded) {
          $message = $this->prepareSmsText($objectName, $tranportType, $offset);
          Log::debug($message);
          $this->sendSMS($message, $phoneNo);
          $this->updateDatetimeWhenNotificationSent($ruleId);
          Log::debug('notification sent');
        } else {
          Log::debug('no notification still needed for '. $scheduleId);
        }
      }
  }

  protected function getLiveArivals($stopId, $scheduleId) {

    $url='http://api-ext.trafi.com/departures?api_key=your_api_key&region=vilnius';
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

  protected function prepareSmsText($objectName, $tranportType, $offset){
    $message = 'Your ' . $tranportType . ' No.' . $objectName . ' arrives in ' . $offset . ' min';
    return $message;
  }


  public function sendSMS($message, $phoneNo) {

    $deviceID = 82787;
    $number = $phoneNo;

    $options = [
    // 'send_at' => strtotime('+10 minutes'), // Send the message in 10 minutes
    // 'expires_at' => strtotime('+1 hour') // Cancel the message in 1 hour if the message is not yet sent
    ];

    //Please note options is no required and can be left out
    $result = $this->smsHelper->sendMessageToNumber($number, $message, $deviceID, $options);
    dump( $result);
  }
}
