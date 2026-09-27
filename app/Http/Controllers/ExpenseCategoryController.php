<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')->latest()->paginate(15);
        return view('expense-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('expense-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:191|unique:expense_categories,name',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        ExpenseCategory::create($data);
        return redirect()->route('expense-categories.index')->with('success', 'Category created.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense-categories.edit', ['category' => $expenseCategory]);
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:191|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $expenseCategory->update($data);
        return redirect()->route('expense-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->count() > 0) {
            return back()->with('error', 'Cannot delete: category has expenses.');
        }
        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Category deleted.');
    }
}