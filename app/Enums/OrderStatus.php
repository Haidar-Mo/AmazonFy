<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CHECKING = 'checking';
    case PREPARING = 'preparing';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';
}
