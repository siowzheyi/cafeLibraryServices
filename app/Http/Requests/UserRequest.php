<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $request = Request();
        $request_method = Request::method();
        $request_path = $request->path();
       

        if ($request_method == "POST") {
    
            return [
                'name' => ['required'],
                'phone_no' => ['required','unique:users,phone_no'],
                'email' => ['required','unique:users,email'],
                'password' => ['required'],
                'library_id'    =>  ['nullable','exists:libraries,id'],
                'cafe_id'    =>  ['nullable','exists:cafes,id'],
            ];
           
        } elseif ($request_method === "PATCH") {
            return [
                'type' => array('required','in:status'),
            ];
        } elseif ($request_method == "PUT") {
            return [
                'email' => array('required','unique:users,email,'.$this->user->id.',id,deleted_at,NULL'),
                // 'status'   =>  array('required','in:1,0'),
                'name' => ['required'],
                'phone_no' => ['required','unique:users,phone_no,'.$this->user->id.',id,deleted_at,NULL']
            ];
        }
    }

     /**
     * When we fail validation, override our default error.
     *
     * @param ValidatorContract $validator
    */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $this->validator->errors();
        // $base_controller = new BaseController;
        // return $base_controller->sendHTMLCustomValidationError($errors);

        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'status' => 'fail',
                'errors' => $errors,
                'message' => 'The given data was invalid.',
        ], 422)
        );
    }

    public function messages()
    {
        return [
            // 'invitation_code.exists' => '邀请码错误',
            // 'invitation_code.exists' => 'Referral code is not exist',
            // 'verification_code.required' => 'Verification code is required',
            // 'phone_number.min' => '电话号码格式错误，至少11个数字',
            // 'password.min' => __('general.incorrect_format', ['attribute'=>__('general.password'),'format'=>__('general.password_format')]),
            // 'password.max' => __(
            //     'general.incorrect_format',
            //     ['attribute'=>__('general.password'),'format'=>__('general.password_format')]
            // ),
            // 'password.regex' => __(
            //     'general.incorrect_format',
            //     ['attribute'=>__('general.password'),'format'=>__('general.password_format')]
            // )
        ];
    }
}