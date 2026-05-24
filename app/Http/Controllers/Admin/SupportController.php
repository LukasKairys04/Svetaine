<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportMessage::query();
        if ($s = $request->input('status')) $query->where('status', $s);
        $messages = $query->latest()->paginate(20)->withQueryString();
        return view('admin.support.index', compact('messages'));
    }

    public function show($id)
    {
        $message = SupportMessage::findOrFail($id);
        if ($message->status === 'new') $message->update(['status' => 'in_progress']);
        return view('admin.support.show', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $message = SupportMessage::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'admin_reply' => 'nullable|string|max:3000',
        ]);
        if ($data['admin_reply'] && !$message->replied_at) $data['replied_at'] = now();
        $message->update($data);
        return back()->with('success', 'Atnaujinta.');
    }

    public function destroy($id)
    {
        SupportMessage::findOrFail($id)->delete();
        return redirect()->route('admin.support.index')->with('success', 'Pašalinta.');
    }
}
