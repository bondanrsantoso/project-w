<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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
            // 'worker_id' => 'unique:worker_experiences,worker_id' . $this->Auth::user()->worker->id . ',id',
            'position' => 'required|string|max:255|unique:worker_experiences,position',
            'organization' => 'required',
            'date_start' => 'required',
            'date_end' => 'sometimes|nullable'
        ];
    }
}
