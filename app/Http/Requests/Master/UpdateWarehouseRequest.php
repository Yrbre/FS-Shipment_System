<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim($this->code)),
            'name' => trim($this->name),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'code' => 'required|string|max:100|unique:warehouses,code,' . $this->route('warehouse')->id,
                'name' => 'required|string|max:255',
        ];
    }
}
