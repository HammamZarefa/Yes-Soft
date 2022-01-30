<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\About\AboutRequest;
use App\Http\Requests\General\GeneralRequest;
use App\Service\General\GeneralService;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    private $generalService;
    public function __construct(GeneralService $generalService)
    {
        $this->generalService=$generalService;
    }

    public function dashboard(){
        return $this->generalService->dashboard();
    }

    public function general(){
        return $this->generalService->general();
    }

    public function generalUpdate(GeneralRequest $request){
        return $this->generalService->generalUpdate($request);
    }

    public function about(){
        return $this->generalService->about();
    }

    public function aboutUpdate(AboutRequest $request){
        return $this->generalService->aboutUpdate($request);
    }
}
