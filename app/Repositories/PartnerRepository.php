<?php

namespace App\Repositories;

use App\Http\Requests\Partner\PartnerRequest;
use App\Models\Page\Page;
use App\Models\Page\PageTranslation;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerTranslation;
use App\Repositories\Interfaces\PartnerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PartnerRepository implements PartnerRepositoryInterface{

    private $partner;
    private $partnerTranslation;
    private $request;
    public function __construct(Partner $partner, PartnerTranslation $partnerTranslation , Request $request)
    {
        $this->partner = $partner;
        $this->partnerTranslation = $partnerTranslation;
        $this->request = $request;
    }

    public function index()
   {
       $partner = Partner::orderBy('id','desc')->get();
       return view('admin.partner.index',compact('partner'));
   }

   public function create()
   {
       return view('admin.partner.create');
   }

   public function store(PartnerRequest $request)
   {
    try {
        /** transformation to collection */
        $allpartners= collect($request->partner)->all();

        $request->is_active ? $is_active = true : $is_active = false;

        $cover = $request->file('cover');
        if($cover){
        $cover_path = $cover->store('images/partner', 'public');
        // $coverName= 'images/banner'. 'public' . '/' .$cover-> getClientOriginalName();
        }

        DB::beginTransaction();
        //create the default language's banner
        $unTransPartner_id = $this->partner->insertGetId([
            'link' => $request['link'],
            'is_active' => $request->is_active = 1,
            'cover' => $cover_path,
        ]);

        //check the Banner and request
        if (isset($allpartners) && count($allpartners)) {
            //insert other traslations for Banners
            foreach ($allpartners as $allpartner) {
                $transPartner_arr[] = [
                    'name' => $allpartner ['name'],
                    'local' => $allpartner['local'],
                    'partner_id' => $unTransPartner_id
                ];
            }
            $this->partnerTranslation->insert($transPartner_arr);
        }
        DB::commit();

        return redirect()->route('admin.partner')->with('success', 'partner added successfully');

    } catch (\Exception $ex) {
        DB::rollback();
        // return $ex->getMessage();
        return redirect()->route('admin.partner.create')->with('error', 'partner failed to add');
    }
   }

   public function show($id)
   {
       //
   }

   public function edit($id)
   {
       $partner = Partner::findOrFail($id);

       return view('admin.partner.edit',compact('partner'));
   }

   public function update(PartnerRequest $request,$id)
   {
    try{

        $partner = $this->partner::findOrFail($id);

        $new_cover = $request->file('cover');

        if($new_cover){
            if($request->cover && file_exists(storage_path('app/public/' . $request->cover))){
               Storage::delete('public/'. $request->cover);
            }

           $cover_path = $new_cover->store('images/partner', 'public');
        }

        DB::beginTransaction();

        $unTransPartner_id = $partner->where('partners.id',$partner->id)
            ->update([
                'link' =>$request['link'],
                'cover' =>$cover_path,
                'is_active' => $request->is_active = 1
            ]);

            $allpartners = array_values($request->partner);
            // insert other translations for Partner
            foreach ($allpartners as $allpartner){
                $this->partnerTranslation->where('partner_id',$id)
                ->where('local',$allpartner['local'])
                ->update([
                    'name' => $allpartner ['name'],
                    'partner_id' =>  $unTransPartner_id
                ]);
            }
            DB::commit();
            return redirect()->route('admin.partner')->with('success', 'Data added successfully');
    }catch(\Exception $ex){
        DB::rollback();
        // return $ex->getMessage();
        return redirect()->route('admin.partner.create')->with('error', 'Data failed to add');
    }
   }

   public function destroy($id)
   {
       $partner = $this->partner::findOrFail($id);

       if($partner->cover && file_exists(storage_path('app/public/' . $partner->cover))){
        Storage::delete('public/'. $partner->cover);
    }

       $partner->delete();

       return redirect()->route('admin.partner')->with('success', 'Data deleted successfully');
   }
}
