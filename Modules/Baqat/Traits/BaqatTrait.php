<?php

namespace Modules\Baqat\Traits;

use Modules\Baqat\Entities\BaqatSubscription;

trait BaqatTrait
{
    public function getUserLastSubscription($userId)
    {
        return BaqatSubscription::with(['baqa', 'user'])->where('user_id', $userId)->unexpired()->successSubscriptions()->first();
    }
}
