<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Event;

interface RegistrationEvents
{
    public const POST_CHANGE_STATUS_ACCEPTED = 'owl.registration.post_change_status_accepted';

    public const POST_CHANGE_STATUS_REJECTED = 'owl.registration.post_change_status_rejected';
}
