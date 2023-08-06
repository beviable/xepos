<?php

namespace App\Http\Controllers;

use App\Http\Resources\JsonResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Event;
use App\Events\SendWelcomeMail;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $companies = Company::latest()->paginate(10);
            return response()->json($companies);
        }
        catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            request()->validate([
                'name' => 'required|unique:companies|string|max:255'
            ]);

            $logo_path = '';

            if ($request->hasFile('avatar')) {

                request()->validate([
                    'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100'
                ]);

                $file = $request->file('avatar');
                $name = 'logos/' . uniqid() . '.' . $file->extension();
                $logo_path = $file->storePubliclyAs('public', $name);
            }

            $company = new Company();
            $company->name = $request->name;
            $company->email = $request->email;
            $company->website = $request->website;
            if (!empty($logo_path)){
                $company->logo = Storage::url($logo_path);
            }
            $company->save();

            Event::dispatch(new SendWelcomeMail($company->id));

            return response()->json([
                'status' => true,
                'message'=>'Company Created Successfully!!',
                'company' => $company
            ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        try {
            return response()->json($company);
        }
        catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        try {
            return response()->json($company);
        }
        catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {

            request()->validate([
                'name' => 'required|string|max:255'
            ]);

            if ($request->hasFile('avatar')) {

                request()->validate([
                    'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100'
                ]);

                $file = $request->file('avatar');
                $name = 'logos/' . uniqid() . '.' . $file->extension();
                $logo_path = $file->storePubliclyAs('public', $name);
            }


            $company->name = $request->name;
            $company->email = $request->email;
            $company->website = $request->website;
            if (!empty($logo_path)){
                $company->logo = Storage::url($logo_path);
            }

            $company->save();

            return response()->json([
                'status' => true,
                'message'=>'Company Updated Successfully!!',
                'company'=>$company
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try {

            $company->delete();
            return response()->json([
                'message'=>'Company Deleted Successfully!!'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
