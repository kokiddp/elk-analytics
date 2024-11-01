<?php

namespace ELKLab\ELKAnalytics\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * User model
 * 
 * @since 1.0.0
 */
class User extends Model {
    protected $table = 'elk_analytics_users';

    protected $fillable = [
        'ip',
        'user_agent',
        'browser_type',
        'browser_version',
        'os_type',
        'os_version',
        'device',
        'country',
        'session_id'
    ];

    public function events() {
        return $this->hasMany(Event::class);
    }
}
