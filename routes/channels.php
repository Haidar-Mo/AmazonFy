<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('support.1', function ($user) {
    return true;
});