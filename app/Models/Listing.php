<?php

namespace App\Models;

class Listing
{
    public $salary;
    public $level;
    public $travel; // global, national, home, quarterly, monthly, weekly, 2 days, 3 days, 4 days, 5 days

    public function __construct(
        public $site, 
        public $link, 
        public $title, 
        public $description, 
        public $salaryRange,
    ) {
        $this->salary = $this->getAverageSalary();
        $this->level = $this->getLevel();
        $this->travel = $this->getTravel();
    }

    public function getAverageSalary(): int
    {
        $start = $this->salaryRange[0];
        $end = $this->salaryRange[1];

        if ($start == 0) {
            return 0;
        }

        if ($end == 0) {
            return $start;
        }

        return round(($start + $end) / 2);
    }

    public function getLevel(): string
    {
        return '';
    }

    public function getTravel(): int
    {
        return 0;
    }
}
