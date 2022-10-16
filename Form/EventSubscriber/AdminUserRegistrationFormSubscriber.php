<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\EventSubscriber;

use Owl\Component\Core\Model\AdminUserInterface;
use Owl\Component\Core\Model\AdminUserRegistrationDataInterface;
use Owl\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

final class AdminUserRegistrationFormSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $classAdminUserRegistrationData, private GeneratorInterface $tokenGenerator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function preSubmit(FormEvent $event): void
    {
        $rawData = $event->getData();
        $form = $event->getForm();
        $data = $form->getData();

        Assert::isInstanceOf($data, AdminUserInterface::class);

        $data->setRegistration($this->copyDataToRegistration($rawData));

        $token = $this->tokenGenerator->generate();
        $data->setEmailVerificationToken($token);
        $data->setDisplayName($rawData['firstName'].' '.$rawData['lastName']);

        $form->setData($data);
    }

    private function copyDataToRegistration(array $data): AdminUserRegistrationDataInterface
    {
        $registration = new $this->classAdminUserRegistrationData();

        $registration->setFirstName($data['firstName']);
        $registration->setLastName($data['lastName']);
        $registration->setPhone($data['phone']);
        $registration->setEmail($data['email']);

        return $registration;
    }
}
