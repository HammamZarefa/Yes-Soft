<?php

namespace App\Repositories;

use App\Models\Tag\Tag;
use App\Models\Tag\TagTranslation;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagRepository implements TagRepositoryInterface{

    private $request;
    private $tag;
    private $tagTranslation;
    public function __construct(Tag $tag, TagTranslation $tagTranslation,Request $request)
    {
        $this->request = $request;
        $this->tag = $tag;
        $this->tagTranslation = $tagTranslation;
    }

    public function index()
    {
        $tag = Tag::orderBy('id','desc')->get();

        return view('admin.tag.index',compact('tag'));
    }

    public function create()
    {
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        try{
            /** transformation to collection */
            $alltags = collect($request->tag)->all();

            $slug= $request->tag['en']['name'];

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's banner
            $unTransTag_id = $this->tag->insertGetId([
                'slug' => $slug ,
                'is_active' => $request->is_active = 1
            ]);

            // check the Category and request
            if(isset($alltags) && count($alltags)){
                // insert other translation for Categories
                foreach ($alltags as $alltag){
                    $transTag_arr[] = [
                        'name' => $alltag ['name'],
                        'local' => $alltag['local'],
                        'keyword' => $alltag['keyword'],
                        'meta_desc' => $alltag['meta_desc'],
                        'tag_id' => $unTransTag_id
                    ];
                }

                $this->tagTranslation->insert($transTag_arr);
            }
            DB::commit();

            return redirect()->route('admin.tag')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            return $ex->getMessage();
            return redirect()->route('admin.tag')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $tag = $this->tag::findOrFail($id);
        return view('admin.tag.edit',compact('tag'));
    }

    public function update($id)
    {
        try{
            $tag = $this->tag::findOrFail($id);

            $slug= $this->request->tag['en']['name'];

            DB::beginTransaction();

            $unTransTag_id = $tag->where('tags.id',$tag->id)
                ->update([
                    'slug' => $slug,
                    'is_active' => $this->request->is_active = 1
                ]);

                $alltags = array_values($this->request->tag);
                // insert other translations for Link
                foreach ($alltags as $alltag){
                    $this->tagTranslation->where('tag_id',$id)
                    ->where('local',$alltag['local'])
                    ->update([
                        'name' => $alltag ['name'],
                        'local' => $alltag['local'],
                        'keyword' => $alltag['keyword'],
                        'meta_desc' => $alltag['meta_desc'],
                        'tag_id' =>  $unTransTag_id
                    ]);
                }
                DB::commit();
                return redirect()->route('admin.tag')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            return $ex->getMessage();
            return redirect()->route('admin.tag')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.tag')->with('success', 'Data deleted successfully');
    }
}
