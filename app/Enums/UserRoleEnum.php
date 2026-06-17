<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Admin = 'admin';
    case Company = 'company';
    case Student = 'student';
}
