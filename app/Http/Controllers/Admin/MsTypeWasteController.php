<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\MsTypeWaste;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GlobalResources;
use App\Http\Resources\PaginateResources;

use App\Http\Requests\Admin\StoreMsTypeWasteRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MsTypeWasteController extends Controller
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
    public function store(StoreMsTypeWasteRequest $request)
    {
        $user = Auth::user();
        $filePhoto = $request->file('foto');
        $filename = time().'_'.$filePhoto->getClientOriginalName();
        $filePhoto->move(public_path('images/'.$user->name.'/photo'), $filename);
        $destination = 'images/'.$user->name.'/'.$filename;
        try{
            MsTypeWaste::create([
                'mtw_name'=>$request->nama,
                'mtw_description'=>$request->deskripsi,
                'mtw_photo'=>$destination,
                'mtw_price'=>$request->harga,
                'mtw_unit'=>$request->satuan,
            ]);
            return new GlobalResources(true,'Data Berhasil ditambahkan',null,null);
        }catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>'Terjadi Kesalahan',
                'data'=>$e->getMessage(),
            ],500);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $query = MsTypeWaste::findOrFail($id);
            $data = [
                'id_jenis_sampah' => $query->id,
                'foto' => $this->url . $query->mtw_photo,
                'nama' => $query->mtw_name,
                'deskripsi' => $query->mtw_description,
                'harga' => $query->mtw_price,
                'satuan' => $query->mtw_unit,
            ];

            return new GlobalResources(true, 'Data Berhasil ditampilkan', $data, null);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null,
            ], 404); // Return a 404 Not Found response
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'data' => $e->getMessage(),
            ], 500); // Return a 500 Internal Server Error response for other exceptions
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request, $id)
    {
        try{
            $query = MsTypeWaste::findOrFail($id)
                    ->where('id',$id)
                    ->update([
                        'mtw_name'=>$request->nama,
                        'mtw_description'=>$request->deskripsi,
                        'mtw_price'=>$request->harga,
                        'mtw_unit'=>$request->satuan,
                    ]);
            return new GlobalResources(true,'Data Berhasil diupdate',$query,null);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null,
            ], 404); // Return a 404 Not Found response
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'data' => $e->getMessage(),
            ], 500); // Return a 500 Internal Server Error response for other exceptions
        }

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
