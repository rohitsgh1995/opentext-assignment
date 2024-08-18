<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FileUploadRequest;
use Facades\App\Services\FileService;
use Facades\App\Services\DebrickedService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class FileUploadController extends Controller
{
    public function upload(FileUploadRequest $request): JsonResponse
    {
        try {

            $files = $request->file('files');

            $uploads = FileService::upload($files);

            foreach ($uploads as $upload) {
                DebrickedService::forwardToDebricked($upload->id);
            }

            return response()->json(['message' => 'Files uploaded successfully.'], 200);
        } catch (\Exception $e) {

            Log::error('File upload failed: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to upload files'], 500);
        }
    }
}
