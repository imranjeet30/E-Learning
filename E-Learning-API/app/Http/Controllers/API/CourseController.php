<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    // GET /api/courses
    public function index()
    {
        return response()->json($this->courseService->listCourses());
    }

    // GET /api/courses/{id}
    public function show($id)
    {
        return response()->json($this->courseService->getCourseDetail($id));
    }

    // POST /api/courses
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric'
        ]);

        $course = $this->courseService->createCourse($data);

        return response()->json($course, 201);
    }
}
