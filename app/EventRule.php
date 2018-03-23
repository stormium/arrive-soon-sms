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
    'offset',
    'icon_url',
    'user_id'
  ];

  public function notifications()
  {
    return $this->hasMany('App\Notification', 'event_rule_id');
  }

  public function owner()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
}
