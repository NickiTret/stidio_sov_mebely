<?php

namespace App\Http\Controllers\MainSet;

use App\Models\MainSet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainSetEditController extends Controller
{
    public function __invoke(Request $request)
    {

        $mainset = MainSet::where('id', 1)->first();

        if (empty($mainset)) {
            $request->validate([
                'tel' => 'required',
                'email' => 'required',
                'telegram' => 'required',
                'watsap' => 'required',
                'map' => 'required',
                'footer' => 'required',

            ]);

            $data = $request->all();

            $mainset = MainSet::create($data);
        } else {
                $request->validate([
                    'tel' => 'required',
                    'email' => 'required',
                    'telegram' => 'required',
                    'watsap' => 'required',
                    'map' => 'required',
                    'footer' => 'required',
                ]);
                $mainset = MainSet::first();
                $data = $request->all();
                $mainset->update($data);
        }

        return view('main.mainset', compact('mainset'));
    }
}
