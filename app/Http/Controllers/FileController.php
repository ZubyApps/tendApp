<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorefileRequest;
use App\Http\Requests\UpdatefileRequest;
use App\Models\File;
use App\Models\User;

class FileController extends Controller
{
    public function __construct(private readonly File $file)
    {
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorefileRequest $request)
    {
        $this->file->createFile($request, $request->user());
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatefileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }
}
