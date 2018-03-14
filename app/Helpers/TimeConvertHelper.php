<?php
namespace App\Helpers;

use Carbon\Carbon;

class TimeConvertHelper {

  public function convertToWeekdayName($value) {
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

  public function getCurrentWeekday() {
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

  public function getTransportType($scheduleId) {
    $target = '';
    if (preg_match('/_(.*?)\_/s', $scheduleId, $matches)) {
        $target = $matches[1];
    };

    dump($target);
    if ($target == 'bus') {
      return 'Bus';
    } elseif ($target == 'expressbus') {
      return 'ExpressBus';
    } elseif ($target == 'trol') {
      return 'Trolleybus';
    }
  }

}
