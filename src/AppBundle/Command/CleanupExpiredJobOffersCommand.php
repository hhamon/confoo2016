<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanupExpiredJobOffersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:jobs:cleanup')
            ->setDescription('Cleanups expired jobs offers.')
            ->addArgument('company', InputArgument::OPTIONAL, 'The company name')
            ->addOption('days', 'd', InputOption::VALUE_REQUIRED, 'Number of days', 20)
            ->setHelp(<<<HELP
This is how to execute this command:

    $ php bin/console app:jobs:cleanup

Or with a company name:

    $ php bin/console app:jobs:cleanup SensioLabs --days=35

HELP
)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $style->caution('This command will delete some information!');

        $cleaner = $this->getContainer()->get('app.job_offer_cleaner');
        $nbOffers = $cleaner->cleanup(
            $input->getArgument('company'),
            (int) $input->getOption('days')
        );

        $style->success(sprintf('%u job offers have been cleaned up!', $nbOffers));
    }
}
