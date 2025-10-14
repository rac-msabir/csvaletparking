<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'vehicle_make' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_color' => 'nullable|string|max:50',
            'license_plate' => 'nullable|string|max:20',
            'parking_spot' => 'nullable|string|max:50',
            'parking_zone' => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'damage_notes' => 'nullable|string',
            'check_in_at' => 'nullable|date',
            'check_out_at' => 'nullable|date|after_or_equal:check_in_at',
            'ready_at' => 'nullable|date|after_or_equal:check_in_at',
            'delivered_at' => 'nullable|date|after_or_equal:check_in_at',
            'amount' => 'nullable|numeric|min:0',
            'payment_status' => ['nullable', 'string', Rule::in(['pending', 'paid', 'partially_paid', 'refunded', 'failed'])],
            'payment_method' => ['nullable', 'string', Rule::in(['cash', 'credit_card', 'debit_card', 'mobile_payment', 'other'])],
            'payment_reference' => 'nullable|string|max:255',
            'verification_code' => 'nullable|string|max:20',
            'check_in_latitude' => 'nullable|numeric|between:-90,90',
            'check_in_longitude' => 'nullable|numeric|between:-180,180',
            'check_out_latitude' => 'nullable|numeric|between:-90,90',
            'check_out_longitude' => 'nullable|numeric|between:-180,180',
            'tenant_id' => 'nullable|exists:tenants,id',
            'assigned_to' => 'nullable|exists:users,id',
            'delivered_by' => 'nullable|exists:users,id',
            'created_by' => 'nullable|exists:users,id',
             'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max per file
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $user = auth()->user();
        
        $this->merge([
            'created_by' => $user->id,
            'tenant_id' => $user->tenant_id,
            'status' => $this->status ?? 'pending',
            'amount' => $this->amount ?? 0,
            'check_in_at' => $this->check_in_at ?? now(),
            'assigned_to' => $user->id,
        ]);
    }
}
