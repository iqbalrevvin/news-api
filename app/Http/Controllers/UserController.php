<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use App\User;

class UserController extends Controller
{
    public function index()
    {
    	if(Auth::user()){
    		$user = User::where('id', '!=', Auth::user()->id)->where('email', '!=', 'admin@app.com')->get();
    	}else{
    		$response = response()->json([
	            'status' => 'failed',
	            'message' => 'Token Tidak Valid, Silahkan Login Kembali!',
	        ], 200);
	        return $response;
    	}
    	if($user->count() > 0){
	    	$response = response()->json([
	            'status' => 'success',
	            'message' => 'Data Pengguna Ditemukan',
	            'data' => [
	            	'user' => $user
	            ]
	        ], 200);
    	}else{
    		$response = response()->json([
	            'status' => 'failed',
	            'message' => 'Data Pengguna Tidak Ditemukan',
	        ], 400);
    	}

    	return $response;
    }

    public function Add(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'hak_akses' => 'required'
        ]);

        $nama 		= $request->nama;
        $email 		= $request->email;
        $password 	= Hash::make($request->password);
        $hak_akses  = $request->hak_akses;

        if($validator->passes()){
        	$user = User::Create([
        		'name' => $nama,
        		'email' => $email,
        		'password' => $password,
                'privilege' => $hak_akses
        	]);
        	$response =  response()->json([
                'status' => 'success',
                'message' => 'Penambahan Pengguna Berhasil',
                'data' => [
                    'user' => $user,
                ]
            ], 200);
        }else{
        	$response =  response()->json([
                'status' => 'failed',
                'message' => 'Penambahan Pengguna Gagal',
                'data' => [
                	'user' => $validator->errors()->all()
                ]
            ], 400);
        }

        return $response;
    }

    public function Delete($id)
    {
    	$user = User::find($id);
    	$user->delete();

    	$response =  response()->json([
            'status' => 'success',
            'message' => 'Pengguna Berhasil Dihapus',
            'data' => [
                'user' => $user,
            ]
        ], 200);

    	return $response;
    }

    public function Update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [
            'nama' => 'required|max:100',
            'email' => 'required|email',
            'password' => 'required'
        ]);

    	
    	if($validator->passes()){
        	$user = User::find($id);
    		$user->name 		= $request->nama;
    		$user->email 		= $request->email;
    		$user->password 	= Hash::make($request->password);
    		$user->save();
        	$response =  response()->json([
                'status' => 'success',
                'message' => 'Pengguna Berhasil Dierbarui',
                'data' => [
                    'user' => $user,
                ]
            ], 200);
        }else{
        	$response =  response()->json([
                'status' => 'failed',
                'message' => 'Pengguna Gagal Diperbarui!',
                'data' => [
                	'user' => $validator->errors()->all()
                ]
            ], 400);
        }
        return $response;
    }
}
