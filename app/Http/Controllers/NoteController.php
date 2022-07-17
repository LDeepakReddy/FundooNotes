<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Note;
use Illuminate\Support\Facades\Cache;
use App\Models\LabelNotes;
use Illuminate\Support\Facades\Log;
use App\Exceptions\FundooNotesException;

class NoteController extends Controller
{


    function createNote(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:3|max:30',
                'description' => 'required|string|min:3|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $currentUser = JWTAuth::authenticate($request->token);
            $user_id = $currentUser->id;

            if (!$currentUser) {
                Log::error('Invalid Authorization Token');
                throw new FundooNotesException('Invalid Authorization Token', 401);
            } else {

                $note = Note::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'user_id' => $user_id,

                ]);
                return response()->json([
                    'message' => 'Note created successfully',
                    'note' => $note
                ], 200);
            }
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }


    function getNoteById(Request $request)
    {

        $validator = Validator::make($request->only('id'), [
            'id' => 'required|integer',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid'], 400);
        }

        $currentUser = JWTAuth::authenticate($request->token);

        if (!$currentUser) {
            return response()->json([
                'message' => 'Invalid Authorization Token',
            ], 401);
        }

        $currentid = $currentUser->id;
        //$note = Note::where('id', $request->id)->first();
        $note = Note::where('user_id', $currentid)->where('id', $request->id)->first();

        if (!$note) {
            return response()->json([
                'message' => 'Invalid id'
            ], 401);
        } else {
            return response()->json(['note' => $note], 200);
        }
    }


    function getAllNotes(Request $request)
    {
        try {

            $currentUser = JWTAuth::authenticate($request->token);

            if (!$currentUser) {
                return response()->json([
                    'message' => 'Invalid Authorization Token',
                ], 401);
            }
            $notes = Note::getAllNotes($currentUser);

            if (!$notes) {
                return response()->json([
                    'message' => 'No note created by this user',
                ], 401);
            } else {
                return response()->json([
                    'notes' => $notes,
                ], 200);
            }
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }


    public function updateNoteById(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|between:2,30',
                'description' => 'required|string|between:3,1000',
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = JWTAuth::authenticate($request->token);

            if (!$user) {

                throw new FundooNotesException('Invalid Authorization Token', 401);
            }

            $note = Note::where('user_id', $user->id)->where('id', $request->id)->first();

            if (!$note) {
                throw new FundooNotesException('Notes Not Found', 404);
            }

            // $note->update([
            //     'title' => $request->title,
            //     'description' => $request->description,
            //     'user_id' => $user->id,
            // ]);
            $note->title = $request->title;
            $note->description = $request->description;
            $note->save();

            return response()->json([
                'status' => 200,
                'note' => $note,
                'mesaage' => 'Note Successfully updated',
            ]);
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }

    function deleteNoteById(Request $request)
    {

        try {

            $validator = Validator::make($request->only('id'), [
                'id' => 'required|integer',
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid'], 400);
            }

            $currentUser = JWTAuth::authenticate($request->token);

            if (!$currentUser) {
                log::warning('Invalid Authorisation Token ');
                throw new FundooNotesException('Invalid Authorization Token', 401);
            }

            $note = Note::where('id', $request->id)->first();

            if (!$note) {
                Log::error('Notes Not Found');
                throw new FundooNotesException('Notes Not Found', 404);
            } else {
                $note->delete($note->id);
                return response()->json([
                    'mesaage' => 'Note deleted Successfully',
                ], 200);
            }
        } catch (FundooNotesException $exception) {
            return response()->json([
                'message' => $exception->message()
            ], $exception->statusCode());
        }
    }

    public function addNoteLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'note_id' => 'required',
            'label_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid authorization token'
            ], 401);
        }

        $labelnote = LabelNotes::where('note_id', $request->note_id)->where('label_id', $request->label_id)->first();
        if ($labelnote) {
            return response()->json([
                'status' => 400,
                'message' => 'Note Already have a label'
            ], 409);
        }

        //$notelabel = LabelNotes::createNoteLabel($request, $user->id);
        $labelnotes = LabelNotes::create([
            'user_id' => $user->id,
            'note_id' => $request->note_id,
            'label_id' => $request->label_id
        ]);
        log::info('Label created Successfully');
        return response()->json([
            'status' => 200,
            'message' => 'Label and note added Successfully',
            'notelabel' => $labelnotes,
        ]);
    }





    public function searchNotes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $searchKey = $request->input('search');
        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser) {

            $usernotes = Note::leftJoin('label_notes', 'label_notes.note_id', '=', 'notes.id')->leftJoin('lables', 'lables.id', '=', 'label_notes.label_id')
                ->select('notes.id', 'notes.title', 'notes.description', 'lables.label_name')
                ->where('notes.user_id', '=', $currentUser->id)->Where('notes.title', 'like', '%' . $searchKey . '%')
                ->orWhere('notes.user_id', '=', $currentUser->id)->Where('notes.description', 'like', '%' . $searchKey . '%')
                ->orWhere('notes.user_id', '=', $currentUser->id)->Where('lables.label_name', 'like', '%' . $searchKey . '%')->get();

            if ($usernotes == '[]') {
                return response()->json([
                    'status' => 404,
                    'message' => 'No results'
                ], 404);
            }
            return response()->json([
                'status' => 201,
                'message' => 'Fetched Notes Successfully',
                'notes' => $usernotes
            ], 201);
        }
        Log::error('Invalid Authorization Token');
        return response()->json([
            'status' => 403,
            'message' => 'Invalid authorization token'
        ], 403);
    }
}
