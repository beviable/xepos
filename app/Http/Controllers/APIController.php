<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class APIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getCompanies()
    {
        $companies = Company::get(['id', 'name']);

        return response()->json([
            'status' => true,
            'companies' => $companies
        ]);
    }
}
