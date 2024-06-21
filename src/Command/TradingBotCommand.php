<?php

namespace CryptoDebosh\Presentation\Commands;

use CryptoDebosh\Application\Factories\TradingStrategyFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TradingBotCommand extends Command
{
    protected static $defaultName = 'app:trading-bot';

    private $tradingStrategyFactory;

    public function __construct(TradingStrategyFactory $tradingStrategyFactory)
    {
        $this->tradingStrategyFactory = $tradingStrategyFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Run the trading bot.')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The trading symbol, e.g., BTCUSDT')
            ->addArgument('investment', InputArgument::REQUIRED, 'The amount to invest in USDT')
            ->addOption('continuous', 'c', InputOption::VALUE_NONE, 'Run the bot continuously')
            ->addOption('strategy', 's', InputOption::VALUE_REQUIRED, 'The trading strategy to use', 'moving_average');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        $investment = $input->getArgument('investment');
        $continuous = $input->getOption('continuous');
        $strategy = $input->getOption('strategy');

        $tradingBotService = $this->tradingStrategyFactory->create($strategy, $symbol, $investment);

        if ($continuous) {
            $output->writeln('Starting the bot in continuous mode...');
            $tradingBotService->run($symbol, $investment);
        } else {
            $output->writeln('Running a single trade...');
            $tradingBotService->trade($symbol, $investment);
        }

        return Command::SUCCESS;
    }
}
