<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentController extends Controller
{
    public function index(Request $request): View
    {
        $parents = ParentModel::with('students')->withCount('students')
            ->when($request->q, fn ($q, $v) => $q->where(function ($qq) use ($v) {
                $qq->where('name', 'like', "%{$v}%")->orWhere('national_id', 'like', "%{$v}%")->orWhere('phone', 'like', "%{$v}%");
            }))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.parents.index', compact('parents'));
    }

    public function create(): View
    {
        return view('admin.parents.form', ['parent' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        ParentModel::create($this->validated($request));

        return redirect()->route('admin.parents.index')->with('success', 'تم إضافة ولي الأمر.');
    }

    public function edit(ParentModel $parent): View
    {
        return view('admin.parents.form', ['parent' => $parent]);
    }

    public function update(Request $request, ParentModel $parent): RedirectResponse
    {
        $parent->update($this->validated($request));

        return redirect()->route('admin.parents.index')->with('success', 'تم تحديث بيانات ولي الأمر.');
    }

    public function destroy(ParentModel $parent): RedirectResponse
    {
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'تم حذف ولي الأمر.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'mobile' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'national_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'relation' => 'nullable|string|max:30',
            'active' => 'nullable|boolean',
        ]);
    }
}
