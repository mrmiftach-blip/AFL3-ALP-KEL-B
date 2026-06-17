<?php

namespace App\Enums;

enum ApplicationStatusEnum: string
{
    case Submitted = 'Submitted';
    case Reviewed = 'Reviewed';
    case Accepted = 'Accepted';
    case Rejected = 'Rejected';
}
