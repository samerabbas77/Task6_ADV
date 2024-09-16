<?php

namespace App\Enums\User;

enum ProjectRole: string {
    case Manager = 'manager';
    case Developer = 'developer';
    case Tester = 'tester';
}