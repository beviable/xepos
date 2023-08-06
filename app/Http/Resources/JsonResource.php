<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class JsonResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'avatar_url' => env('APP_URL') . $this->avatar
        ]);
    }
}
