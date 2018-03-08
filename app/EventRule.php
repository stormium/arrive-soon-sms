<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRule extends Model
{
  protected $fillable = [
    'stop',
    'direction',
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
