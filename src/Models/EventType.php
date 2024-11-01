<?php

namespace ELKLab\ELKAnalytics\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Event type model
 * 
 * @since 1.0.0
 */
class EventType extends Model {
    protected $table = 'elk_analytics_event_types';

    protected $fillable = [
        'name'
    ];

    public function events() {
        return $this->hasMany(Event::class);
    }
}
