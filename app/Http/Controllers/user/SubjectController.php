<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Subject;
use Validator;
use Auth;

class SubjectController extends Controller
{
    public function __construct()
    {
        // $this->middleware("auth:role['Admin|Teacher']", ['except' => ['read_subject']]);
        $this->middleware(['role:Admin|Teacher', 'permission:readpost']);
    }
    public function create_subject(Request $request)
    {

        $data = $request->all();

        $rules = [
            'name' => 'required',

        ];
        $validator = Validator::make($data, $rules);
        $error_msg = $validator->errors()->first();
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
        $role = Auth::user()->hasRole('Admin|Teacher');
        if ($role) {
            $data_user = array('name' => $data['name']);
            $subject  = Subject::create($data_user);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($subject) {
            return response()->json([
                'message' => 'subject successfully registered', 'data' => $subject
            ], 201);
            die();
        } else {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
    }
    // ================  get data====================//
    public function read_subject()
    {
        $subject  = Subject::get();
        $role = Auth::user()->hasRole('Admin|Teacher|Student');
        if ($role) {
            if ($subject) {
                return response()->json([
                    'message' => 'User get successfully', 'data' => $subject
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
    public function edit_subject($id)
    {
        $subject = Subject::find($id);
        $role = Auth::user()->hasRole('Admin|Teacher');
        if ($role) {
            if (is_null($subject)) {
                return $this->sendError('data not found.');
            }
            return response()->json(array('status' => 'true', 'data' => $subject, 'message' => 'Data get Successfully'));
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function update_subject(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required',

        ];
        $validator = Validator::make($data, $rules);
        $error_msg = $validator->errors()->first();
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
            die();
        }
        $data_user = array('name' => $data['name']);
        $id = $request['id'];
        $role = Auth::user()->hasRole('Admin|Teacher');
        if ($role) {
            $subject = Subject::where('id', $id)->update($data_user);
            if ($subject) {
                return response()->json(array('status' => 'true', 'data' => $subject, 'message' => 'Data update Successfully'));
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
    public function delete_subject($id)
    {
        $id = $id;

        $role = Auth::user()->hasRole('Admin|Teacher');
        if ($role) {
            $data = Subject::find($id)->delete();
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
