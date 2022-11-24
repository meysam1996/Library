<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Index simple get json list of Subjects
     * @return Json
     *
     */
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    /**
     * Create new object of Subject
     * @param StoreSubjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSubjectRequest $request)
    {
        $subject = new Subject();
        $subject->name = $request->input("name");
        $subject->save();
        return response()->json($subject);
    }

    /**
     * Get a subject
     * @param Subject $subject
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Subject $subject)
    {
        return response()->json($subject);
    }

    /**
     * Edit a Subject
     * @param StoreSubjectRequest $request
     * @param Subject $subject
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreSubjectRequest $request, Subject $subject)
    {
        $subject->name = $request->input("name");
        $subject->save();
        return response()->json($subject);
    }

    /**
     * Delete a Subject
     * @param Subject $subject
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Subject $subject)
    {
        if (!Auth::user()->isAdmin()) {
            abort(404);
        }
        $subject->delete();
        return redirect()->route("subjects.index");
    }
}
