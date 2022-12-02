<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator;
//use Auth;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function create_role(Request $request)
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
            $roles  = Role::create($data_user);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($roles) {
            return response()->json([
                'message' => 'role successfully registered', 'data' => $roles
            ], 201);
            die();
        } else {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
    }

    //================  get data====================//
    public function read_role()
    {
        $roles  = Role::get();
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            if ($roles) {
                return response()->json([
                    'message' => 'User get successfully ', 'data' => $roles
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
    public function edit_role($id)
    {
        $roles = Role::find($id);
        $role = Auth::user()->hasRole('Admin');
        if ($role) {
            if (is_null($roles)) {
                return $this->sendError('data not found.');
            }
            return response()->json(array('status' => 'true', 'data' => $roles, 'message' => 'Data get Successfully'));
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function update_role(Request $request)
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
            $role = Role::where('id', $id)->update($data_user);
            if ($role) {
                return response()->json(array('status' => 'true', 'data' => $role, 'message' => 'Data update Successfully'));
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
    public function delete_role($id)
    {
        $id = $id;

        $data = Role::find($id)->delete();
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
