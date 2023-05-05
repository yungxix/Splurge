<?php

namespace App\Http\Requests;

use App\Models\CustomerEvent;
use App\Models\CustomerEventGuest;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Http\Requests\Support\Import\Column as ImportColumn;
use App\Http\Requests\Support\Import\GuestMenuPreferenceColumn;

class CustomerEventGuestImportRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'spreadsheet' => ['required', 'file'],
            'sheet' => ['nullable']
        ];
    }

    public function store(CustomerEvent $event) {
        return DB::transaction(function () use ($event) {
            return CustomerEventGuest::withoutEvents(function () use ($event) {
                return $this->storeImpl($event);
            });
        });
    }


    private function storeImpl(CustomerEvent $event) {
        $file = $this->file('spreadsheet');
        if (preg_match('/csv$/i', $file->getClientOriginalExtension())) {
            return $this->importFromCSV($event, $file);
        }
        return $this->importFromXlsX($event, $file);
    }

    private function importFromCSV(CustomerEvent $event, UploadedFile $file) {
        $reader = new CSVReader();
        $reader->open($file->getRealPath());
        return $this->importFromSheetV2($event, $reader->getCurrentSheet());
    }

    private function importFromSheetV2(CustomerEvent $event, $sheet) {
        $guestColumns  = [
            new ImportColumn(['name', 'guest name'], 'name'),
            new ImportColumn(['gender', 'sex'], 'gender'),
            new ImportColumn(['table', 'table name'], 'table_name')
        ];

        $menuPreference = new GuestMenuPreferenceColumn(['menu', 'preferences'], 'name');

        $foundColumns = false;

        $blankCount = 0;

        $affected = 0;
        foreach ($sheet->getRowIterator() as $row) {
            $cells = $row->getCells();
            if (!$foundColumns) {
                foreach ($guestColumns as $col) {
                    $col->lookup($cells);
                }
                $menuPreference->lookup($cells);

                $foundColumns = static::testColumnFound($guestColumns);
                
                continue;
            } 

            $offsetIndex = static::getMinimumColumnIndex($guestColumns);

            if (static::isBlankRow($cells, $offsetIndex, 20)) {
                $blankCount++;
                if ($blankCount > 2) {
                    break;
                }
                continue;
            }

            $guest = new CustomerEventGuest();
            foreach ($guestColumns as $col) {
                $col->writeTo($guest, $cells);
            }

            $guest->tag = Str::random(10) . sprintf('-ev%s', $event->id);
            
            $guest->generateBarcode(FALSE);

            $event->guests()->save($guest);

            $menuPrefs = $menuPreference->valueFrom($cells);

            if (!empty($menuPrefs)) {
                $guest->menuPreferences()->saveMany($menuPrefs);
            }

            $affected += 1;

            
        }

        return $affected;
    }

    private static function getMinimumColumnIndex($columns) {
        $def = 10000000;
        $min = $def;

        foreach ($columns as $col) {
            $index = $col->getIndex();
            if ($index < 0) {
                continue;
            }
            if ($min > $index) {
                $min = $index;
            }
        }

        if ($min === $def) {
            return 0;
        }
        return $min;
    }

    private static function testColumnFound($columns) {
        if (is_array($columns)) {
            foreach ($columns as $col) {
                if ($col->isFound()) {
                    return true;
                }
            }
            return false;
        }
        return $columns->isFound();
    }

    private function importFromXlsX(CustomerEvent $event, UploadedFile $file) {
        $reader = new XLSXReader();
        $reader->open($file->getRealPath());
        $sheetSpec = $this->input('sheet');
        
        if (is_null($sheetSpec)) {
            return $this->importFromSheetV2($event, $reader->getCurrentSheet());
        } else {
            $sheetName = is_integer($sheetSpec) ? "Sheet $sheetSpec" : $sheetSpec;
            $sheetIndex = is_integer($sheetSpec) || preg_match('/^\d+$/', $sheetSpec) ? intval($sheetSpec) : -1;
            foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheetName == $sheet->getName() || $sheet->getIndex() == $sheetIndex) {
                    return $this->importFromSheetV2($event, $sheet);
                }
            }
            throw new Exception("Sheet to import from is not specified");
        }
    }

    private static function isBlankRow($cells, $offset, $columnCount) {
        for ($i = 0; $i < $columnCount; $i++) {
            $index = $offset + $i;
            $value = $cells[$index]->getValue();
            if (!empty($value)) {
                return false;
            }
        }
        return true;
    }

    
}
