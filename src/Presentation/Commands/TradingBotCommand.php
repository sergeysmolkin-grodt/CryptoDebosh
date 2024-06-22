<?php

namespace App\Presentation\Commands;

use App\Application\Services\TradingBotService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TradingBotCommand extends Command
{
    protected static $defaultName = 'app:trading-bot';

    private TradingBotService $tradingBotService;

    public function __construct(TradingBotService $tradingBotService)
    {
        $this->tradingBotService = $tradingBotService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:trading-bot')
            ->setDescription('Runs the trading bot.')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The trading pair symbol')
            ->addArgument('investment', InputArgument::REQUIRED, 'The amount to invest')
            ->addOption('continuous', 'c', InputOption::VALUE_NONE, 'Run the bot continuously')
            ->addOption('strategy', null, InputOption::VALUE_REQUIRED, 'The strategy to use');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        $investment = (float) $input->getArgument('investment');
        $continuous = $input->getOption('continuous');
        $strategy = $input->getOption('strategy');

        if ($continuous) {
            $output->writeln('Starting the bot in continuous mode...');
            $this->tradingBotService->run($symbol, $investment, $strategy);
        } else {
            $output->writeln('Running a single trade...');
            $this->tradingBotService->trade($symbol, $investment, $strategy);
        }

        return Command::SUCCESS;
    }

}