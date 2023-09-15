<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Owl\Bundle\CoreBundle\Mailer\Emails as CoreBundleEmails;
use Owl\Bundle\UserBundle\Mailer\Emails as UserBundleEmails;
use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\AdminUserRegistrationDataInterface;
use Owl\Component\User\Model\UserInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class MailerListener
{
    public function __construct(
        private SenderInterface $emailSender,
    ) {
    }

    public function sendResetPasswordTokenEmail(GenericEvent $event): void
    {
        $this->sendEmail($event->getSubject(), UserBundleEmails::RESET_PASSWORD_TOKEN);
    }

    public function sendResetPasswordPinEmail(GenericEvent $event): void
    {
        $this->sendEmail($event->getSubject(), UserBundleEmails::RESET_PASSWORD_PIN);
    }

    public function sendVerificationTokenEmail(GenericEvent $event): void
    {
        $this->sendEmail($event->getSubject(), UserBundleEmails::EMAIL_VERIFICATION_TOKEN);
    }

    public function sendUserRegistrationEmail(GenericEvent $event): void
    {
        $user = $event->getSubject();

        Assert::isInstanceOf($user, AdminUserInterface::class);

        $email = $user->getEmail();
        if (empty($email)) {
            return;
        }

        $this->sendEmail($user, CoreBundleEmails::USER_REGISTRATION);
    }

    public function sendUserRegistrationAccepted(GenericEvent $event): void
    {
        $this->sendEmail($this->getUserFromRegistrationData($event), CoreBundleEmails::REGISTRATION_ACCEPTED);
    }

    public function sendUserRegistrationRejected(GenericEvent $event): void
    {
        $this->sendEmail($this->getUserFromRegistrationData($event), CoreBundleEmails::REGISTRATION_REJECTED);
    }

    private function sendEmail(UserInterface $user, string $emailCode): void
    {
        $this->emailSender->send(
            $emailCode,
            [$user->getEmail()],
            [
                'user' => $user,
            ],
        );
    }

    private function getUserFromRegistrationData(GenericEvent $event): AdminUserInterface
    {
        $registrationData = $event->getSubject();
        Assert::isInstanceOf($registrationData, AdminUserRegistrationDataInterface::class);

        $user = $registrationData->getUser();
        Assert::isInstanceOf($user, AdminUserInterface::class);

        return $user;
    }
}
