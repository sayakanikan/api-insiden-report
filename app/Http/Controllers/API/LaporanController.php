<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Laporan::all();

        return response()->json([
            'status'    => 200,
            'message'   => 'Data Berhasil Diambil',
            'data'      => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'user_id'   => '',
            'laporan'   => 'required',
            'status'    => 'required',
            'foto'      => 'required'
        ]);

        $validasi['user_id'] = auth()->user()->id;

        try {
            $response = Laporan::create($validasi);

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Ditambahkan',
                'data'      => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message'  => 'Err',
                'errors'   => $e->getMessage()
            ]);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $validasi = $request->validate([
            'user_id'   => '',
            'laporan'   => '',
            'status'    => 'required',
            'foto'      => ''
        ]);

        $validasi['user_id'] = auth()->user()->id;

        try {
            Laporan::where('id', $id)->update($validasi);

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Diupdate',
                'data'      => $validasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => 'Err',
                'errors'   => $e->getMessage()
            ]);
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
        try {
            Laporan::destroy($id);

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => 'Err',
                'errors'   => $e->getMessage()
            ]);
        }
    }

    public function image(Request $request){
        try {
            $uploadedFileUrl = Cloudinary::upload($request->file('foto')->getRealPath())->getSecurePath();

            return response()->json([
                'status'    => 200,
                'message'   => 'Foto Berhasil Diupload',
                'url'       => $uploadedFileUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => 'Err',
                'errors'   => $e->getMessage()
            ]);
        }
    }

    public function history(){
        try{
            $user = auth()->user()->id;
            $data = DB::table('laporans')->where('user_id', $user)->get();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data berhasil didapatkan',
                'data'      => $data
            ]);
        }catch(Exception $e){
            return response()->json([
                'message'   => 'Err',
                'errors'   => $e->getMessage()
            ]);
        }
    }
}
