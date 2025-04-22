<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

trait FileUpload
{
    public function save_file($file, $folder): string
    {
        $image_name = date('Ymd_His').'_'.rand().'.'.$file->getClientOriginalExtension();
        $file->storeAs('uploads/'.$folder,$image_name,'public');
        return $image_name;

    }

    public function remove_file($path,$name): void
    {
        if($name == 'default.png'){
            return;
        }

        $file_path = public_path('storage/uploads/').$path.'/'.$name;
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
    public function uploadAttachments(String $type,$folder)
    {
        if (request()->hasFile($type)) {
            $file = request()->file($type);

            // Generate a unique name for the file
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store the file in the specified folder
            $fileUrl = $file->storeAs($folder, $fileName, 'public');

            // Return file details
            return [
                'file_url'  => $fileUrl,
                'type'      => $type,
                'extension' => $file->getClientOriginalExtension(),
                'size'      => $file->getSize(),
                'created_at'=> Carbon::now(),
            ];
        }

        return null;

    }

}
