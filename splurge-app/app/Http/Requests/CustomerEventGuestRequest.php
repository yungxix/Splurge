<?php

namespace App\Http\Requests;

use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use App\Rules\DateOrTimeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CustomerEventGuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'guest' => 'required|array',
                'guest.person_count' => 'integer|min:1',
                'guest.name' => 'sometimes|max:25',
                'guest.table_name' => 'sometimes|max:150',
                'guest.gender' => 'sometimes|max:30',
                'guest.accepted' => 'sometimes|array',
                'guest.presented' => 'sometimes|array',
                'guest.attendance_at' => ['sometimes', new DateOrTimeRule()],
                'guest.menu_preferences' => ['sometimes', 'array'],
                'guest.menu_preferences.name' => ['sometimes', 'max:255'],
                'guest.menu_preferences.comment' => ['sometimes', 'max:255']
            ];    
        }
        return [
            'guest' => 'required|array',
            'guest.name' => 'required|max:255',
            'guest.person_count' => 'integer|min:1',
            'guest.table_name' => 'nullable|max:150',
            'guest.gender' => 'nullable|max:30',
            'guest.accepted' => 'nullable|array',
            'guest.presented' => 'nullable|array',
            'guest.attendance_at' => ['nullable', new DateOrTimeRule()],
            'guest.menu_preferences' => ['sometimes', 'array'],
            'guest.menu_preferences.name' => ['sometimes', 'max:255'],
            'guest.menu_preferences.comment' => ['sometimes', 'max:255']
        ];
    }

    public function commitNew(CustomerEvent $event) {
        return DB::transaction(function () use ($event) {
            return $this->commitNewImpl($event);
        });
    }


    private function grabMenuPrefences(CustomerEventGuest $guest) {
        $data = $this->input('guest.menu_preferences');
        if (empty($data)) {
            return;
        }
        
        $root = [
            'prefs' => $data
        ];
        Validator::make($root, [
            'prefs' => ['required', 'array'],
            'prefs.name' => ['required', 'max:255'],
            'prefs.comment' => ['nullable', 'max:255']
        ])->validate();

        $guest->menuPreferences()->delete();

        foreach ($data as $preference) {
            $guest->menuPreferences()->create($preference);
        }
    }

    private static function attendanceToFullDate(array $data, CustomerEvent $event): array {
        $time_attribute = 'attendance_at';
        if (isset($data[$time_attribute])) {
            if (preg_match('/^\d{2}\:/', $data[$time_attribute])) {
                $full_time = $event->event_date->format('Y-m-d') . ' ' . $data[$time_attribute];
                $data[$time_attribute] = $full_time;
            }
        }
        return $data;
    }

    public function commitEdit(CustomerEventGuest $guest, CustomerEvent $event) {
        return DB::transaction(function () use ($guest, $event) {
            $data = static::attendanceToFullDate($this->input('guest'), $event);
            
            $guest->fill($data);
            
            if (is_null($guest->barcode_image_url)) {
                $guest->generateBarcode(false);   
            }
            $guest->saveOrFail();
            $this->grabMenuPrefences($guest);
            return $guest;
        });
    }

    private function commitNewImpl(CustomerEvent $event) {
        $data = $this->input('guest');
        $guest = new CustomerEventGuest(static::attendanceToFullDate($data, $event));
        $guest->tag = Str::random(10) . sprintf('-ev%s', $event->id);
        $guest->generateBarcode(FALSE);
        $event->guests()->save($guest);
        $this->grabMenuPrefences($guest);
        return $guest;
    }
}
