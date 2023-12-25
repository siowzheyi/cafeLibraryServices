<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;

use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user authorized to make this request.
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
        // dd()

        if ($request_method == "POST") {
    
            return [
                'type'  =>  ['required','in:room,equipment,book'],
                'room_id' => ['required_if:type,room','exists:rooms,id'],
                'equipment_id' => ['required_if:type,equipment','exists:equipments,id'],
                'book_id' => ['required_if:type,book','exists:books,id'],

                'quantity' => ['required'],
                'start_booked_at' => ['required'],
                'end_booked_at' => ['required'],

            ];
           
        } elseif ($request_method === "PATCH") {
            return [
                'type' => array('required','in:status,return'),
                'action'    =>  array('required_if:type,status','in:approved,rejected')
            ];
        } elseif ($request_method == "PUT") {
            return [
                // 'title' => array('required'),
                // 'status'   =>  array('required','in:1,0'),
                // 'content' => ['required'],
                // 'expired_at' => ['nullable'],
                // 'picture'   =>  ['required'],

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
        // dd($errors);
        // return $this->sendError('Validation Error.', $validator->errors());

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
