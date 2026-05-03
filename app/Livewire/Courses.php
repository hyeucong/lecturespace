<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Courses extends Component
{
    public $courses = [];

    public $courseId;

    public $sortField = 'course_name';

    public $sortDirection = 'asc';

    public $search = '';

    public function mount()
    {
        $this->loadUserCourses();
    }

    public function render()
    {
        return view('livewire.courses');
    }

    #[On('reloadCourses')]
    public function reloadCourses()
    {
        $this->loadUserCourses();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // If already sorting by this field, toggle direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // New sort field, default to ascending
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadUserCourses();
    }

    public function clearSort()
    {
        $this->sortField = 'course_name';
        $this->sortDirection = 'asc';
        $this->loadUserCourses();
    }

    public function updatedSearch()
    {
        $this->loadUserCourses();
    }

    private function loadUserCourses()
    {
        // 1. Define allowed sort fields to prevent SQL injection/errors
        $sortWhitelist = ['course_name', 'course_code', 'date_start', 'status'];
        $sortField = in_array($this->sortField, $sortWhitelist) ? $this->sortField : 'course_name';
        $direction = $this->sortDirection === 'desc' ? 'desc' : 'asc';

        $this->courses = Auth::user()->courses()
            ->select([
                'courses.id', 'courses.course_name', 'courses.course_code',
                'courses.slug', 'courses.lecturer', 'courses.status', 'courses.date_start',
            ])
            ->wherePivotIn('role', ['creator', 'teacher'])
            ->where('status', '!=', 'archived')
            ->withCount('enrollments as student_count')

            // 2. Use when() for cleaner logic
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('course_name', 'like', "%{$search}%")
                        ->orWhere('course_code', 'like', "%{$search}%");
                });
            })

            // 3. Apply the validated sort
            ->orderBy($sortField, $direction)
            ->get();
    }
}
