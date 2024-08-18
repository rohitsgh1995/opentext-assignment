<?php

namespace App\Services;

use App\Models\DependencyFile;
use App\Jobs\SendFileToDebricked;

class DebrickedService
{
    public function forwardToDebricked(array $files): void
    {
        foreach ($files as $file) {
            $dependencyFile = DependencyFile::find($file->id);
            $dependencyFile->isProcessing();

            SendFileToDebricked::dispatch($dependencyFile);
        }
    }
}
