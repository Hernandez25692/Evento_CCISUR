<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordResetCode extends Model
{
    protected $fillable = ['email', 'code', 'expires_at'];

    protected $dates = ['expires_at'];

    public $timestamps = true;

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
