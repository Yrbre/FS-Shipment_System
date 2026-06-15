<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        // Shipment
        'po'                => 'required|string|max:100|unique:shipments,po',
        'no_invoice'        => 'required|string|max:100|unique:shipments,no_invoice',
        'no_bl'             => 'required|string|max:100|unique:shipments,no_bl',
        'supplier_id'       => 'required|string',  // ← tidak pakai exists karena bisa 'other'
        'new_supplier_name' => 'nullable|string|max:255',
        'department_id'     => 'required|exists:departments,id',
        'etd'               => 'required|date',
        'eta'               => 'required|date|after_or_equal:etd',
        'notes'             => 'nullable|string',

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

        // ── Validasi supplier ─────────────────────────────────────
        $supplierId = $this->input('supplier_id');

        if ($supplierId === 'other') {
            // Jika other, new_supplier_name wajib diisi
            if (empty(trim($this->input('new_supplier_name', '')))) {
                $validator->errors()->add(
                    'new_supplier_name',
                    'Nama supplier baru wajib diisi.'
                );
            }
        } elseif (!empty($supplierId)) {
            // Jika bukan other, pastikan ID valid di database
            if (!\App\Models\Supplier::where('id', $supplierId)->exists()) {
                $validator->errors()->add('supplier_id', 'Supplier tidak valid.');
            }
        }

        // ── Validasi items ────────────────────────────────────────
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
        'po'                => 'Purchase Order',
        'no_invoice'        => 'No Invoice',
        'no_bl'             => 'No B/L',
        'supplier_id'       => 'Supplier',
        'new_supplier_name' => 'Nama Supplier Baru',
        'department_id'     => 'Department',
        'etd'               => 'ETD',
        'eta'               => 'ETA',
        'rf.*'              => 'Item',
        'item_name.*'       => 'Nama Item',
        'hscode.*'          => 'HS Code',
        'quantity.*'        => 'Quantity',
        'uom.*'             => 'UOM',
    ];
}
}
