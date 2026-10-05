<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function createFile(Request $data, User $user)
    // {
    //     $userFile = $data->file('file');

    //     if(!$userFile){
    //         return request()->json('message' , 'User file not found.');
    //     }

    //     $extension = $userFile->extension();
    //     $fileName = $userFile->getFilename();
    //     $mimetype = $userFile->getMimeType();
    //     $storageFilename = bin2hex(random_bytes(10));
    //     $userFile->storeAs('attendance', $storageFilename);
        
    //     info('info', ['ext' => $extension, 'filename' => $fileName, 'mime' => $mimetype, 'storageFileName' => $storageFilename]);

    //    return $user->files()->create([
    //         'file_name' => $data->fileName ?? $fileName, 
    //         'storage_filename' => $storageFilename,
    //         'client_mimetype' => $mimetype,
    //         'extension' => $extension,
    //         'comment' => $data->comment
    //     ]);

    // }

    public function createFile(Request $data, User $user)
    {
        $userFile = $data->file('file');

        if (!$userFile) {
            return response()->json(['message' => 'User file not found.'], 400);
        }

        // Client-provided values (matches what the user uploaded)
        $extension = $userFile->getClientOriginalExtension();
        $fileName = $userFile->getClientOriginalName();
        $mimetype = $userFile->getClientMimeType();

        // Fallback or guessed extension based on MIME type (optional double-check)
        // $guessedExtension = $userFile->guessExtension(); 

        $storageFilename = bin2hex(random_bytes(10)) . '.' . $extension;
        $userFile->storeAs('attendance', $storageFilename);

        info('info', [
            'ext' => $extension, 
            'filename' => $fileName, 
            'mime' => $mimetype, 
            'storageFileName' => $storageFilename
        ]);

        return $user->files()->create([
            'file_name' => $data->fileName ?? $fileName, 
            'storage_filename' => $storageFilename,
            'client_mimetype' => $mimetype,
            'extension' => $extension,
            'comment' => $data->comment
        ]);
    }

    public function processAttendanceData(int $id)
    {
        $file = File::find($id);
        $storageFilename = $file->storage_filename;

        // $userFile = Storage::get('attendance/'.$storageFilename);
        $userFilePath = Storage::path('attendance/' . $storageFilename);

        $file = fopen($userFilePath, 'r');

        // fgetcsv($file);

        $attendanceArray = [];

        while(($attendance = fgetcsv($file, 0, ',', '"', '\\')) !== false){
            $attendanceArray[] = $attendance;
            // info($attendance);
        }

        fclose($file);

        foreach($attendanceArray as $index => $attendaceInfo){
            info($attendaceInfo);

        }
    }
}
