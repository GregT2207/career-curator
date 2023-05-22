<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Utilities\Currency;

class ListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $salaryStart = Currency::formatRounded($this->salaryRange[0]);
        $salaryEnd = Currency::formatRounded($this->salaryRange[1]);

        return [
            'site' => $this->site,
            'link' => $this->link,
            'title' => $this->title ? $this->title : 'No title',
            'description' => $this->description ? Str::beforeLast(Str::limit($this->description, 500), ' ') . '...' : 'No description',
            'salary' => $this->salary ? $this->salary : 'Salary not stated',
            'salaryRange' => $this->salary ? "{$salaryStart} - {$salaryEnd}" : 'Salary not stated',
            'level' => $this->level,
            'travel' => $this->travel,
        ];
    }
}