<?php

namespace App\Observers;

use App\Models\Team;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;

class TeamObserver
{
    public function saved(Team $team): void
    {
        PollTriggerStore::bump(PollChannel::DASHBOARD);
    }

    public function deleted(Team $team): void
    {
        PollTriggerStore::bump(PollChannel::DASHBOARD);
    }
}

