<?php

namespace App\Presentation\Commands;

use App\Infrastructure\Services\External\ArbitrageService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArbitrageBotCommand extends Command
{
    protected static $defaultName = 'app:arbitrage-bot';

    private ArbitrageService $arbitrageService;

    public function __construct(ArbitrageService $arbitrageService)
    {
        $this->arbitrageService = $arbitrageService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:arbitrage-bot')
            ->setDescription('Runs the arbitrage bot.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting the arbitrage bot...');

        while (true) {
            $opportunities = $this->arbitrageService->findArbitrageOpportunities($output);

            if (empty($opportunities)) {
                $output->writeln('No arbitrage opportunities found.');
            } else {
                foreach ($opportunities as $opportunity) {
                    $this->arbitrageService->executeArbitrage($opportunity);
                    $output->writeln('Executed arbitrage for opportunity: ' . json_encode($opportunity));
                }
            }


            sleep(5);
        }

        return Command::SUCCESS;
    }
}