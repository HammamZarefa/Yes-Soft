<?php

namespace App\Repositories;

use App\Http\Requests\Faq\FaqRequest;
use App\Models\Faq\Faq;
use App\Models\Faq\FaqTranslation;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqRepository implements FaqRepositoryInterface{

    private $request;
    private $faq;
    private $faqTranslation;
    public function __construct(Faq $faq, FaqTranslation $faqTranslation, Request $request)
    {
        $this->request = $request;
        $this->faq = $faq;
        $this->faqTranslation = $faqTranslation;
    }

    public function index()
    {
        $faq = Faq::orderBy('id','desc')->get();

        return view('admin.faq.index',compact('faq'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(FaqRequest $request)
    {
        try{
            /** transformation to collection */
            $allfaqs = collect($request->faq)->all();

            $request->is_active ? $is_active = true : $is_active = false;

            DB::beginTransaction();
            // create the default language's banner
            $unTransFaq_id = $this->faq->insertGetId([
                'is_active' => $request->is_active = 1
            ]);

            // check the Category and request
            if(isset($allfaqs) && count($allfaqs)){
                // insert other translation for Categories
                foreach ($allfaqs as $allfaq){
                    $transFaq_arr[] = [
                        'question' => $allfaq ['question'],
                        'local' => $allfaq['local'],
                        'answer' => $allfaq['answer'],
                        'faq_id' => $unTransFaq_id
                    ];
                }

                $this->faqTranslation->insert($transFaq_arr);
            }
            DB::commit();

            return redirect()->route('admin.faq')->with('success', 'Data added successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.faq.create')->with('error', 'Data failed to add');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);

        return view('admin.faq.edit',compact('faq'));
    }

    public function update(FaqRequest $request,$id)
    {
        try{

            $faq = $this->faq->find($id);

            DB::beginTransaction();
            // //create the default language's faq
            $unTransFaq_id = $this->faq->where('faqs.id', $faq->id)
                ->update([
                    'is_active' =>  $request->is_active = 1,
            ]);

            $allfaqs = array_values( $request->faq);
                //insert other translations for Faqs
                foreach ($allfaqs as $allfaq) {
                    $this->faqTranslation->where('faq_id', $id)
                    ->where('local', $allfaq['local'])
                    ->update([
                        'question' => $allfaq ['question'],
                        'local' => $allfaq['local'],
                        'answer' => $allfaq['answer'],
                        'faq_id' => $faq->id
                    ]);
                }
            DB::commit();
            return redirect()->route('admin.faq')->with('success', 'Data updated successfully');
        }catch(\Exception $ex){
            DB::rollback();
            // return $ex->getMessage();
            return redirect()->route('admin.faq.create')->with('error', 'Data failed to update');
        }
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);

        $faq->delete();

        return redirect()->route('admin.faq')->with('success', 'Data deleted successfully');
    }
}
