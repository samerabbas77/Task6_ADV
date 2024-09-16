<?php

namespace App\Enum\Tasks;

enum TaskPriority:string
{
    case Hight = 'hight';
    case Medium = 'medium';
    case Low = 'low';
}