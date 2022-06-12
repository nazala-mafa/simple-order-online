<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    function index() {
        return view('admin.setting.index', [
            'title' => 'Setting',
            'xenditApiKey' => Option::where('key', 'xendit_api_key')->pluck('value')->first() ?? ''
        ]);
    }

    function save(Request $request) {
        $request->validate([
            'xenditApiKey' => ['string']
        ]);

        Option::updateOrCreate(['key' => 'xendit_api_key'], [
            'key' => 'xendit_api_key',
            'value' => $request->xenditApiKey
        ]);

        return redirect()->back()->with('message', 'Setting Change Saved');
    }
}
