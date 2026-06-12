<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Shipment
            'po'            => 'required|string|max:100',
            'no_invoice'    => 'required|string|max:100',
            'no_bl'         => 'required|string|max:100',
            'supplier_id'   => 'required|exists:suppliers,id',
            'department_id' => 'required|exists:departments,id',
            'etd'           => 'required|date',
            'eta'           => 'required|date|after_or_equal:etd',
            'notes'         => 'nullable|string',

            // Status — nullable, hanya dikirim jika punya permission
            'status_id'    => 'nullable|exists:statuses,id',
            'status_notes' => 'nullable|string|max:255',

            // Items
            'shipment_item_id'   => 'nullable|array',
            'shipment_item_id.*' => 'nullable|exists:shipment_items,id',
            'item_id'            => 'required|array|min:1',
            'item_id.*'          => ['required', function ($attribute, $value, $fail) {
                                        if ($value !== 'other' && !is_numeric($value)) {
                                            $fail('Item tidak valid.');
                                        }
                                    }],
            'new_item_name'      => 'nullable|array',
            'new_item_name.*'    => 'nullable|string|max:255',
            'quantity'           => 'required|array',
            'quantity.*'         => 'required|numeric|min:0.01',
            'uom'                => 'required|array',
            'uom.*'              => 'required|string|max:50',
            'item_notes'         => 'nullable|array',
            'item_notes.*'       => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $itemIds      = $this->input('item_id', []);
            $newItemNames = $this->input('new_item_name', []);

            foreach ($itemIds as $index => $itemId) {
                if ($itemId === 'other') {
                    $name = $newItemNames[$index] ?? null;
                    if (empty(trim($name ?? ''))) {
                        $validator->errors()->add(
                            "new_item_name.{$index}",
                            'Item name wajib diisi jika memilih Other.'
                        );
                    }
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'po'                 => 'Purchase Order',
            'no_invoice'         => 'No Invoice',
            'no_bl'              => 'No B/L',
            'supplier_id'        => 'Supplier',
            'department_id'      => 'Department',
            'etd'                => 'ETD',
            'eta'                => 'ETA',
            'status_id'          => 'Status',
            'status_notes'       => 'Catatan Status',
            'shipment_item_id.*' => 'Shipment Item',
            'item_id.*'          => 'Item',
            'quantity.*'         => 'Quantity',
            'uom.*'              => 'UOM',
        ];
    }

}
