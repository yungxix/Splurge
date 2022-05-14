<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\SplurgeAccessToken;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

class SplurgeAccessTokenRepository {
    public function createFor($model, $expires_at = NULL): SplurgeAccessToken {

        return DB::transaction(function () use ($model, $expires_at) {
            $token = Str::random(12);

            $email = sprintf("%s@splurge_%s.com", Str::random(8), Str::random(4));

            if ($model instanceof Booking) {
                $user = User::create(['name' => $model->customer->fullName(), 'email' => $email, 'password' => bcrypt($token)]);
            } else {
                $user = User::create(['name' => 'Splurge Access', 'email' => $email, 'password' => bcrypt($token)]);
            }

            

            $access = new SplurgeAccessToken();

            $access->token = $token;
            $access->user_id = $user->id;
            $access->expires_at = $expires_at ?? Carbon::now()->addDays(config("app.default_splurge_access_duration"));
            $access->access_type = get_class($model);
            $access->access_id = $model->id;
            $access->saveOrFail();
            return $access;

        });
        


    }


    public function createForUser($model, $user_id = 0, $expires_at = NULL): SplurgeAccessToken {

        return DB::transaction(function () use ($model, $expires_at, $user_id) {
            $token = Str::random(24);


            $access = new SplurgeAccessToken();

            $access->token = $token;
            $access->user_id = $user_id;
            $access->expires_at = $expires_at ?? Carbon::now()->addDays(config("app.default_splurge_access_duration"));
            $access->access_type = get_class($model);
            $access->access_id = $model->id;
            $access->saveOrFail();
            return $access;

        });
        


    }

    public function findForModel($model, $user_id = NULL) {
        $query = SplurgeAccessToken::where([
            'access_type' => get_class($model),
            'access_id' => $model->id
        ]);

        if (is_null($user_id)) {
            $query = $query->where("user_id", ">", 0);
        } else {
            $query = $query->where("user_id", $user_id);
        }

        return $query->orderBy("expires_at", "desc")->firstOrFail();
    }


}