<?php

namespace App\Http\Requests\Slider;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $group = Group::where('id', $this->group_id)->get();

        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'image_src' => 'nullable|image',
            'group_id' => $group,
        ];
    }
}
