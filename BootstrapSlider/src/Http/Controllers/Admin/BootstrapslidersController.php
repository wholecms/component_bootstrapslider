<?php

namespace Components\BootstrapSlider\Http\Controllers\Admin;

use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Components\BootstrapSlider\Repositories\Bootstrapslider\BootstrapsliderRepository;
use Whole\Core\Logs\Facade\Logs;

class BootstrapslidersController extends Controller
{
    protected $bootstrapslider;

    /**
     * @param BootstrapsliderRepository $bootstrapslider
     */
    public function __construct(BootstrapsliderRepository $bootstrapslider)
    {
        $this->bootstrapslider = $bootstrapslider;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sliders = $this->bootstrapslider->all();
        return view('backend::bootstrapsliders.index',compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend::bootstrapsliders.create');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
		if ($this->bootstrapslider->saveData('create',$request->all()))
		{
			Flash::success('Başarıyla Kaydedildi');
			Logs::add('process',"Slider Başarıyla Eklendi. \n");
			return redirect()->route('admin.bootstrapslider.index');
		}else 
		{
			Flash::error('Bir Hata Meydana Geldi ve Kaydedilemedi');
			Logs::add('errors',"Slider Eklerken Hata Meydana Geldi! \n");
			return redirect()->back();
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
		//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $slider = $this->bootstrapslider->find($id);
        return view('backend::bootstrapsliders.edit',compact('slider'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param BlockRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlockRequest $request, $id)
    {
        if ($this->bootstrapslider->saveData('update',$data,$id))
        {
            Logs::add('process',"Slider Başarıyla Düzenlendi \nSlider ID:{$id}");
            Flash::success('Başarıyla Düzenlendi');
            return redirect()->route('admin.bootstrapslider.index');
        }
        else
        {
            Logs::add('errors',"Slider Düzenlerken Hata Meydana Geldi \nSlider ID:{$id}");
            Flash::error('Bir Hata Meydana Geldi ve Düzenlenemedi');
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $message = $this->bootstrapslider->delete($id) ?
            ['success','Başarıyla Silindi'] :
            ['error','Bir Hata Meydana Geldi ve Silinemedi'];
        if($message[0]=="success")
        {
            Logs::add('process',"Slider Başarıyla Silindi \nSlider ID:{$id}");
        }else
        {
            Logs::add('errors',"Slider Silinemedi \nSlider ID:{$id}");
        }
        Flash::$message[0]($message[1]);
        return redirect()->route('admin.bootstrapslider.index');
    }

}