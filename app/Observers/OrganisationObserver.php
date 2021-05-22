<?php

namespace App\Observers;

use App\Notifications\OrganisationCreateNotification;
use App\Organisation;

class OrganisationObserver
{

    /**
     * Notify organisation Owner about creating one
     * @param Organisation $organisation
     */
    public function created(Organisation $organisation): void
    {
        $organisation->owner->notify(new OrganisationCreateNotification($organisation));
    }
}
