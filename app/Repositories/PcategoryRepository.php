<?php

namespace App\Repositories;

use App\Http\Requests\PCategory\PCategoryRequest;
use App\Models\Pcategory\Pcategory;
use App\Models\Pcategory\PcategoryTranslation;
use App\Repositories\Interfaces\PcategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PcategoryRepository implements PcategoryRepositoryInterface{

    private $request;
    private $pcategory;
    private $pcategoryTranslation;
    public function __construct(Pcategory $pcategory, PcategoryTranslation $pcategoryTranslation, Request $request)
    {
        $this->request = $request;
        $this->pcategory = $pcategory;
        $this->pcategoryTranslation = $pcategoryTranslation;
    }

    public function index()
    {
        $pcategory = $this->pcategory::orderBy('id','desc')->get();

        return view('admin.pcategory.index',compact('pcategory'));
    }

    public function store(PCategoryRequest $request)
    {
        try{
            /** transformation to collection */
            $allpcategories = collect($request->pcategory)->all();

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's banner
            $unTransPcategory_id = $this->pcategory->insertGetId([
                'is_active' => $request->is_active = 1
            ]);

            // check the Category and request
            if(isset($allpcategories) && count($allpcategories)){
                // insert other translation for Categories
                foreach ($allpcategories as $allpcategory){
                    $transPcategory_arr[] = [
                        'name' => $allpcategory ['name'],
                        'local' => $allpcategory['local'],
                        'pcategory_id' => $unTransPcategory_id
                    ];
                }

                $this->pcategoryTranslation->insert($transPcategory_arr);
            }
            DB::commit();

            return redirect()->route('admin.pcategory')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.pcategory')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $pcategory = $this->pcategory::findOrFail($id);

        return view('admin.pcategory.edit',compact('pcategory'));
    }

    public function update(PCategoryRequest $request,$id)
    {
        try{

            $pcategory = $this->pcategory::findOrFail($id);

            DB::beginTransaction();

            $unTransPcategory_id = $pcategory->where('pcategories.id',$pcategory->id)
                ->update([
                    'is_active' => $request->is_active = 1
                ]);

                $allpcategories = array_values($request->pcategory);
                // insert other translations for Pcategory
                foreach ($allpcategories as $allpcategory){
                    $this->pcategoryTranslation->where('pcategory_id',$id)
                    ->where('local',$allpcategory['local'])
                    ->update([
                        'name' => $allpcategory ['name'],
                        'pcategory_id' =>  $unTransPcategory_id
                    ]);
                }
                DB::commit();
                return redirect()->route('admin.pcategory')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.pcategory.create')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        $pcategory = $this->pcategory::findOrFail($id);
        $pcategory->delete();

        return redirect()->route('admin.pcategory')->with('success', 'Data deleted successfully');
    }
}
