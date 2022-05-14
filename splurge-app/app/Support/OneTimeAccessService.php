<?php

namespace App\Support;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\SplurgeAccessToken;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OneTimeAccessService {
    private $request;

    static $entityMap = [
        'booking' => Booking::class,
        'bookings' => Booking::class,
    ];

    static $COOKIE_NAME = "splurge.access_id";

    static $redirectMap = [
        Booking::class => ['route' => 'my.bookings.show', 'arg' => 'booking']
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function start(): array {
        $token = $this->request->input('tk');
        
        $entity = $this->request->route('entity');
        
        $entity_class = Arr::get(static::$entityMap, $entity, $entity);
       

        $access = SplurgeAccessToken::where([
            'id' => $this->request->input('a'),
            'access_type' => $entity_class,
        ])->firstOrFail();

        if (!Hash::check($access->token, $token)) {
            throw new BadRequestHttpException("Invalid access token", null, 403);
        }   



        $user = $access->user;

        Auth::guard('my')->login($user, false);

        $this->request->session()->put(static::$COOKIE_NAME, $access->id);

        if ($this->request->has("d")) {
            return [
                'url' => url($this->request->input('d'))
            ];
        }
        
        $redirectInfo = static::$redirectMap[$entity_class];

      
        $redirect_args = [];

        if (isset($redirectInfo['arg'])) {
            $redirect_args[$redirectInfo['arg']] = $access->access_id;
        }
        return [
            'url' => route($redirectInfo['route'], $redirect_args)
        ];
    }

    public function check() {
        $accessId = $this->request->session()->get(static::$COOKIE_NAME);

        if (!$accessId) {
            return false;
        }
        $access = SplurgeAccessToken::where([
            'id' => $accessId,
            'user_id' => Auth::guard('my')->id()
        ])->where("expires_at", ">=",
         Carbon::now())->selectRaw("1")->take(1)->get();



        return !$access->isEmpty();
    }
}