<?php

namespace CryptoDebosh\Presentation\Commands;

use CryptoDebosh\Application\Services\TradingBotService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TradingBotCommand extends Command
{
    protected static $defaultName = 'app:trading-bot';

    private $tradingBotService;

    public function __construct(TradingBotService $tradingBotService)
    {
        $this->tradingBotService = $tradingBotService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Run the trading bot.')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The trading symbol, e.g., BTCUSDT')
            ->addArgument('investment', InputArgument::REQUIRED, 'The amount to invest in USDT')
            ->addOption('continuous', 'c', InputOption::VALUE_NONE, 'Run the bot continuously');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        $investment = $input->getArgument('investment');
        $continuous = $input->getOption('continuous');

        if ($continuous) {
            $output->writeln('Starting the bot in continuous mode...');
            $this->tradingBotService->run($symbol, $investment);
        } else {
            $output->writeln('Running a single trade...');
            $this->tradingBotService->trade($symbol, $investment);
        }

        return Command::SUCCESS;
    }
}
