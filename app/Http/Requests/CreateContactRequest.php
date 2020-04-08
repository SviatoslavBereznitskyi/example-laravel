<?php

namespace App\Http\Requests;

use App\DTO\Response\ErrorResponse;
use App\Models\Phone;
use Illuminate\Validation\Rule;

class CreateContactRequest extends ApiRequest
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
     * @return array
     */
    public function rules()
    {
        return $this->getMainRules() + $this->getPhoneRules();
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = parent::messages();
        $messages['phones.min'] = ErrorResponse::ERROR_FIELD_MIN_ONE_PHONE;
        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function getMainRules()
    {
        return [
            'firstName'        => [
                'required',
                'string',
                'max:255',
            ],
            'middleName'       => [
                'nullable',
                'string',
                'max:255',
            ],
            'lastName'         => [
                'required',
                'string',
                'max:255',
            ],
            'relationId'       => [
                'nullable',
                'integer',
                'exists:relations,id',
            ],
            'email'            => [
                'required',
                'email',
                'max:255',
            ],
            'isEmergencyEmail' => [
                'bool',
            ],
        ];
    }

    public function getPhoneRules()
    {
        return [
            'phones'                => [
                'array',
                'min:1',
            ],
            'phones.*.number'       => [
                'required',
                'string',
            ],
            'phones.*.type'         => [
                'required',
                'string',
                Rule::in(Phone::getAvailableTypes()),
            ],
        ];
    }
}
