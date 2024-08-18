<?php

namespace App\Services;

use App\Models\DependencyFile;
use App\Jobs\SendFileToDebricked;

class DebrickedService
{
    public function forwardToDebricked(int $fileId): void
    {
        $dependencyFile = DependencyFile::find($fileId);
        $dependencyFile->isProcessing();

        SendFileToDebricked::dispatch($dependencyFile);
    }
}
