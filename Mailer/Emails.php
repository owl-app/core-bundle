<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Mailer;

interface Emails
{
    public const USER_REGISTRATION = 'user_registration';

    public const PASSWORD_RESET = 'password_reset';

    public const ACCOUNT_VERIFICATION_TOKEN = 'account_verification_token';

    public const REGISTRATION_ACCEPTED = 'registration_accepted';

    public const REGISTRATION_REJECTED = 'registration_rejected';

    public const EMAIL_EQUIPMENT_EVENT_NOTIFY = 'equipment_event_notify';
}
