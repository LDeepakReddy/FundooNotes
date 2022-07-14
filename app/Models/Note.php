<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Note extends Model  implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_id()
    {
        return $this->belongsTo(User::class);
    }

    
    public static function getNotesByNoteIdandUserId($id, $user_id)
    {
        $notes = Note::where('id', $id)->where('user_id', $user_id)->first();
        return $notes;
    }
    
    public function noteId($id) {
        return Note::where('id', $id)->first();
    }

    public static function getAllNotes($user)
    {
        // $notes = Note::leftJoin('labelnotes', 'labelnotes.note_id', '=', 'notes.id')
        //     ->leftJoin('labels', 'labels.id', '=', 'labelnotes.label_id')
        //     ->leftjoin('collaborators','collaborators.note_id', '=', 'notes.id')
        //     ->select('notes.id', 'notes.title', 'notes.description', 'notes.pin', 'notes.archive', 'notes.colour', 'labels.label_name', 'collaborators.email as collaborator')
        //     ->where([['notes.user_id', '=', $user->id], ['archive', '=', 0], ['pin', '=', 0]])->get();
        //     //->orWhere([['archive', '=', 0], ['pin', '=', 0],['collaborators.email', '=', $user->email]])
        //     //->get();
        
        // return $notes;

        $notes = User::leftjoin('notes','notes.user_id', '=', 'users.id')
        ->select('users.id','notes.id', 'notes.title', 'notes.description')
        ->where([['notes.user_id', '=', $user->id]])
        ->get();

        return $notes;


}
}