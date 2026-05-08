<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDormListingRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->user_type === 'owner';
    }

    public function rules()
    {
        return [
            'street' => 'required|string|max:255',
            'complete_address' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|in:Room,Bedspace,Unit',
            'gender_policy' => 'required|in:Any,Female,Male',
            'walk_minutes' => 'required|integer|min:1|max:60',
            'bathroom' => 'required|in:Private,Shared',
            'furnishings' => 'nullable|array',
            'furnishings.*' => 'string|max:100',
            'appliances' => 'nullable|array',
            'appliances.*' => 'string|max:100',
            'bills_included' => 'nullable|array',
            'bills_included.*' => 'string|max:100',
            'curfew' => 'required|in:No curfew,10 PM,11 PM,12 AM',
            'wifi_included' => 'boolean',
            'pets_allowed' => 'boolean',
            'nearby_landmarks' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'photos' => 'array|max:6',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'type.in' => 'Type must be Room, Bedspace, or Unit.',
            'curfew.in' => 'Curfew must be one of: No curfew, 10 PM, 11 PM, or 12 AM.',
            'gender_policy.in' => 'Gender policy must be Any, Female, or Male.',
            'bathroom.in' => 'Bathroom type must be Private or Shared.',
        ];
    }
}
