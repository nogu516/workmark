<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
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
            'attendance_id' => 'required|exists:attendances,id',
            'clock_in' => ['nullable', 'date_format:H:i'],
            'clock_out' => ['nullable', 'date_format:H:i', 'after_or_equal:clock_in'],
            'break_start' => ['nullable', 'date_format:H:i'],
            'break_end' => ['nullable', 'date_format:H:i', 'after:break_start'],
            'break2_start' => ['nullable', 'date_format:H:i'],
            'break2_end' => ['nullable', 'date_format:H:i', 'after:break2_start'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.date_format' => '出勤時間が不適切な値です。',
            'clock_out.date_format' => '退勤時間が不適切な値です。',
            'clock_out.after_or_equal' => '出勤時間もしくは退勤時間が不適切な値です。',
            'break_start.date_format' => '休憩時間が不適切な値です。',
            'break_end.date_format' => '休憩時間が不適切な値です。',
            'break_end.after' => '休憩時間が不適切な値です。',
            'break2_start.date_format' => '休憩時間が不適切な値です。',
            'break2_end.date_format' => '休憩時間が不適切な値です。',
            'break2_end.after' => '休憩時間が不適切な値です。',
        ];
    }
}
