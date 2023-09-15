<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class ResourceAjaxValidationListener
{
    public function validate(ResourceControllerEvent $event): void
    {
        $form = $event->getSubject();

        Assert::isInstanceOf($form, FormInterface::class);

        $event->stopPropagation();

        $event->setResponse(
            new JsonResponse([
                'status' => 'error',
                'errors' => $this->getErrorMessages($form),
            ], Response::HTTP_UNPROCESSABLE_ENTITY),
        );
    }

    /**
     * @psalm-return array<int<0, max>|string, mixed>
     */
    private function getErrorMessages(FormInterface $form): array
    {
        $errors = [];

        /** @var FormError $error */
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
