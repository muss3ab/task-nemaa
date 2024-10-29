<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TransactionStatus;

class UserFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => 'sometimes|string|in:DataProviderX,DataProviderY',
            'statusCode' => 'sometimes|string|in:authorised,decline,refunded',
            'balanceMin' => 'sometimes|numeric|min:0',
            'balanceMax' => 'sometimes|numeric|gt:balanceMin',
            'currency' => 'sometimes|string|size:3'
        ];
    }
}
