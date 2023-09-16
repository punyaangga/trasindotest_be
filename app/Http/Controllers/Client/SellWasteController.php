<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\MsTypeWaste;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreSellWasteRequest;
use App\Http\Resources\GlobalResources;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class SellWasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellWasteRequest $request)
    {

        $user= Auth::user();
        $id =  $request->id_jenis_sampah;
        $qty = $request->qty;
        $satuan = 'kg';


        $getHarga = MsTypeWaste::where('id',$id)->first();
        $totalHarga  = $qty * $getHarga->mtw_price;

        Transaction::create([
            'user_id' => $user->id,
            'mtw_id' =>$id,
            'tr_qty' => $qty,
            'tr_qty_unit' => $satuan,
            'tr_total_price' => $totalHarga,
        ]);
        return new GlobalResources(true,'Data berhasil disimpan',null,null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $query = Transaction::join('users','users.id','=','transactions.user_id')
                ->join('ms_type_wastes','ms_type_wastes.id','=','transactions.mtw_id')
                ->where('transactions.id',$id)
                ->where('transactions.user_id',$user->id)
                ->first();
        $data = [
            'nama_client'=>$query->name,
            'jenis_sampah'=>$query->mtw_name,
            'qty'=>$query->tr_qty,
            'total_harga'=>$query->tr_total_price,
        ];
        return new GlobalResources(true,'Data berhasil ditampilkan',$data,null);
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
