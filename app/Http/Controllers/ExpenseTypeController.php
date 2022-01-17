<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $expenseTypes = $request->user('user')->expenseTypes()->paginate(10);
        return response()->view('cms.expense-types.index', ['expenseTypes' => $expenseTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('cms.expense-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator($request->all(), [
            'name' => 'required|string|min:3|max:10',
            'details' => 'nullable|string|min:3|max:100',
            'active' => 'required|boolean'
        ]);

        if (!$validator->fails()) {
            $expenseType = new ExpenseType();
            $expenseType->name = $request->get('name');
            $expenseType->details = $request->get('details');
            $expenseType->active = $request->get('active');
            $expenseType->user_id = $request->user('user')->id;
            $isSaved = $expenseType->save();
            return response()->json(['message' => $isSaved ? 'Expense type created successfully' : 'Failed to create expense type!'], $isSaved ? 201 : 400);
        } else {
            return response()->json(['message' => $validator->getMessageBag()->first()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $expenseType = ExpenseType::findOrFail($id);
        return response()->view('cms.expense-types.edit', ['expenseType' => $expenseType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator($request->all(), [
            'name' => 'required|string|min:3|max:10',
            'details' => 'nullable|string|min:3|max:100',
            'active' => 'required|boolean'
        ]);

        if (!$validator->fails()) {
            $expenseType = ExpenseType::findOrFail($id);
            $expenseType->name = $request->get('name');
            $expenseType->details = $request->get('details');
            $expenseType->active = $request->get('active');
            $isSaved = $expenseType->save();
            return response()->json(['message' => $isSaved ? 'Expense type updated successfully' : 'Failed to update expense type!'], $isSaved ? 201 : 400);
        } else {
            return response()->json(['message' => $validator->getMessageBag()->first()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = ExpenseType::destroy($id);
        if ($isDeleted) {
            // return redirect()->back();
            return response()->json(['title' => 'Deleted!', 'message' => 'Expense type Deleted Successfully', 'icon' => 'success'], 200);
        } else {
            return response()->json(['title' => 'Failed!', 'message' => 'Delete expense type failed', 'icon' => 'error'], 400);
        }
    }
}
