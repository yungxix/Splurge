<?php

namespace App\Support;

// use App\Models\Booking;
use App\Models\Communication;
use Illuminate\Support\Arr;
use App\Models\Customer;

class CommunicationHelper {
    private $messages;
    private $email_hash;
    private $loaded;

    public function __construct($messages)
    {
        $this->messages = $messages;
        $this->email_hash = [
            'company' => config('app.name'),
            'splurge' => config('app.name'),
        ];
    }

    public function resolveSender(Communication $message) {
        return $this->resolveImpl($message, 'sender');
    }

    public function resolveReceiver(Communication $message) {
        return $this->resolveImpl($message, 'receiver');
    }


    private function resolveImpl(Communication $message, string $attribute): string {
        $value = $message[$attribute];
        if (Arr::has($this->email_hash, $value)) {
            return $this->email_hash[$value];
        }
        $full_key = sprintf("%s_%s_%s", $attribute, $message->channel_type, $message->channel_id);
        if (Arr::has($this->email_hash, $full_key)) {
            return Arr::get($this->email_hash, $full_key);
        }
        $this->tryLoad();
        return Arr::get($this->email_hash, $full_key, $value);
    }


    private function tryLoad() {
        if ($this->loaded) {
            return;
        }

        
        
        $this->loaded = true;

        // $booking_ids = $this->messages->filter(fn ($c, $i) => $c->receiver == 'customer' &&
        //                                       $c->channel_type === "Models/Booking")
        //                                 ->map(fn ($c, $i) => $c->channel_id)->toArray();

        // if (empty($booking_ids)) {
        //     return;
        // }                                

        // $booking_table = (new Booking())->getTable();

        // $customer_table = (new Customer())->getTable();

        // $customers = Customer::join($booking_table, "${customer_table}.id", "=", "${booking_table}.customer_id")
        //                     ->whereIn("${booking_table}.id", $booking_ids)
        //                     ->select("${customer_table}.first_name",
        //                      "${customer_table}.last_name",
        //                       "${customer_table}.email", "${booking_table}.id as booking_id")->get();


        // foreach ($customers as $customer) {
        //     $full_key = sprintf("receiver_%s_%s", Booking::class, $customer->booking_id);
        //     $this->email_hash[$full_key] = sprintf("%s %s <%s>",
        //      $customer->first_name, $customer->last_name, $customer->email);


        //      $full_key = sprintf("sender_%s_%s", Booking::class, $customer->booking_id);
        //      $this->email_hash[$full_key] = sprintf("%s %s <%s>",
        //       $customer->first_name, $customer->last_name, $customer->email);  
        // }                    

    }





}