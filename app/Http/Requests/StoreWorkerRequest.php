<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerRequest extends FormRequest
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
            //
            'worker.category_id' => 'nullable|integer',
            'worker.address' => 'sometimes|required|string|max:255',
            'worker.account_number' => 'sometimes|required|string|max:255',
            'user.name' => "sometimes|required|string",
            'user.email' => "sometimes|required|email",
            'user.username' => "sometimes|required|alpha_num",
            'user.phone_number' => "sometimes|required|string",
        ];
    }
}
