<?php

namespace App\Http\Requests;

use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use App\Rules\DateOrTimeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
                'guest.name' => 'sometimes|max:25',
                'guest.table_name' => 'sometimes|max:150',
                'guest.gender' => 'sometimes|max:30',
                'guest.accepted' => 'sometimes|array',
                'guest.presented' => 'sometimes|array',
                'guest.attendance_at' => ['sometimes', new DateOrTimeRule()],
                'guest.barcode_image' => 'sometimes|image',
                'guest.barcode_image_url' => 'sometimes|url'
            ];    
        }
        return [
            'guest' => 'required|array',
            'guest.name' => 'required|max:255',
            'guest.table_name' => 'nullable|max:150',
            'guest.gender' => 'nullable|max:30',
            'guest.accepted' => 'nullable|array',
            'guest.presented' => 'nullable|array',
            'guest.attendance_at' => 'nullable|date'
        ];
    }

    public function commitNew(CustomerEvent $event) {
        return DB::transaction(function () use ($event) {
            return $this->commitNewImpl($event);
        });
    }

    private function grabBarcodeImage(array $data): array {
        $key = 'guest.barcode_image';
        if ($this->hasFile($key)) {
            $file = $this->file($key);
            $dir = public_path('img/barcodes');
            if (!file_exists($dir)) {
                mkdir($dir);
            }

            $filename = 'bc_' . Str::random() . '.' . $file->getClientOriginalExtension();
            
            $file->move($dir, $filename);

            $data['barcode_image_url'] = asset('img/barcodes/' . $filename);
        }
        return $data;
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
            $data = $this->grabBarcodeImage($data);
            $guest->fill($data);
            $guest->saveOrFail();
            return $guest;
        });
    }

    private function commitNewImpl(CustomerEvent $event) {
        $data = $this->input('guest');
        $guest = new CustomerEventGuest($this->grabBarcodeImage(static::attendanceToFullDate($data, $event)));
        $guest->tag = Str::random(5) . sprintf('-ev%s', $event->id);
        $event->guests()->save($guest);
        return $guest;
    }
}
