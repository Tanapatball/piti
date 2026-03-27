<?php

namespace App\Http\Controllers;

use App\Models\IssueType;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = IssueType::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('issue_type_id', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        $issueTypes = $query->get();
        return view('issue_types.index', compact('issueTypes'));
    }

    public function create()
    {
        return view('issue_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue_type_id' => 'nullable|integer|min:1|unique:issue_types,issue_type_id',
            'name' => 'required|string|max:100|unique:issue_types,name',
        ], [
            'issue_type_id.unique' => 'รหัสประเภทการเบิกนี้มีอยู่แล้ว',
            'name.unique' => 'ชื่อประเภทการเบิกนี้มีอยู่แล้ว',
        ]);

        $id = $request->issue_type_id ?: (IssueType::max('issue_type_id') ?? 0) + 1;

        IssueType::create([
            'issue_type_id' => $id,
            'name' => $request->name,
        ]);

        return redirect()->route('issue-types.index')
                         ->with('success', 'เพิ่มประเภทการเบิกเรียบร้อยแล้ว');
    }

    public function edit(IssueType $issueType)
    {
        return view('issue_types.edit', compact('issueType'));
    }

    public function update(Request $request, IssueType $issueType)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:issue_types,name,' . $issueType->issue_type_id . ',issue_type_id',
        ], [
            'name.unique' => 'ชื่อประเภทการเบิกนี้มีอยู่แล้ว',
        ]);

        $issueType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('issue-types.index')
                         ->with('success', 'อัปเดตประเภทการเบิกเรียบร้อยแล้ว');
    }

    public function destroy(IssueType $issueType)
    {
        $issueType->delete();

        return redirect()->route('issue-types.index')
                         ->with('success', 'ลบประเภทการเบิกเรียบร้อยแล้ว');
    }
}
