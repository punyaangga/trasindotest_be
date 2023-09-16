<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\MsTypeWaste;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResources;

class ListWasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $url;
    public function __construct()
    {
        $currenturl = URL::current();
        $eksplode = explode('/', $currenturl);
        $this->url = $eksplode[0] . '//' . $eksplode[2] . '/';
    }
    public function index(Request $request)
    {
        $totalData = $request->jumlah_data ?? 10;
        $query = MsTypeWaste::paginate($totalData);
        $data = [];
            foreach($query as $querys){
                $data []= [
                            'id_jenis_sampah'=>$querys->id,
                            'foto'=>$this->url.$querys->mtw_photo,
                            'nama'=>$querys->mtw_name,
                            'deskripsi'=>$querys->mtw_description,
                            'harga'=>$querys->mtw_price,
                            'satuan'=>$querys->mtw_unit,
                         ];
            }
        return new PaginateResources(true,'Data Berhasil ditampilkan',$data,$query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
