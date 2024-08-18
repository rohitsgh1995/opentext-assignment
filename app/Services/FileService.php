<?php

namespace App\Services;

use App\Models\DependencyFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class FileService
{
    public function upload($files): array
    {
        $uploadedFiles = [];

        if (!$files instanceof UploadedFile) {
            $files = is_array($files) ? $files : [$files];
        }

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                try {

                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = $originalName . '_' . time() . '.' . $extension;

                    $path = $file->storeAs('uploads', $newFileName);

                    $save = $this->saveDependencyFile($path);

                    $uploadedFiles[] = $save;
                } catch (\Exception $e) {

                    Log::error('File upload error: ' . $e->getMessage());
                }
            } else {
                Log::warning('Invalid file upload attempt.');
            }
        }

        return $uploadedFiles;
    }

    private function saveDependencyFile(string $path): DependencyFile
    {
        try {
            return DependencyFile::create([
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save file path to the database: ' . $e->getMessage());
            return null;
        }
    }
}
