<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabsController extends Controller
{
    public function index(Request $request)
    {
        $page = (int) $request->query('p', 1);
        $pageSize = (int) $request->query('pageSize', 5);

        $labs = array_map(
            fn ($i) => "Lab {$i}",
            range(1, 30)
        );

        $total = count($labs);
        $totalPages = ceil($total / $pageSize);

        $offset = ($page - 1) * $pageSize;
        $pageLabs = array_slice($labs, $offset, $pageSize);

        return view('labs', [
            'labs' => $pageLabs,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPages' => $totalPages,
            'query' => $request->query(),
        ]);
    }
}
