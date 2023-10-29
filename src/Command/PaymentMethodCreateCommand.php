<?php

namespace App\Command;

use App\Service\PaymentMethodFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:pm:create',
    description: 'Create a new payment method',
)]
class PaymentMethodCreateCommand extends Command
{
    public function __construct(private PaymentMethodFactory $pmFactory)
    {
        parent::__construct(self::getDefaultName());
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Payment method name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        if (!$name) {
            $name = $io->ask('Name:', null, function ($value) {
                if ('' === trim($value)) {
                    throw new \Exception('The name cannot be empty');
                }

                return $value;
            });
        }

        $pm = $this->pmFactory->createFromName($name);

        $io->success(sprintf('New payment method created (%s) !', $pm->getName()));

        return Command::SUCCESS;
    }
}
