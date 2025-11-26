<?php

namespace App\Observers;

use App\Models\Jasa;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;

class JasaObserver
{
    public function saved(Jasa $jasa): void
    {
        PollTriggerStore::bump([PollChannel::JASA, PollChannel::DASHBOARD]);
    }

    public function deleted(Jasa $jasa): void
    {
        PollTriggerStore::bump([PollChannel::JASA, PollChannel::DASHBOARD]);
    }
}

