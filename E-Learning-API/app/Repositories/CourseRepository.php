<?php
namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    public function getAll()
    {
        return Course::all();
    }

    public function findById($id)
    {
        return Course::findOrFail($id);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }
}
