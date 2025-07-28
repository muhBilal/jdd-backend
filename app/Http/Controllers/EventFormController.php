<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventFormController extends Controller
{
    public function index($id){
        $forms = DB::table('event_forms')->where('event_id', $id)->get();
        return response()->json($forms);
    }
    public function store(Request $request)
    {
        try {
            $forms = $request->all();

            foreach ($forms as $form) {
                $validatedForm = [
                    'event_id' => $form['event_id'],
                    'label' => $form['label'],
                    'datatype' => $form['datatype'],
                    'options' => isset($form['options']) ? '{' . implode(',', $form['options']) . '}' : null
                ];

                DB::table('event_forms')->insert($validatedForm);
            }

            return response()->json([
                'message' => 'Forms created successfully',
                'data' => $forms
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create forms',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
