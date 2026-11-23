<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStorInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $data = [];

        // تحويل المفاتيح من camelCase إلى snake_case قبل الـ Validation
        foreach ($this->all() as $item) {
            if (is_array($item)) {
                $item['customer_id'] = $item['customerId'] ?? $item['customerID'] ?? null;
                $item['billed_date'] = $item['billedDate'] ?? $item['billeDate'] ?? null;
                $item['paid_date']   = $item['paidDate'] ?? null;

                // إزالة المفاتيح القديمة لتنظيف البيانات
                unset($item['customerId'], $item['customerID'], $item['billedDate'], $item['billeDate'], $item['paidDate']);

                $data[] = $item;
            }
        }

        // الدمج يتم مرة واحدة فقط بعد انتهاء الـ Loop
        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.customer_id' => ['required', 'integer'],
            '*.amount'      => ['required', 'numeric'],
            '*.status'      => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.billed_date' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paid_date'   => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
