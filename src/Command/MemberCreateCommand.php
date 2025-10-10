<?php

namespace App\Command;

use App\Service\MemberFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:member:create',
    description: 'Prompts to create a new member',
)]
class MemberCreateCommand extends Command
{
    public function __construct(protected MemberFactory $memberFactory)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the member')
            ->addOption('firstname', null, InputOption::VALUE_REQUIRED, 'First name of the member')
            ->addOption('lastname', null, InputOption::VALUE_REQUIRED, 'Last name of the member')
            ->addOption('subscribe', null, InputOption::VALUE_NONE, 'Subscribe the member to the newsletter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $firstname = $input->getOption('firstname');
        $lastname = $input->getOption('lastname');
        $subscribeNewsletter = $input->getOption('subscribe');

        if ($email && $firstname && $lastname && isset($subscribeNewsletter)) {
            $member = $this->memberFactory->createMember(
                $firstname,
                $lastname,
                $email,
                0,
                new \DateTime(),
                NULL,
                FALSE, // willing to volunteer
                $subscribeNewsletter ? TRUE : FALSE // subscribed to newsletter
            );
            $io->success(sprintf('Member created: %s %s (%s)', $member->getFirstname(), $member->getLastname(), $member->getEmail()));
            return Command::SUCCESS;
        }

        $io->error('You must provide all the required arguments and options.');
        return Command::FAILURE;
    }
}
