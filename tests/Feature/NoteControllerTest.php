<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
   
    /** @test */

    public function test_SuccessfulCreateNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjU3ODUxNDc1LCJleHAiOjE2NTc4NTUwNzUsIm5iZiI6MTY1Nzg1MTQ3NSwianRpIjoiT3BRaU5xR1ZSY3M3aEdLYSIsInN1YiI6IjI2IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.uFTRKN81O_EZNwjZDcmIFSPKGXNd1FgWRqK-F9b_6kk'
        ])->json('POST', '/api/createNote',
        [
            "title" => "Rishab1",
            "description" => "Rishab Note",
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Note created successfully']);
    }

  /** @test */

    public function test_UnsuccessfulCreateNote()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjU3ODUzMzE2LCJleHAiOjE2NTc4NTY5MTYsIm5iZiI6MTY1Nzg1MzMxNiwianRpIjoiU0h6eWQ0T3lIMTdUeEl3byIsInN1YiI6IjI2IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.1Yz5GuvbRL3In4D3TXlHEpJQ_wGZY7j3jRnrsWgQ44A'
        ])->json('POST', '/api/createNote',
        [
            "title" => "Rishab Notee",
            "description" => "DShghgm",
        ]);

        $response->assertStatus(401)->assertJson(['message' => 'Invalid Authorization Token']);
    }

    
      /** @test */

    public function test_SuccessfulgetNotes()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjU2OTIwNjU2LCJleHAiOjE2NTY5MjQyNTYsIm5iZiI6MTY1NjkyMDY1NiwianRpIjoiS2Rxb3k5Nkk0YkZscTc1TSIsInN1YiI6IjYiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.05JTJ_TTJa9GZjV2Waye23WLno0FiuYhczmZztC48o0'
         ])->json('GET', '/api/getAllNotes');

         $response->assertStatus(200)->assertJson(['message' => 'Notes Found Successfully']);
     }

     public function test_UnsuccessfulgetNotes()
     {
         $response = $this->withHeaders([
             'Content-Type' => 'Application/json',
             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNjU2NjA1MDM0LCJleHAiOjE2NTY2MDg2MzQsIm5iZiI6MTY1NjYwNTAzNCwianRpIjoiS3NtVGxtNXBYUXpleWlvaCIsInN1YiI6IjkiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.cRZvozpSSz9iBZA2UWuJPbWZHVesOy0qhCEkG4MDNBg'
         ])->json('GET', '/api/getAllNotes');

         $response->assertStatus(401)->assertJson(['message' => 'No note created by this user']);
     }

}
