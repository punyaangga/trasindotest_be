<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\ProfileUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\GlobalResources;
use App\Http\Resources\PaginateResources;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileUserController extends Controller
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
        $query = User::select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone_number',
                    'profile_users.pu_company_name',
                    'profile_users.pu_division',
                )
                ->leftJoin('profile_users','profile_users.user_id','=','users.id')
                ->orderBy('users.id','asc')
                ->paginate($totalData);

        $data = [];
        foreach($query as $querys){
            $data[] = [
                'user_id'=>$querys->id,
                'name'=>$querys->name,
                'email'=>$querys->email,
                'phone_number'=>$querys->phone_number,
                'company_name'=>$querys->pu_company_name,
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
        $query = User::select(
            'users.name',
            'users.email',
            'users.phone_number',
            'profile_users.pu_company_name',
            'profile_users.pu_division',
            'profile_users.pu_photo',
        )
        ->leftJoin('profile_users','profile_users.user_id','=','users.id')
        ->where('users.id',$id)
        ->first();

        $data = [
            'photo_profile'=>$this->url.$query->pu_photo,
            'name'=>$query->name,
            'email'=>$query->email,
            'phone_number'=>$query->phone_number,
            'company_name'=>$query->pu_company_name,
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
    public function updateData(UpdateProfileRequest $request)
    {
        $destination = null;
        $id = $request->user_id;
        if ($request->hasFile('photo_profile')) {
            $photoProfile = $request->file('photo_profile');
            $filename = time() . '_' . $photoProfile->getClientOriginalName();
            $photoProfile->move(public_path('images/' . $request->name . '/'), $filename);
            $destination = 'images/' . $request->name . '/' . $filename;
        }

        $dataUser = User::where('id',$id)->first();
        $profileUser = ProfileUser::where('user_id',$id)->first();
        if($dataUser == null || $profileUser == null){
            return new GlobalResources(false,'Data tidak ditemukan',null,null);
        }else {
            try{
                DB::beginTransaction();
                $queryUpdateDataUser = User::where('id',$id)->update([
                    'name'=>$request->name == null ? $dataUser->name: $request->name,
                    'email'=>$request->email == null ? $dataUser->email: $request->email,
                    'phone_number'=>$request->phone_number == null ? $dataUser->phone_number: $request->phone_number,
                    'password' => Hash::make($request->password) == null ? $dataUser->password: Hash::make($request->password),
                ]);
                $queryUpdateDataProfileUser = ProfileUser::where('user_id',$id)->update([
                    'pu_company_name'=>$request->company_name == null ? $profileUser->pu_company_name: $request->company_name,
                    'pu_division'=>$request->division == null ? $profileUser->pu_division: $request->division,
                    'pu_photo'=>$destination == null ? $profileUser->pu_photo: $destination,
                ]);
                DB::commit();
                return new GlobalResources(true,'Data berhasil diupdate',$queryUpdateDataUser,null);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json([
                    "success"=> false,
                    "message"=>'Terjadi kesalah',
                    "data"=>$e->getMessage()
                ],500);
            }

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
