<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $shipmentId = $this->route('shipment'); // ambil ID dari route

        return [
            // Shipment — unique ignore current record
            'po'            => "required|string|max:100|unique:shipments,po,{$shipmentId}",
            'no_invoice'    => "required|string|max:100|unique:shipments,no_invoice,{$shipmentId}",
            'no_bl'         => "required|string|max:100|unique:shipments,no_bl,{$shipmentId}",
            'supplier_id'   => 'required|exists:suppliers,id',
            'department_id' => 'required|exists:departments,id',
            'etd'           => 'required|date',
            'eta'           => 'required|date|after_or_equal:etd',
            'notes'         => 'nullable|string',

            // Status
            'status_id'    => 'nullable|exists:statuses,id',
            'status_notes' => 'nullable|string|max:255',

            // Items
            'rf'              => 'required|array|min:1',
            'rf.*'            => 'nullable|string|max:100',
            'item_name'       => 'required|array|min:1',
            'item_name.*'     => 'nullable|string|max:255',
            'hscode'          => 'nullable|array',
            'hscode.*'        => 'nullable|string|max:50',
            'new_item_name'   => 'nullable|array',
            'new_item_name.*' => 'nullable|string|max:255',
            'quantity'        => 'required|array',
            'quantity.*'      => 'required|numeric|min:0.01',
            'uom'             => 'required|array',
            'uom.*'           => 'required|string|max:50',
            'item_notes'      => 'nullable|array',
            'item_notes.*'    => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $rfs          = $this->input('rf', []);
            $newItemNames = $this->input('new_item_name', []);

            foreach ($rfs as $index => $rf) {
                if (empty($rf)) {
                    $name = $newItemNames[$index] ?? null;
                    if (empty(trim($name ?? ''))) {
                        $validator->errors()->add(
                            "new_item_name.{$index}",
                            'Item name wajib diisi jika memilih Other.'
                        );
                    }
                }
            }

            // Pastikan minimal ada 1 item valid
            $hasValidItem = collect($rfs)->contains(fn($rf) => !empty($rf))
                || collect($newItemNames)->contains(fn($name) => !empty(trim($name ?? '')));

            if (!$hasValidItem) {
                $validator->errors()->add('rf', 'Minimal satu item harus diisi.');
            }
        });
    }

    public function attributes(): array
    {
        return [
            'po'            => 'Purchase Order',
            'no_invoice'    => 'No Invoice',
            'no_bl'         => 'No B/L',
            'supplier_id'   => 'Supplier',
            'department_id' => 'Department',
            'etd'           => 'ETD',
            'eta'           => 'ETA',
            'status_id'     => 'Status',
            'status_notes'  => 'Catatan Status',
            'rf.*'          => 'Item',
            'item_name.*'   => 'Nama Item',
            'hscode.*'      => 'HS Code',
            'quantity.*'    => 'Quantity',
            'uom.*'         => 'UOM',
        ];
    }
}
