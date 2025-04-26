<?php

namespace App\Http\Requests\Api;

use App\Models\Address;
use App\Models\MenuItem;
use App\Models\SplurgeEvent;
use App\Models\SplurgeEventUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;
use App\Support\Data\Import\ImportAttribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
class ImportSplurgeEventUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('api')->can('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sheet' => ['sometimes', 'integer'],
            'spreadsheet' => ['required', 'file', 'mimes:xlxs,csv']
        ];
    }

    static function getColumns() {
        return [
            new ImportAttribute('first_name', ['name', 'full_name', 'full_name', 'first_name', 'first name'], true),
            new ImportAttribute('last_name', ['last_name','last name'], false, ''),
            new ImportAttribute('gender', ['gender', 'sex']),
            new ImportAttribute('email', ['email', 'email address', 'email_address']),
            new ImportAttribute('phone', ['phone', 'phone number', 'phone_number']),
            ImportAttribute::simple('title'),
            new ImportAttribute('table', ['table', 'assigned table']),
            new ImportAttribute('menu_items', ['menu items', 'menu preferences', 'menu']),
        ];
    }

    public function commit(SplurgeEvent $event): Collection {
        return DB::transaction(function () use ($event) {
            $file = $this->file('spreadsheet');
            $reader = new Reader();
            $emptyCount = 0;
            try {
                
                $sheetIndex = (int) $this->input('sheet') ?: 0;

                $venue = $event->locations()->whereIn("purpose", ['RECEPTION', 'reception'])->first();
                if (is_null($venue)) {
                    $venue = $event->locations()->create(['purpose' => 'RECEPTION', 'name' => '<Reception>', 'line1' => '(Automatically created.  Please update)', 'state' => '']);
                }
                $tables = $venue->tables()->get();

                $menuItems = MenuItem::all();

                $users = collect([]);
                
                $reader->open($file->getRealPath());
                foreach ($reader->getSheetIterator() as $sheet) {
                    if ($sheet->getIndex() == $sheetIndex) {
                        $columns = collect(self::getColumns());
                        $headerRowFound = false;
                        
                        foreach ($sheet->getRowIterator() as $row) {
                            if ($row->isEmpty()) {
                                $emptyCount += 1;
                                if ($emptyCount > 1) {
                                    break;
                                }
                                continue;
                            }          
                            if (!$headerRowFound) {
                                $headerRowFound = self::fillHeaders($row, $columns);
                                continue;
                            } 

                            $values = [];

                            foreach ($columns as $column) {
                                if (!$column->isFound()) {
                                    if (!is_null($column->getDefaultValue())) {
                                        $values[$column->getName()] = $column->getDefaultValue();
                                    }
                                    continue;
                                }
                                $values[$column->getName()] = (string)$row->getCellAtIndex($column->getIndex())->getValue();

                            }

                            if (empty($values['last_name'])) {
                                $fullName = collect(explode(' ', $values['first_name']));
                                $values['first_name'] = $fullName->first();
                                $values['last_name'] = $fullName->skip(1)->join(" ");                               
                            }
                            $users->add(self::insert($event, $venue, $values, $tables, $menuItems));
                        }
                    } else {
                        break;
                    }
                }
                return $users;
            } finally {
                $reader->close();
                @unlink($file->getRealPath());
            }
            

            
        });
    }

    static function insert(SplurgeEvent $event, Address $location, array $values, Collection &$tables, Collection &$menuItems) {
        $primary_attributes = Arr::only($values, ['first_name', 'last_name', 'gender', 'title', 'phone', 'email']);
        $user = $event->members()->create(array_merge($primary_attributes, ['role' => 'GUEST']));
        if (!empty(Arr::get($values, 'table'))) {
            $table = $values['table'];
            $table_model = $tables->firstWhere(function ($t) use ($table) {
                return strcasecmp($t->name, $table) === 0;
            });
            if (is_null($table_model)) {
                $table_model = $location->tables()->create(['name' => $table]);
            }
            $user->tables()->create(['table_id' => $table_model->id]);     
        }

        if ($menuPreferences = Arr::get($values, 'menu_items')) {
            $lines = collect(explode('\n', $menuPreferences))->map(fn ($x) => trim($x))->filter(fn ($x) => !empty($x));
            if (!$lines->isEmpty()) {
                foreach ($lines as $item) {
                    $menuItem = $menuItems->firstWhere(fn ($i) => $i->name == $item);
                    if (is_null($menuItem)) {
                        $menuItem = MenuItem::create(['name' => $item]);
                        $menuItems->add($menuItem);
                    }
                    $user->menuItems()->create(['menu_item_id' => $menuItem->id]);
                }
            }
        }
        return $user;
    }

    private static function fillHeaders(Row $row, Collection $columns): bool {
        $requiredCount = $columns->reduce(function (int $carry, ImportAttribute $attr) {
            if ($attr->isRequired()) {
                return $carry + 1;
            }
            return $carry;
        }, 0);

        $foundCount = 0;
        foreach ($row->getCells() as $columnIndex => $cell) {
           $okay = $columns->contains(function (ImportAttribute $col) use ($cell, $columnIndex){
                if ($col->matches(Str::lower((string)$cell->getValue()))) {
                    $col->setIndex($columnIndex);
                    $col->setResolvedName((string)$cell->getValue());
                    return true;
                }
                return false;
            });
            if ($okay) {
                $foundCount += 1;
            }
        }
        return $foundCount >= $requiredCount;
    }
    
}
