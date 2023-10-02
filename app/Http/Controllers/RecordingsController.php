<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecordingRequest;
use App\Http\Resources\RecordingsResource;
use App\Models\Recording;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use getID3;

class RecordingsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $get_recordings = Recording::OrderBy('id', 'asc')->get();

        $count = $get_recordings->count();

        if($count > 0){

        return RecordingsResource::collection($get_recordings);

        }else{
       
            return response()->json([
                'message' => 'No Recordings Found!',
                'status_code' => 404
            ], 404);
       }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{

    

    $validator = Validator::make($request->all(), (new StoreRecordingRequest)->rules());

    if ($validator->fails()) {
        return response()->json([
            'error_message' => $validator->errors(),
            'status_code' => 400
        ], 400);
    }

    if ($request->hasFile('video')) {

        $file = $request->file('video');

        $file_name = $file->getClientOriginalName();

        $file_name = str_replace(' ', '_', $file_name);
        
        $title = $request->input('title') ?? pathinfo($file_name, PATHINFO_FILENAME);
        
        $slug = Str::slug($request->input('title') ?? pathinfo($file_name, PATHINFO_FILENAME), '-');
        
        $file->storeAs('videos', $file_name, ['s3', 'public']);

        $local_path = $file->storeAs('videos', $file_name, 'public');

        $local_file_path = storage_path('app/public/' . $local_path);
        
        $getid3 = new \getID3;
        
        $record_file = $getid3->analyze($local_file_path);
        
        $duration_sec = $record_file['playtime_seconds'];
        
        $minutes = floor($duration_sec / 60);

        $seconds = round($duration_sec - ($minutes * 60));

        $file_length = $minutes. ':' . $seconds;
        
        $file_bytes = $file->getSize();

        $file_size = number_format($file_bytes / (1024 * 1024), 2).'mb';

        $path = Storage::path('videos/' . $file_name);

        $url = 'https://videohng.s3.amazonaws.com/'.$path;

        $api_url = 'https://transcribe.whisperapi.com';

        $api_key = config('services.whisper.api_key');

        $record = fopen($url, 'r');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $api_key,
        ])
        ->attach('file',$record)->post($api_url, [
            'fileType' => 'mp4',
            'language' =>'en',
            'diarization' => 'false',
            'task' => 'transcribe'
           
        ]);

        $transcription = $response['text'];
        
        $save_record = Recording::create([
            'file_name' => $file_name,
            'title' => $title,
            'file_size' => $file_size,
            'file_length' => $file_length,
            'url' => $url,
            'transcription' => $transcription,
            'slug' => $slug
        ]);

        if ($save_record) {

            unlink($local_file_path);

            return response()->json([
                'message' => 'Video Uploaded Successfully',
                'status_code' => 201,
                'data' => new RecordingsResource($save_record)
            ], 201);
        } else {
            return response()->json([
                'error_message' => 'Error Occured, Recording not saved!',
                'status_code' => 500
            ], 500);
        }
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recording = Recording::find($id);

        if(!$recording){
            return response()->json([
                'error_message' => 'No Rocording found with id:'.$id,
                'status_code' => 404
            ], 404);
        }

        return new RecordingsResource($recording);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $recording = Recording::find($id);

    if ($recording) {

        $file_path = 'videos/' . $recording->file_name;

        if (Storage::disk('s3')->exists($file_path)) {

            Storage::disk('s3')->delete($file_path);
        }

        $recording->delete();

        return response()->json([
            'message' => "Recording Deleted Successfully",
            'status_code' => 200
        ], 200);

      }
        return response()->json([
            'message' => "Recording with id: ".$id." Does Not Exist!",
            'status_code' => 200
        ], 200);

    
    }
}
