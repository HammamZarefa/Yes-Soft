<?php

namespace App\Repositories;

use App\Http\Requests\Banner\BannerRequest;
use App\Models\Banner\Banner;
use App\Models\Banner\BannerTranslation;
use App\Repositories\Interfaces\BannerRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerRepository implements BannerRepositoryInterface{

    private $banner;
    private $bannerTranslation;
    public function __construct(Banner $banner, BannerTranslation $bannerTranslation )
    {
        $this->banner = $banner;
        $this->bannerTranslation = $bannerTranslation;
    }

    public function index()
    {
        return $this->banner::all();
    }

    public function create()
    {

    }

    public function store(BannerRequest $request)
    {
        try {
            /** transformation to collection */
            $allbanners = collect($request->banner)->all();

            $request->is_active ? $is_active = true : $is_active = false;

            $cover = $request->file('cover');
            if($cover){
            $cover_path = $cover->store('images/banner', 'public');
            }

            DB::beginTransaction();
            //create the default language's banner
            $unTransBanner_id = $this->banner->insertGetId([
                'link' => $request['link'],
                'is_active' => $request->is_active = 1,
                'cover' => $cover_path,
            ]);

            //check the Banner and request
            if (isset($allbanners) && count($allbanners)) {
                //insert other traslations for Banners
                foreach ($allbanners as $allbanner) {
                    $transBanner_arr[] = [
                        'title' => $allbanner ['title'],
                        'local' => $allbanner['local'],
                        'desc' => $allbanner['desc'],
                        'banner_id' => $unTransBanner_id
                    ];
                }
                $this->bannerTranslation->insert($transBanner_arr);
            }
            DB::commit();

            return redirect()->route('admin.banner')->with('success', 'Data added successfully');

        } catch (\Exception $ex) {
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.banner.create')->with('error', 'Data failed to create');
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        return $this->banner::findOrFail($id);
    }

    public function update(BannerRequest $request, $id)
    {
        try{

            $banner = $this->banner::findOrFail($id);

            $cover = $request->file('cover');
            if($cover){
                if($request->cover && file_exists(storage_path('app/public/images/' . $request->cover))){
                    Storage::delete('public/'. $request->cover);
                }
                $cover_path = $cover->store('images/banner', 'public');
            }

            DB::beginTransaction();
            // //create the default language's banner
            $unTransBanner_id = $this->banner->where('banners.id', $banner->id)
                ->update([
                    'link' => $request['link'],
                    'is_active' => $request->is_active = 1,
                    'cover' => $cover_path,
            ]);

            $allbanners = array_values($request->banner);
                //insert other translations for Banner
                foreach ($allbanners as $allbanner) {
                    $this->bannerTranslation->where('banner_id', $id)
                    ->where('local', $allbanner['local'])
                    ->update([
                        'title' => $allbanner ['title'],
                        'local' => $allbanner['local'],
                        'desc' => $allbanner['desc'],
                        'banner_id' =>  $banner->id
                    ]);
                }
            DB::commit();
            return redirect()->route('admin.banner')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.banner.edit')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        try {
            $banner = $this->banner::findOrFail($id);

            if ($banner->delete()) {
                if($banner->cover && file_exists(storage_path('app/public/' . $banner->cover))){
                    Storage::delete('public/'. $banner->cover);
                }
            }

            return redirect()->route('admin.banner')->with('success', 'Data deleted successfully');

        } catch (\Exception $ex) {
            return redirect()->route('admin.banner')->with('success', 'Data failed to deleted');
        }

    }
}

