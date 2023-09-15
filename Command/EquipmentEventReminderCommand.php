<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Command;

use SyliusLabs\Polyfill\Symfony\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @final
 */
class EquipmentEventReminderCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'owl:equipment-event-reminder';

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Equipment event reminder',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf(
            'Command will send email to owner and admin company with data to equipment event ',
        ));

        $equipmentEventReminder = $this->getContainer()->get('owl.equipment_event_reminder');
        $equipmentEventReminder->remind();

        $this->getContainer()->get('owl.manager.equipment_event')->flush();

        return 0;
    }
}
