<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;

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

    public function processAttendanceData(int $id , $duration)
    {
        $file = File::find($id);
        $storageFilename = $file->storage_filename;

        // $userFile = Storage::get('attendance/'.$storageFilename);
        $userFilePath = Storage::path('attendance/' . $storageFilename);

        $file = fopen($userFilePath, 'r');

        // fgetcsv($file, 0, ',', '"', '\\');

        $mainArray = [];
        $attendanceArray = [];

        while(($attendance = fgetcsv($file, 0, ',', '"', '\\')) !== false){
            $attendanceArray[] = array_map('rtrim', $attendance);;
            // info($attendance);
        }

        fclose($file);

        foreach($attendanceArray as $attendaceInfo){

            // info('Attendanceinfo ',  [$attendaceInfo]);

            $shiftDepts = ['Pharmacy Dept', 'Nursing Dept', 'Nursing Assistants Dept'];
            $date = 0;

            foreach($attendaceInfo as $index => $record){
                $index >= 3 ? $date++ : '';

                if (Carbon::hasFormatWithModifiers($record, 'H:i')){

                    $ordinalDate = $this->getOrdinal($date);

                    [$mShiftTime, $nShiftTime] = in_array($attendaceInfo[2], $shiftDepts) ? ['07:31', '19:31'] : ['08:01', '20:01'];

                    // morning
                    $morningShiftStart = CarbonImmutable::parse($mShiftTime);
                    $morningShiftEnd = $morningShiftStart->addHours($duration);
                    // afternoon
                    $afternoonShiftStart = CarbonImmutable::parse('14:01');
                    $afternoonShiftEnd = $afternoonShiftStart->addHours($duration);
                    //night
                    $eveningShiftStart = CarbonImmutable::parse($nShiftTime);
                    $eveningShiftEnd = $eveningShiftStart->addHours($duration);

                    $recordTime = Carbon::parse($record);

                   
                    if ($recordTime->between($morningShiftStart, $morningShiftEnd)){
    
                        $mainArray['mLate'][$attendaceInfo[1]][$ordinalDate] = $record;
                    }

                    if ($recordTime->between($afternoonShiftStart, $afternoonShiftEnd)){
    
                        $mainArray['aLate'][$attendaceInfo[1]][$ordinalDate] = $record;
                    }

                    if ($recordTime->between($eveningShiftStart, $eveningShiftEnd)){
    
                        $mainArray['nLate'][$attendaceInfo[1]][$ordinalDate] = $record;
                    }

                }

            }
        }

        info($mainArray);
    }

    function getOrdinal($number) {
    // Handle the exception for 11, 12, and 13
    if (in_array(($number % 100), [11, 12, 13])) {
        return $number . 'th';
    }
    
    switch ($number % 10) {
        case 1:  return $number . 'st';
        case 2:  return $number . 'nd';
        case 3:  return $number . 'rd';
        default: return $number . 'th';
    }
}
}
