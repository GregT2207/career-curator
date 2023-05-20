<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'salary' => $this->salary ? $this->salary : 'Salary not stated',
            'level' => $this->level,
            'travel' => $this->travel,
            'site' => $this->site,
            'link' => $this->link,
            'title' => $this->title ? $this->title : 'No title',
            'description' => $this->description ? $this->description : 'No description',
            'salaryRange' => $this->salary ? $this->salaryRange : 'Salary not stated',
        ];
    }
}