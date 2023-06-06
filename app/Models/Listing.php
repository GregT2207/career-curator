<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

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
        $this->setAverageSalary();
        $this->setAiData();
    }

    public function setAverageSalary(): int
    {
        $start = $this->salaryRange[0];
        $end = $this->salaryRange[1];

        if ($start && $end) {
            return round(($start + $end) / 2);
        }

        if ($start) {return $start;}
        if ($end) {return $end;}

        return 0;
    }
 
    public function setAiData()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_SECRET_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'temperature' => 0,
            'messages' => [
                {
                    'role' => 'system',
                    'content' => '
                        You will be provided with a job description, return JSON only.

                        The JSON object has an integer, the key is "level", the value describes what level of seniority the job is with the potential values as follows:
                        "0" for unknown, "1" for entry-level, "2" for junior, "3" for mid-level, "4" for senior, "5" for expert.

                        The JSON object has an integer, the key is "travel", the value describes the nature of the travel (if any) involved in the job with the potential values as follows:
                        "0" for unknown, "1" if there are no meetups and you can work from anywhere in the world, "2" if there are no meetups and you can work from anywhere in the country the company is in, "3" if there are meetups a few times a year, "4" if you must work on-site monthly, "5" if you must work on-site 1 day a week, "6" if you must work on-site 2 days a week, "7" if you must work on-site 3 days a week, "8" if you must work on-site 4 days a week, "9" if you must work on-site every day.
                    ',
                },
            ],
        ]);
    }
}
