<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Label;
use App\Exceptions\FundooNotesException;

use Illuminate\Support\Facades\Log;
//use PhpParser\Node\Stmt\Label;



class LabelController extends Controller
{
    public function createLabel(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'labelname' => 'required|string|between:2,30',]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->tojson(), 400);
            }
            $user = JWTAuth::authenticate($request->token);
            $user_id = $user->id;
                      
            if (!$user) {
                Log::error('Invalid Authorization Token');
                throw new FundooNotesException('Invalid Authorization Token', 401);
            } else {
                // $label = Label::create([
                //     'labelname' => $request->labelname,
                //     'user_id' => $user_id,
                // ]);
                // $label = new Label;
            $label->labelname = $request->get('labelname');
                Log::info('Label successfully created');
                return response()->json([
                    'status' => 200,
                    'message' => 'Label successfully created',
                    'label' => $label
                ]);
            }
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }


    function getLabelById(Request $request)
    {
        try {

            $validator = Validator::make($request->only('id'), [
                'id' => 'required'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid'], 400);
            }

            $currentUser = JWTAuth::authenticate($request->token);

            if (!$currentUser) {
                Log::error('Invalid Authorization Token');
                throw new FundooNotesException('Invalid Authorization Token', 401);
            }

            $currentid = $currentUser->id;
            $label = Label::where('user_id', $currentid)->where('id', $request->id)->first();

            if (!$label) {
                Log::info('Label Not Found');
                throw new FundooNotesException('Label Not Found', 404);
            } else {
                return response()->json(['label' => $label], 201);
            }
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }
}
