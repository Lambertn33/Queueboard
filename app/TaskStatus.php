<?php

namespace App;

enum TaskStatus: string
{
    case Todo = 'to_do';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Overdue = 'overdue';
}
