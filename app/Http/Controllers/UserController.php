<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function import(Request $request)
    {
      
           ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 1800);
        Log::info('وصلنا إلى راوت import-excel');
    
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
    
        Log::info('وصلنا إلى راوت import-excel');
    
        Excel::import(new UsersImport, $request->file('file'));
    
        Log::info('وصلنا إلى راوت import-excel');
    
        return back()->with('success', 'تم رفع الملف بنجاح!');
    }
    
    

    // public function export(){
    //     return Excel::download(new UsersExport(),'export.xlsx');
    // }
}