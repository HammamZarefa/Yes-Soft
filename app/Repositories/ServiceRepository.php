<?php

namespace App\Repositories;

use App\Http\Requests\Service\ServiceRequest;
use App\Models\Service\Service;
use App\Models\Service\ServiceTranslation;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceRepository implements ServiceRepositoryInterface{

    private $request;
    private $service;
    private $serviceTranslation;
    public function __construct(Service $service, ServiceTranslation $serviceTranslation, Request $request)
    {
        $this->request = $request;
        $this->service = $service;
        $this->serviceTranslation = $serviceTranslation;
    }

    public function index()
    {
        $service = $this->service::orderBy('id','desc')->get();
        return view('admin.service.index',compact('service'));
    }

    public function create()
    {
        return view('admin.service.create');

    }

    public function store(ServiceRequest $request)
    {
        try{
            /** transformation to collection */
            $allservices = collect($request->service)->all();

            $slug= $request->service['en']['title'];

            $icon = $request->file('icon');
            if($icon){
            $icon_path = $icon->store('images/service', 'public');
            // $coverName= 'images/banner'. 'public' . '/' .$cover-> getClientOriginalName();
            }

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's banner
            $unTransService_id = $this->service->insertGetId([
                'slug' => $slug ,
                'icon' => $icon_path,
                'is_active' => $request->is_active = 1
            ]);

            // check the Category and request
            if(isset($allservices) && count($allservices)){
                // insert other translation for Categories
                foreach ($allservices as $allservice){
                    $transService_arr[] = [
                        'title' => $allservice ['title'],
                        'local' => $allservice['local'],
                        'quote' => $allservice['quote'],
                        'desc' => $allservice['desc'],
                        'service_id' => $unTransService_id
                    ];
                }

                $this->serviceTranslation->insert($transService_arr);
            }
            DB::commit();

            return redirect()->route('admin.service')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.service.create')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $service = $this->service::findOrFail($id);

        return view('admin.service.edit',compact('service'));
    }

    public function update(ServiceRequest $request,$id)
    {
        try{
            // return $this->request->all();
            $service = $this->service::findOrFail($id);

            $slug= $request->service['en']['title'];

        $icon = $request->file('icon');
        if($icon){
            if($request->icon && file_exists(storage_path('app/public/' .$request->icon))){
                Storage::delete('public/'. $request->icon);
            }
            $new_cover_path = $icon->store('images/service', 'public');
        }

            DB::beginTransaction();
            // //create the default language's service
            $unTransService_id = $this->service->where('services.id', $service->id)
                ->update([
                    'slug' => $slug,
                    'is_active' => $request->is_active = 1,
                    'icon' => $new_cover_path,
            ]);

            $allservices = array_values($this->request->service);
                //insert other translations for Service
                foreach ($allservices as $allservice) {
                    $this->serviceTranslation->where('service_id', $id)
                    ->where('local', $allservice['local'])
                    ->update([
                        'title' => $allservice ['title'],
                        'local' => $allservice['local'],
                        'quote' => $allservice['quote'],
                        'desc' => $allservice['desc'],
                        'service_id' =>  $service->id
                    ]);
                }
            DB::commit();
            return redirect()->route('admin.service')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.service.create')->with('error', 'Data failed to add');
        }
    }

    public function destroy($id)
    {
         $service = $this->service::findOrFail($id);

        $service->delete();

        return redirect()->route('admin.service')->with('success', 'Data deleted successfully');
    }
}
