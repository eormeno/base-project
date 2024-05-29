<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequestFilter extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function eventInfo() : array
    {
        $validated = $this->all();
        $event_name = $validated['event'] ?? null;
        unset($validated['event']);
        unset($validated['_token']);
        unset($validated['_method']);
        unset($validated['submit']);
        $event = [
            'event' => $event_name,
            'data' => $validated['data']
        ];
        return $event;
    }
}
