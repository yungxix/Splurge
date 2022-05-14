<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;

use Illuminate\Support\Str;

use Illuminate\Support\Carbon;

class SplurgeAccessToken extends Model
{
    use HasFactory;

    protected $fillable = ['access_type', 'access_id', 'token', 'expires_at', 'user_id'];

    protected $casts = ['expires_at' => 'datetime'];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function toAutoLoginURL() {
        $query = [
            'exp' => $this->expires_at->getTimestamp(),
            'tx' => Str::random(12),
            'tk' => bcrypt($this->token),
            'v' => 2,
            "a" => $this->id
        ];

        $class_name = Str::of(class_basename($this->access_type))
        ->lower()->
        plural()->
        toString();

        return url(sprintf("/access/%s/%s/?%s", $class_name, urlencode(Str::uuid()), Arr::query($query)));
    }

    public function access() {
        return $this->morphTo("access");
    }

    public function isExpired() {
        if (is_null($this->expires_at)) {
            return false;
        }
        return Carbon::now()->isAfter($this->expires_at);
    }
}
