<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class AdminProfileController extends Controller
{
    public function edit(): View { return view('admin.profile', ['admin' => auth()->user()]); }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required','string','max:120'], 'email' => ['required','email','max:190','unique:users,email,'.auth()->id()]]);
        auth()->user()->update($data);
        ActivityLog::create(['user_id'=>auth()->id(),'action'=>'Updated profile','description'=>'Updated administrator profile details.','application'=>'Platform','ip_address'=>$request->ip()]);
        return back()->with('success','Profile updated successfully.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validate(['current_password'=>['required'], 'password'=>['required','string','min:8','confirmed']]);
        if (!Hash::check($data['current_password'], auth()->user()->password)) return back()->with('error','Current password is incorrect.');
        auth()->user()->update(['password'=>Hash::make($data['password'])]);
        ActivityLog::create(['user_id'=>auth()->id(),'action'=>'Changed password','description'=>'Changed the administrator account password.','application'=>'Platform','ip_address'=>$request->ip()]);
        return back()->with('success','Password changed successfully.');
    }
}
