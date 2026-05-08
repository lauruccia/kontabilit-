<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\Quote;

class NumberGenerator
{
    public function quote(): string
    {
        return 'PREV-'.now()->format('Y').'-'.str_pad((string) (Quote::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT);
    }

    public function contract(): string
    {
        return 'CTR-'.now()->format('Y').'-'.str_pad((string) (Contract::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT);
    }

    public function payment(): string
    {
        return 'PAY-'.now()->format('Y').'-'.str_pad((string) (Payment::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT);
    }
}
