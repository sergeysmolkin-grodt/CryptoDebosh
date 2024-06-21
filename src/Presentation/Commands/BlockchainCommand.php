<?php

namespace CryptoDebosh\Presentation\Commands;

use CryptoDebosh\Application\Services\BlockchainService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlockchainCommand extends Command
{

    protected static $defaultName = 'app:blockchain';
    private $blockchainService;

    public function __construct(BlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Interacts with the blockchain.')
            ->addArgument('action', InputArgument::REQUIRED, 'The action to perform (add, validate, show)')
            ->addArgument('data', InputArgument::OPTIONAL, 'The data for the new block');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = $input->getArgument('action');

        switch ($action) {
            case 'add':
                $data = $input->getArgument('data');
                if ($data) {
                    $this->blockchainService->addNewBlock($data);
                    $output->writeln('Block added with data: ' . $data);
                } else {
                    $output->writeln('Data argument is required to add a new block.');
                }
                break;

            case 'validate':
                $isValid = $this->blockchainService->isBlockchainValid();
                $output->writeln('Blockchain is ' . ($isValid ? 'valid' : 'invalid'));
                break;

            case 'show':
                $chain = $this->blockchainService->getBlockchain();
                foreach ($chain as $block) {
                    $output->writeln('Block ' . $block->index . ': ' . json_encode($block));
                }
                break;

            default:
                $output->writeln('Invalid action. Use add, validate, or show.');
                break;
        }

        return Command::SUCCESS;
    }
}
