<?php

namespace App\Http\Requests;

use App\Models\CustomerEvent;
use App\Models\EventTable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class EventTableRequest extends FormRequest
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
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'table.name' => 'required|max:60',
                'table.capacity' => 'required|min:1'
            ];
        }
        if ($this->has('tables')) {

            return [
                'tables' => 'required|array',
                'tables.name' => 'required|max:60',
                'tables.capacity' => 'required|min:1',
            ];
        }
        return [
            'table.name' => 'sometimes|max:60',
            'table.capacity' => 'sometimes|min:1'
        ];
    }

    public function commitNew(CustomerEvent $event) {
        return DB::transaction(function () use ($event) {
            return $this->commitNewImpl($event);
        });
    }

    public function commitUpdate(EventTable $table) {
        return $table->update($this->validated('table'));
    }

    private function commitNewImpl(CustomerEvent $event) {
        if ($this->isBatch()) {
            return $event->eventTables()->createMany($this->validated('tables'));
        }
        return $event->eventTables()->create($this->validated('table'));
    }

    public function isBatch() {
        return $this->has('tables');
    }
}
