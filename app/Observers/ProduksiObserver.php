<?php

namespace App\Observers;

use App\Models\Produksi;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;

class ProduksiObserver
{
    public function saved(Produksi $produksi): void
    {
        PollTriggerStore::bump([PollChannel::PRODUKSI, PollChannel::DASHBOARD]);
    }

    public function deleted(Produksi $produksi): void
    {
        PollTriggerStore::bump([PollChannel::PRODUKSI, PollChannel::DASHBOARD]);
    }
}

