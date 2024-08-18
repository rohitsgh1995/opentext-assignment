<?php

namespace App\Jobs;

use App\Models\DependencyFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendFileToDebricked implements ShouldQueue
{
    use Queueable;

    protected $dependencyFile;

    /**
     * Create a new job instance.
     */
    public function __construct(DependencyFile $dependencyFile)
    {
        $this->dependencyFile = $dependencyFile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileContent = Storage::get($this->dependencyFile->path);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('debricked.api_token'),
            'Content-Type' => 'multipart/form-data'
        ])->post('https://debricked.com/api/1.0/open/uploads/dependencies/files', [
            'fileData' => $fileContent,
        ]);

        if ($response->failed()) {
            $this->dependencyFile->isFailed();
            $this->dependencyFile->remarks($response->body());
            Log::error('Failed to send file to Debricked: ' . $response->body());
        } else {
            $this->dependencyFile->isProcessing();
            $this->dependencyFile->remarks('File sent to Debricked successfully.');
            Log::info('File sent to Debricked successfully.');
        }
    }
}
