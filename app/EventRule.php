<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRule extends Model
{
  protected $fillable = [
    'search_value',
    'stop',
    'schedule_id',
    'object_name',
    'transport_type',
    'departure_at',
    'weekday',
    'notification_at',
    'offset'
  ];

  public function notifications()
  {
    return $this->hasMany('App\Notification', 'event_rule_id');
  }
}
