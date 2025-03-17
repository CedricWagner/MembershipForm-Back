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
use Symfony\Component\Filesystem\Filesystem;

use function PHPUnit\Framework\fileExists;

#[AsCommand(
    name: 'app:csv:import',
    description: 'Import from CSV',
)]
class CSVImportCommand extends Command
{

    public function __construct(private MemberFactory $memberFactory)
    {
        parent::__construct(self::getDefaultName());
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Relative path of the CSV file to import')
            ->addArgument('ignore-first-line', InputArgument::OPTIONAL, 'Ignore first line', TRUE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');
        $filesystem = new Filesystem();

        if (!$filesystem->exists($filePath)) {
            throw new \Exception(sprintf('No file found at path: %s', $filePath));
        }

        $rows = array();
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                if ($input->getArgument('ignore-first-line') && $i == 1) { continue; }
                $rows[] = $data;
            }
            fclose($handle);
        }
        
        $io->progressStart(count($rows));
        foreach ($rows as $row) {
            try {
                $this->handleCSVRow($row);
                $io->progressAdvance();
            } catch (\Exception $e) {
                $io->error($e->getMessage());
            }
        }
        $io->progressFinish();

        $io->success(sprintf('%s memberships imported', count($rows)));

        return Command::SUCCESS;
    }

    /**
     * Store data from CSV row.
     * 
     * Create or update item based on member num.
     */
    protected function handleCSVRow($row) {
        $this->memberFactory->createOrUpdateMemberFromCSVValues(
            $row[0], // $num
            $row[2], // $firstname
            $row[3], // $lastname
            $row[4], // $email
            $row[5], // $amount
            $row[1], // $date
            $row[6], // $paymentMethod
            $row[7], // $willingToVolunteer
            $row[8], // $subscribedToNewsletter
        );
    }
}