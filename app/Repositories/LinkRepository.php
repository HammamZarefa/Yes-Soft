<?php

namespace App\Repositories;

use App\Http\Requests\Link\LinkRequest;
use App\Models\Link\Link;
use App\Models\Link\LinkTranslation;
use App\Repositories\Interfaces\LinkRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinkRepository implements LinkRepositoryInterface{

    private $link;
    private $request;
    private $linkTranslation;
    public function __construct(Link $link, LinkTranslation $linkTranslation, Request $request)
    {
        $this->link = $link;
        $this->request = $request;
        $this->linkTranslation = $linkTranslation;
    }

    public function index()
    {
        $link = $this->link::orderBy('id','desc')->get();
        return view('admin.link.index',compact('link'));
    }

    public function create()
    {
        return view('admin.link.create');
    }

    public function store(LinkRequest $request)
    {
        try{
            /** transformation to collection */
            $alllinks = collect($request->links)->all();

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's Link
            $unTransLink_id = $this->link->insertGetId([
                'link' => $request['link'],
                'is_active' => $request->is_active = 1
            ]);

            // check the Link and request
            if(isset($alllinks) && count($alllinks)){
                // insert other translation for Links
                foreach ($alllinks as $alllink){
                    $transLink_arr[] = [
                        'name' => $alllink ['name'],
                        'local' => $alllink['local'],
                        'link_id' => $unTransLink_id
                    ];
                }

            $this->linkTranslation->insert($transLink_arr);
            }
            DB::commit();

            return redirect()->route('admin.link')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.link.create')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $link = $this->link::findOrFail($id);
        return view('admin.link.edit',compact('link'));
    }

    public function update(LinkRequest $request,$id)
    {
        try{
            $link = $this->link::findOrFail($id);

            DB::beginTransaction();

            $unTransLink_id = $link->where('links.id',$link->id)
                ->update([
                    'link' => $request['link'],
                    'is_active' => $request->is_active = 1
                ]);

                $alllinks = array_values($request->links);
                // insert other translations for Link
                foreach ($alllinks as $alllink){
                    $this->linkTranslation->where('link_id',$id)
                    ->where('local',$alllink['local'])
                    ->update([
                        'name' => $alllink ['name'],
                        'local' => $alllink['local'],
                        'link_id' =>  $link->id
                    ]);
                }
                DB::commit();
                return redirect()->route('admin.link')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.link.create')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        $link = $this->link::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.link')->with('success', 'Data deleted successfully');
    }
}
