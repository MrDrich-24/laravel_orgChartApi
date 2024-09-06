<?php

namespace App\Http\Controllers;

use App\Models\EmployeePosition;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = EmployeePosition::query();

            // Search functionality
            if ($request->has('search')) {
                $query->where('position', 'like', '%' . $request->search . '%');
            }

            // Sorting functionality alphabetically and vice versa
            $sort = $request->query('sort', 'asc'); // default value
            
            if ($sort !== 'asc' && $sort !== 'desc') {
                return response()->json(['error' => 'Invalid sort parameter. Use "asc" or "desc".'], 400);
            }
            
            $positions = $query->orderBy('position', $sort)->get();

            return response()->json($positions);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'position' => 'required|unique:employee_positions,position',
                'reports_to' => 'nullable|exists:employee_positions,id',
            ]);

            // ensure that only 1 position has a null reports_to
            if (is_null($validated['reports_to'])) {
                $existingNullReportsTo = EmployeePosition::whereNull('reports_to')->first();

                if ($existingNullReportsTo) {
                    return response()->json([
                        'error' => 'Validation failed. Only one position can have null value in Reports To.',
                    ], 422);
                }
            }

            $position = EmployeePosition::create($validated);

            return response()->json($position, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed. Invalid input.',
                'messages' => $e->errors()
            ], 422);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $position = EmployeePosition::findOrFail($id);
            return response()->json($position);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $position = EmployeePosition::findOrFail($id);

            $validated = $request->validate([
                'position' => 'required|unique:employee_positions,position,' . $id,
                'reports_to' => 'nullable|exists:employee_positions,id',
            ]);

            // ensure that only 1 position has a null value in reports_to
            if (is_null($validated['reports_to'])) {
                $existingNullReportsTo = EmployeePosition::whereNull('reports_to')->where('id', '!=', $id)->first();

                if ($existingNullReportsTo) {
                    return response()->json([
                        'error' => 'Validation failed. Only one position can have null value in Reports To.',
                    ], 422);
                }
            }
            // prevent a position to report to itself
            if ($id == $validated['reports_to']) {
                return response()->json([
                    'error' => 'Validation failed. A position cannot report to itself.',
                ], 422);
            }

            $position->update($validated);

            return response()->json($position);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } 
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed. Invalid input.',
                'messages' => $e->errors()
            ], 422);
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $position = EmployeePosition::findOrFail($id);
            $position->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } 
    }
}
