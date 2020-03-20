<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OrderFormSave - работа с запросом по сохранению формы с заказом
 * @package App\Http\Requests
 */
class OrderFormSave extends FormRequest
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
     * Backend validation
     * @return array
     */
    public function rules()
    {
        return [
            'client_email' => 'required',
            'partner_id' => 'required',
            'status' => 'required'
        ];
    }
}
