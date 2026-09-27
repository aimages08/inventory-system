<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'user']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }
        if ($cat = $request->input('category_id')) $query->where('expense_category_id', $cat);
        if ($from = $request->input('from'))       $query->whereDate('expense_date', '>=', $from);
        if ($to   = $request->input('to'))         $query->whereDate('expense_date', '<=', $to);

        $expenses   = $query->latest('expense_date')->latest('id')->paginate(20)->withQueryString();
        $categories = ExpenseCategory::orderBy('name')->get();
        $total      = $query->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'total'));
    }

    public function create()
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'               => 'required|string|max:191',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0.01',
            'expense_date'        => 'required|date',
            'payment_method'      => 'required|in:cash,bank,card,online',
            'reference'           => 'nullable|string|max:100',
            'notes'               => 'nullable|string',
        ]);

        $data['reference_no'] = Expense::generateReference();
        $data['user_id']      = auth()->id();

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded.');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'title'               => 'required|string|max:191',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0.01',
            'expense_date'        => 'required|date',
            'payment_method'      => 'required|in:cash,bank,card,online',
            'reference'           => 'nullable|string|max:100',
            'notes'               => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}