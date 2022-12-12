<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\RemoveStudentRequest;
use App\Http\Requests\Students\RetrieveStudentsRequest;
use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentsController extends Controller
{


    public function store(StoreStudentRequest $request)
    {
        $studentData = $request->all(
            'name',
            'university_id',
            'level',
            'department',
            'batch',
            'is_active',
            'fees_id',
            'installments_id',
            'reductions_id',
        );
        $student = Student::create($studentData);
        return $this->sendData($student);
    }

    public function index(RetrieveStudentsRequest $request)
    {
        return $this->sendData(Student::all());
    }

    public function show(RetrieveStudentsRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }
        return $this->sendData($student);
    }

    public function update(UpdateStudentRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }

        $student->update($request->all());
        return $this->sendData($student);
    }

    public function destroy(RemoveStudentRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }

        $student->delete();
        return $this->sendData($student);
    }

}
