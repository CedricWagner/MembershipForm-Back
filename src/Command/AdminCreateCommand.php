<?php

namespace App\Command;

use App\Service\AdminFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:admin:create',
    description: 'Create a new administrator',
)]
class AdminCreateCommand extends Command
{
    public function __construct(private AdminFactory $adminFactory)
    {
        parent::__construct(self::getDefaultName());
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Administrator email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        if (!$email) {
            $email = $io->ask('Enter email:', null, function ($value) {
                if ('' === trim($value)) {
                    throw new \Exception('The email cannot be empty');
                }

                return $value;
            });
        }

        $password = $io->askHidden('Enter password:', function ($value) {
            if ('' === trim($value)) {
                throw new \Exception('The password cannot be empty');
            }

            return $value;
        });

        $admin = $this->adminFactory->createFromEmailAndRawPassword($email, $password);

        $io->success(sprintf('New administrator created (%s) ! You can now log in.', $admin->getEmail()));

        return Command::SUCCESS;
    }
}
