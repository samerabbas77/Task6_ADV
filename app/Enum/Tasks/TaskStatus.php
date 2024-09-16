<?php
namespace App\Enum\Tasks;

use PhpParser\Node\Stmt\Break_;

enum TaskStatus: string
{
    case New = 'new';
    case In_Progress = 'in_progress';
    case Done = 'done';
}