<?php

namespace App\Repositories;

use App\Http\Requests\Portfolio\PortfolioRequest;
use App\Models\Pcategory\Pcategory;
use App\Models\Pcategory\PcategoryTranslation;
use App\Models\Portfolio\Portfolio;
use App\Models\Portfolio\PortfolioTranslation;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\PortfolioRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PortfolioRepository implements PortfolioRepositoryInterface{

    private $request;
    private $pcategory;
    private $portfolio;
    private $portfolioTranslation;
    public function __construct(Portfolio $portfolio, PortfolioTranslation $portfolioTranslation, Pcategory $pcategory, Request $request)
    {
        $this->request = $request;
        $this->pcategory = $pcategory;
        $this->portfolio = $portfolio;
        $this->portfolioTranslation = $portfolioTranslation;
    }

    public function index()
    {
        $portfolio = $this->portfolio::orderBy('id','desc')->get();

        return view('admin.portfolio.index',compact('portfolio'));
    }

    public function create()
    {
        $categories = $this->pcategory::get();

        return view('admin.portfolio.create',compact('categories'));
    }

    public function store(PortfolioRequest $request)
    {
//        dd($request);
        try{
            /** transformation to collection */
            $allportfolioes = collect($request->portfolio)->all();


            $slug= $request->portfolio['en']['name'];

            $cover = $request->file('cover');

            if($cover){
                $cover_path = $cover->store('images/portfolio', 'public');

            }

            $mobileImage = $request->file('mobileImage');

            if($mobileImage){
                $mobileImage_path = $mobileImage->store('images/portfolio', 'public');
            }

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's portfolio
            $unTransPortfolio_id = $this->portfolio->insertGetId([
                'slug' => $slug ,
                'pcategory_id' => $request['category'],
                'mobileImage' => $mobileImage_path,
                'cover' => $cover_path,
                'link' => $request['link'],
                'date' => $request['date'],
                'is_active' => $request->is_active = 1
            ]);

            // check the Portfolio and request
            if(isset($allportfolioes) && count($allportfolioes)){
                // insert other translation for Portfolioes
                foreach ($allportfolioes as $allportfolio){
                    $transPortfolio_arr[] = [
                        'name' => $allportfolio ['name'],
                        'local' => $allportfolio['local'],
                        'client' => $allportfolio['client'],
                        'desc' => $allportfolio['desc'],
                        'portfolio_id' => $unTransPortfolio_id
                    ];
                }

                $this->portfolioTranslation->insert($transPortfolio_arr);
            }
            DB::commit();

            return redirect()->route('admin.portfolio')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.portfolio.create')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $portfolio = $this->portfolio::findOrFail($id);
        $categories = $this->pcategory::get();

        return view('admin.portfolio.edit',compact('portfolio','categories'));
    }

    public function update(PortfolioRequest $request,$id)
    {
        try{
            $portfolio = $this->portfolio::findOrFail($id);

            $slug= $request->portfolio['en']['name'];

        // image desktop
        $new_cover = $request->file('cover');

        if($new_cover){
            if($request->cover && file_exists(storage_path('app/public/' .$request->cover))){
                Storage::delete('public/'. $request->cover);
            }
            $new_cover_path = $new_cover->store('images/portfolio', 'public');

        }
        // image mobile
        $new_mobileImage = $request->file('mobileImage');

        if($new_mobileImage){
            if($portfolio->mobileImage && file_exists(storage_path('app/public/' . $portfolio->mobileImage))){
                Storage::delete('public/'. $portfolio->mobileImage);
            }
            $new_mobileImage_path = $new_mobileImage->store('images/portfolio', 'public');

        }

            DB::beginTransaction();
            // //create the default language's portfolio
            $unTransPortfolio_id = $this->portfolio->where('portfolios.id', $portfolio->id)
                ->update([
                    'slug' => $slug,
                    'pcategory_id' => $this->request['category'],
                    'date' => $this->request['date'],
                    'is_active' => $this->request->is_active = 1,
                    'cover' => $new_cover_path,
                    'mobileImage' => $new_mobileImage_path
            ]);

            $allportfolioes = array_values($this->request->portfolio);
                //insert other translations for Portfolio
                foreach ($allportfolioes as $allportfolio) {
                    $this->portfolioTranslation->where('portfolio_id', $id)
                    ->where('local', $allportfolio['local'])
                    ->update([
                        'name' => $allportfolio ['name'],
                        'local' => $allportfolio['local'],
                        'client' => $allportfolio['client'],
                        'desc' => $allportfolio['desc'],
                        'portfolio_id' =>  $portfolio->id
                    ]);
                }
            DB::commit();
            return redirect()->route('admin.portfolio')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.portfolio.edit')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        $portfolio = $this->portfolio::findOrFail($id);
        $portfolio->delete();

        return redirect()->route('admin.portfolio')->with('success', 'Data deleted successfully');
    }
}
