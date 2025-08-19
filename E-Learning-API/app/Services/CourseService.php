<?php
namespace App\Services;

use App\Repositories\CourseRepository;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function listCourses()
    {
        return $this->courseRepository->getAll();
    }

    public function createCourse(array $data)
    {
        return $this->courseRepository->create($data);
    }

    public function getCourseDetail($id)
    {
        return $this->courseRepository->findById($id);
    }
}
