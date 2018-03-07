<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  protected $fillable = [
    'sent_on',
    'event_rule_id'
  ];

    public function rules()
    {
        return $this->belongsTo('App\EventRule');
    }
}
