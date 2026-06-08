<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'uom' => trim($this->uom),
            'other_uom' => trim($this->other_uom),
            'description' => trim($this->description),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('item');
        return [
            'code'          => 'sometimes|string|max:255|unique:items,code,' . $id,
            'name'          => 'sometimes|string|max:255',
            'uom'           => 'sometimes|string|max:255',
            'other_uom'     => 'required_if:uom,other|string|max:255',
            'description'   => 'nullable|string',
        ];
    }
}
