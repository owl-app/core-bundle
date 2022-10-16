<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Doctrine\ORM\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Owl\Component\Core\Model\Rbac\Role;

final class LoadMetadataSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            'loadClassMetadata',
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArguments): void
    {
        $metadata = $eventArguments->getClassMetadata();

        if ($metadata->getName() != 'Owl\\Component\\Rbac\\Model\\AuthItem') {
            return;
        }

        $discriminatorMap = $metadata->discriminatorMap;
        $discriminatorMap['role'] = Role::class;

        $metadata->setDiscriminatorMap($discriminatorMap);
    }
}
