<?php

namespace App\Http\Controllers\Backend;
use App\Models\skill;
use App\Http\Requests\StoreSkillReaquest;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillRequest;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(){
        $skills= skill::all();

        return view('skills.index',compact('skills'));


    }
    public function create()
    {
        return view('skills.create');  
    }
    public function store(StoreSkillRequest $request)
    {
            if($request->hasFile('image')){
                $image =$request->file('image')->store('skills');
                skill::create([
                    'name'=>$request->name,
                    'image'=>$image
                ]);
                return to_route('skills.index')->with('success', 'Skill created.');
            }
            return back();
    }
    public function edit(skill $skill)
    {
        return view('skills.edit' ,compact('skill'));

    }
    public function update (Request $request, skill $skill)
    {
        $request->validate([
            'name'=>['required','min:3'],
            'image'=>['nullable','image']  ]);

        $image=$skill->image;
        if($request->hasFile('image')){
            Storage::delete($skill->image);
            $image =$request->file('image')->store('skills');

            
        }
        $skill->update(
            [
                'name'=>$request->name,
                'image'=>$image

            ]
            );
        return to_route('skills.index')->with('success', 'Skill updated.');


    }

    public function destroy (skill $skill)
    {
        Storage::delete($skill->image);
        $skill->delete();

        return back()->with('danger', 'Skill deleted.');



    }
}
