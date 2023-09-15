<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AjaxValidationSubscriber implements EventSubscriberInterface
{
    /**
     * @return string[]
     *
     * @psalm-return array{'form.post_submit': 'submit'}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'submit',
        ];
    }

    /**
     * @return JsonResponse|null
     */
    public function submit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (!$form->isValid()) {
            $event->stopPropagation();

            return new JsonResponse([
                'status' => 'Error',
                'message' => 'Error',
            ], 422);
        }
    }

    /**
     * @psalm-return array<int<0, max>|string, mixed>
     */
    private function getErrorMessages(Form $form): array
    {
        $errors = [];

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
