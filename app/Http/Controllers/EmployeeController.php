<?php

namespace App\Http\Controllers;

use App\Http\Resources\JsonResource;
use App\Models\Employee;
use Illuminate\Http\Request;


class EmployeeController extends Controller
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
            $employees = Employee::with('Company')->paginate(10);
            return response()->json($employees);
        }
        catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // return view('products.create');
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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'company_id' => 'required|integer'
            ]);

            $employee = new Employee();
            $employee->first_name   = $request->first_name;
            $employee->last_name    = $request->last_name;
            $employee->email        = $request->email;
            $employee->phone        = $request->phone;
            $employee->company_id   = $request->company_id;
            $employee->save();

            return response()->json([
                'status' => true,
                'message'=>'Employee Created Successfully!!'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        try {
            return response()->json($employee);
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
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        try {
            return response()->json($employee);
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
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        request()->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_id' => 'required|integer'
        ]);

        $employee->id           = $request->id;
        $employee->first_name   = $request->first_name;
        $employee->last_name    = $request->last_name;
        $employee->email        = $request->email;
        $employee->phone        = $request->phone;
        $employee->company_id   = $request->company_id;
        $employee->save();


        return response()->json([
            'status' => true,
            'message'=>'Employee Updated Successfully!!',
            'employee'=>$employee
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        try {

            $employee->delete();

            return response()->json([
                'status' => true,
                'message' => 'Employee Deleted Successfully!!'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
