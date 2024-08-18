<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependencyFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'status',
        'vulnerabilities',
        'remarks',
    ];
    
    public function isProcessing(): void
    {
        $this->status = 'processing';
        $this->save();
    }

    public function isFailed(): void
    {
        $this->status = 'failed';
        $this->save();
    }

    public function remarks(string $remarks): void
    {
        $this->remarks = $remarks;
        $this->save();
    }
}
