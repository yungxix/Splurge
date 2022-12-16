<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laminas\Barcode\Barcode;
use Illuminate\Support\Str;

class CustomerEventGuest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender', 'table_name', 'accepted', 'presented', 'attendance_at'];

    protected $casts = [
        'accepted' => 'array',
        'presented' => 'array',
        'attendance_at' => 'datetime'
    ];

    public static function generateTag(int $event_id) {
        $base = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ -.\$/+%";
        $max = strlen($base) - 1;
        $size = 8;
        $buffer = '';
        for ($i = 0; $i < $size; $i++) {
            $index = random_int(0, $max);
            $buffer .= $base[$index];
        }
        $addition = random_int(1, 9);
        $addition2 = random_int(1, 8);
        $prefix = Str::upper(Str::random(2));
        return sprintf('S%s%s-%s%s', $event_id * 10 + $addition, $buffer, $prefix, $event_id * 100 + $addition2);
    }

    public function customerEvent() {
        return $this->belongsTo(CustomerEvent::class);
    }

    public function getAttendanceTime() {
        if (is_null($this->attendance_at)) {
            return null;
        }
        return $this->attendance_at->format('H:i');
    }

    public function menuPreferences() {
        return $this->hasMany(MenuPreference::class, "guest_id");
    }

    public static function cleanTag($tag) {
        return preg_replace('/\-txi.+$/', '', $tag);
    }
     

    public function generateBarcode($save_after = FALSE) {
        if (empty($this->tag)) {
            throw new Exception("There is no tag to generate barcode with");
        }

        $dest = config('app.barcode.dest');



        $dir = public_path($dest);

        if (!file_exists($dir)) {
            mkdir($dir,0777,true);
        }

        $filename = sprintf('%s-g%s.png', Str::random(), $this->id);

        $real_path = $dir . DIRECTORY_SEPARATOR . $filename;
        $barcodeOptions = ['text' => $this->tag, 'drawText' => false, 'barHeight' => 60];
        $rendererOptions = [];

        $renderer = Barcode::factory(
            'code39',
            'image',
            $barcodeOptions,
            $rendererOptions
        );
        
        $image = $renderer->draw();
        imagepng($image, $real_path);
        @imagedestroy($image);
        $path = sprintf('%s/%s', $dest, $filename);
        $this->barcode_image_url = asset($path);
        if ($save_after) {
            $this->saveOrFail();
        }
        return $this;
    }

    public static function filteredBy($builder, Request $request) {
        foreach (['table' => 'table_name', 'name' => 'name', 'table_name' => 'table_name', 'q' => '*'] as $key => $attr) {
            if (!empty($request->input($key))) {
                $term = sprintf('%%%s%%', $request->input($key));
                if ('*' === $key) {
                    foreach (['name', 'table_name'] as $idx => $field) {
                        if ($idx == 0) {
                            $builder = $builder->where($field, 'like', $term);
                        } else {
                            $builder = $builder->orWhere($field, 'like', $term);
                        }
                    }
                } else {
                    $builder = $builder->where($attr, 'like', $term);
                }
                
            }
        }
        foreach (['tag'] as $field) {
            if ($request->has($field)) {
                $builder = $builder->where($field, static::cleanTag($request->input($field)));
            }
        }
        return $builder;
    }
}
