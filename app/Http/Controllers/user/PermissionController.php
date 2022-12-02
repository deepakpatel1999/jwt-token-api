<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Validator;
//use Auth;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function create_permission(Request $request)
    {
        $data = $request->all();

        $rules = [
            'name' => 'required',
            'display_name' => 'required',
            'description'    => 'required',
        ];
        $validator = Validator::make($data, $rules);
        $error_msg = $validator->errors()->first();
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            $data_user = array('name' => $data['name'], 'display_name' => $data['display_name'], 'description' => $data['description']);
            $Permission  = Permission::create($data_user);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($Permission) {
            return response()->json([
                'message' => 'data successfully registered', 'data' => $Permission
            ], 201);
            die();
        } else {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
    }
    //================  get data====================//
    public function read_permission()
    {
        $permission  = Permission::get();
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            if ($permission) {
                return response()->json([
                    'message' => 'gate successfully ', 'data' => $permission
                ], 201);
                die();
            } else {
                return response()->json(['error' => 'somthing wrong'], 401);
                die();
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // =================== edit ========================//
    public function edit_permission($id)
    {
        $permission = Permission::find($id);
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            if (is_null($permission)) {
                return $this->sendError('data not found.');
            }
            return response()->json(array('status' => 'true', 'data' => $permission, 'message' => 'Data get Successfully'));
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function update_permission(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'display_name' => 'required',
            'description'    => 'required',
        ];
        $validator = Validator::make($data, $rules);
        $error_msg = $validator->errors()->first();
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
        $data_user = array('name' => $data['name'], 'display_name' => $data['display_name'], 'description' => $data['description']);
        $id = $request['id'];
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            $permission = Permission::where('id', $id)->update($data_user);
            if ($permission) {
                return response()->json(array('status' => 'true', 'data' => $permission, 'message' => 'Data update Successfully'));
                die();
            } else {
                return $this->sendError('Validation Error.', $validator->errors());
                die();
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    //===============Delete Data=====================//
    public function delete_permission($id)
    {
        $id = $id;

        $data = Permission::find($id)->delete();
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            if ($data) {
                return response()->json(array('status' => 'true', 'message' => 'succssesfuly'));

                die();
            } else {
                return response()->json(array('status' => 'false', 'message' => 'Somthing went wrong'));
                die();
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
