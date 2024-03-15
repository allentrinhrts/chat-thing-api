<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CompanyResourcesController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $results = DB::table('companies')
        ->join('rag_resources', 'companies.id', '=', 'rag_resources.company_id')
        ->where('companies.id', $id)
        ->get();

    return response()->json($results);
    }
}
