<?php

namespace App\Enums;

enum RoleName: string
{
    case Admin = 'ADMIN';
    case Member = 'MEMBER';
    case ProjectLead = 'PROJECT_LEAD';
}
