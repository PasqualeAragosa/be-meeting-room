<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user.name' => 'required',
            'user.surname' => 'required',
            'date' => 'required|date|date_format:d-m-Y',
            'timeFrom' => 'required',
            'timeTo' => 'required|after:timeFrom',
            'note' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user.name.required' => 'The name field is required.',
            'user.surname.required' => 'The surname field is required.',
            'date.required' => 'The date field is required.',
            'date.date' => 'The date field must be a valid date.',
            'date.date_format' => 'The date field must be in the format of DD-MM-YYYY.',
            'timeFrom.required' => 'The timeFrom field is required.',
            'timeTo.required' => 'The timeTo field is required.',
            'timeTo.after' => 'The timeTo field must be greater than the timeFrom field.',
            'note.required' => 'The note field is required.',
        ];
    }
}
