<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    public $site;
    public $link;
    public $title;
    public $description;

    public $salary;
    public $level;
    public $travel; // global, national, home, quarterly, monthly, weekly, 2 days, 3 days, 4 days, 5 days

    private function setSalary(): bool
    {
        
    }
}
