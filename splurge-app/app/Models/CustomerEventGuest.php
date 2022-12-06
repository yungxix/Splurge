<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CustomerEventGuest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender', 'table_name', 'accepted', 'presented', 'attendance_at', 'barcode_image_url'];

    protected $casts = [
        'accepted' => 'array',
        'presented' => 'array',
        'attendance_at' => 'datetime'
    ];


    public function customerEvent() {
        return $this->belongsTo(CustomerEvent::class);
    }

    public function getAttendanceTime() {
        if (is_null($this->attendance_at)) {
            return null;
        }
        return $this->attendance_at->format('H:i');
    }

    public static function filteredBy($builder, Request $request) {
        foreach (['table' => 'table_name', 'q' => '*'] as $key => $attr) {
            if (!empty($request->input($key))) {
                if ('*' === $key) {
                    $term = sprintf('%%%s%%', $request->input($key));
                    foreach (['name', 'table_name'] as $idx => $field) {
                        if ($idx == 0) {
                            $builder = $builder->where($field, 'like', $term);
                        } else {
                            $builder = $builder->orWhere($field, 'like', $term);
                        }
                    }
                } else {
                    $builder = $builder->where($attr, 'like', sprintf('%%%s%%', $request->input($key)));
                }
                
            }
        }
        return $builder;
    }
}
