<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentManagementRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GlobalResources;
use App\Models\User\DocumentManagement;
use App\Http\Resources\PaginateResources;

class DocumentManagementController extends Controller
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
        $totalData = $request->total_data ?? 10;

        $query = DocumentManagement::paginate($totalData);
        $data = [];
        foreach($query as $querys){
            $data[] = [
                'id'=>$querys->id,
                'dm_title'=>$querys->dm_title,
                'dm_content'=>$querys->dm_content,
                'dm_signing'=>$querys->dm_signing,
            ];
        }
        return new PaginateResources(true,'Data berhasil ditampilkan',$data,$query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentManagementRequest $request)
    {
        $user = Auth::user();
        $signingFile = $request->file('sigining');
        $filename = time().'_'.$signingFile->getClientOriginalName();
        $signingFile->move(public_path('images/'.$user->name.'/signing'), $filename);
        $destination = 'images/'.$user->name.'/'.$filename;

        $query = DocumentManagement::create([
            'dm_title'=>$request->title,
            'dm_content'=>$request->content,
            'dm_signing'=>$destination,
        ]);
        return new GlobalResources(true,'Data berhasil ditambahkan',$query,null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = DocumentManagement::where('id',$id)->first();
        $data = [
                    'dm_id'=>$query->id,
                    'dm_title'=>$query->dm_title,
                    'dm_content'=>$query->dm_content,
                    'dm_signing'=>$this->url.$query->dm_signing
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
    public function updateData(Request $request)
    {
        $user = Auth::user();
        $id = $request->dm_id;
        $destination = null;
        if ($request->hasFile('sigining')) {
            $signingFile = $request->file('sigining');
            $filename = time().'_'.$signingFile->getClientOriginalName();
            $signingFile->move(public_path('images/'.$user->name.'/signing'), $filename);
            $destination = 'images/'.$user->name.'/'.$filename;
        }
        $dataDoc = DocumentManagement::where('id',$id)->first();
        if($dataDoc == null ){
            return new GlobalResources(false,'Data tidak ditemukan',null,null);
        }else {
            $queryUpdate = DocumentManagement::where('id',$id)->update([
                'dm_title'=>$request->title == null ? $dataDoc->dm_title : $request->title,
                'dm_content'=>$request->content == null ? $dataDoc->dm_content : $request->content,
                'dm_signing'=>$destination == null ? $dataDoc->dm_signing : $destination,
            ]);

            return new GlobalResources(true,'Data berhasil diupdate',$queryUpdate,null);
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
