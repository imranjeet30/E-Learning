<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Laravel for Beginners',
                'description' => 'Learn Laravel step by step with real-world examples.',
                'price' => 1999,
            ],
            [
                'title' => 'Vue.js Mastery',
                'description' => 'Become a Vue.js expert with hands-on projects.',
                'price' => 1499,
                'instructor_id' => 2,
            ],
            [
                'title' => 'Node.js Fullstack Development',
                'description' => 'Learn backend and frontend with Node.js.',
                'price' => 2499,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
