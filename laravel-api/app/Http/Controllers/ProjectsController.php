<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    private array $projects = ['pawat', 'keam', 'lab-laravel', 'project'];

    private function search(string $project, string $search) {
        return str_contains(strtolower($project), strtolower($search));
    }

    public function index(Request $request) {
        $search = $request->query('search');

        $projects = $this->projects;

        if ($search) {
            $projects = array_filter($projects, function ($project) use ($search) {
                return $this->search($project, $search);
            });
        }

        return view('projects', [
            'projects' => $projects,
            'search' => $search,
        ]);
    }

    public function show($title) {
        return view('projects', [
            'title' => $title,
            'projects' => $this->projects,
        ]);
    }
}


