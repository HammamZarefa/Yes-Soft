<?php

namespace App\Repositories;

use App\Http\Requests\Page\PageRequest;
use App\Models\Page\Page;
use App\Models\Page\PageTranslation;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageRepository implements PageRepositoryInterface{

    private $page;
    private $request;
    private $pageTranslation;
    public function __construct(Page $page, PageTranslation $pageTranslation, Request $request)
    {
        $this->page = $page;
        $this->request = $request;
        $this->pageTranslation = $pageTranslation;
    }

    public function index()
    {
        $page = Page::all();
        return view ('admin.page.index', compact('page'));
    }

    public function create()
    {
        return view('admin.page.create');
    }

    public function store(PageRequest $request)
    {
        try{
            /** transformation to collection */
            $allpages = collect($request->page)->all();

            $slug= $request->page['en']['title'];

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's banner
            $unTransPage_id = $this->page->insertGetId([
                'slug' => $slug ,
                'is_active' => $request->is_active = 1
            ]);

            // check the Category and request
            if(isset($allpages) && count($allpages)){
                // insert other translation for Categories
                foreach ($allpages as $allpage){
                    $transPage_arr[] = [
                        'title' => $allpage ['title'],
                        'local' => $allpage['local'],
                        'text' => $allpage['text'],
                        'page_id' => $unTransPage_id
                    ];
                }

                $this->pageTranslation->insert($transPage_arr);
            }
            DB::commit();

            return redirect()->route('admin.page')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.page.create')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $page = $this->page::findOrFail($id);
        return view ('admin.page.edit', compact('page'));
    }

    public function update(PageRequest $request,$id)
    {
        try{

            $page = $this->page::findOrFail($id);

            $slug= $request->page['en']['title'];

            DB::beginTransaction();

            $unTransPage_id = $page->where('pages.id',$page->id)
                ->update([
                    'slug' => $slug,
                    'is_active' => $request->is_active = 1
                ]);

                $allpages = array_values($request->page);
                // insert other translations for Category
                foreach ($allpages as $allpage){
                    $this->pageTranslation->where('page_id',$id)
                    ->where('local',$allpage['local'])
                    ->update([
                        'title' => $allpage ['title'],
                        'local' => $allpage['local'],
                        'text' => $allpage['text'],
                        'page_id' =>  $unTransPage_id
                    ]);
                }
                DB::commit();
                return redirect()->route('admin.page')->with('success', 'Data Berhasil Diperbarui');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.page.edit')->with('error', 'Data Gagal Diperbarui');
        }
    }

    public function destroy($id)
    {
        $page = $this->page::findOrFail($id);

        $page->delete();

        return redirect()->route('admin.page')->with('success', 'Data Berhasil Dihapus');
    }
}
