<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Lable;
use App\Exceptions\FundooNotesException;

use Illuminate\Support\Facades\Log;


//use PhpParser\Node\Stmt\Lable;



class LabelController extends Controller
{
    public function createLabel(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'labelname' => 'required|string|between:2,20',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->tojson(), 400);
            }
            $currentUser = JWTAuth::authenticate($request->token);
            $user_id = $currentUser->id;
            
            if (!$currentUser) {
                Log::error('Invalid Authorization Token');
                throw new FundooNotesException('Invalid Authorization Token', 401);
            } else {
                $label = Lable::create([
                    'label_name' => $request->labelname,
                    'user_id' => $user_id,
                ]);
                Log::info('Lable successfully created');
                return response()->json([
                    'status' => 200,
                    'message' => 'Lable successfully created',
                    'label' => $label
                ]);
            }
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }
    
}
