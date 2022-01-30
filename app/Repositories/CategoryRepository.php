<?php

namespace App\Repositories;

use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category\Category;
use App\Models\Category\CategoryTranslation;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface{

    private $request;
    private $category;
    private $categoryTranslation;
    public function __construct(Category $category, CategoryTranslation $categoryTranslation, Request $request)
    {
        $this->request = $request;
        $this->category = $category;
        $this->categoryTranslation = $categoryTranslation;
    }

    public function index()
    {
        $category = $this->category::orderBy('id','desc')->get();
        return view('admin.category.index',compact('category'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryRequest $request)
    {
        try{
            /** transformation to collection */
            $allcategories = collect($request->category)->all();

            $slug= $request->category['en']['name'];

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's banner
            $unTransCategory_id = $this->category->insertGetId([
                'slug' => $slug ,
                'is_active' => $request->is_active = 1
            ]);

            // check the Category and request
            if(isset($allcategories) && count($allcategories)){
                // insert other translation for Categories
                foreach ($allcategories as $allcategory){
                    $transCategory_arr[] = [
                        'name' => $allcategory ['name'],
                        'local' => $allcategory['local'],
                        'keyword' => $allcategory['keyword'],
                        'meta_desc' => $allcategory['meta_desc'],
                        'category_id' => $unTransCategory_id
                    ];
                }

                $this->categoryTranslation->insert($transCategory_arr);
            }
            DB::commit();

            return redirect()->route('admin.category')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.category')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $category = $this->category::findOrFail($id);

        return view('admin.category.edit',compact('category'));
    }

    public function update($id ,CategoryRequest $request)
    {
        try{

            $category = $this->category::findOrFail($id);

            $slug= $request->category['en']['name'];

            DB::beginTransaction();

            $unTransCategory_id = $category->where('categories.id',$category->id)
                ->update([
                    'slug' => $slug,
                    'is_active' => $request->is_active = 1
                ]);

                $allcategories = array_values($request->category);
                // insert other translations for Category
                foreach ($allcategories as $allcategory){
                    $this->categoryTranslation->where('category_id',$category->id)
                    ->where('local',$allcategory['local'])
                    ->update([
                        'name' => $allcategory ['name'],
                        'local' => $allcategory['local'],
                        'keyword' => $allcategory['keyword'],
                        'meta_desc' => $allcategory['meta_desc'],
                        'category_id' =>  $category->id
                    ]);
                }
                DB::commit();
                return redirect()->route('admin.category')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.category')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        $category = $this->category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.category')->with('success', 'Data deleted successfully');
    }

}
