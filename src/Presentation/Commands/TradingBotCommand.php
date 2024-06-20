<?php

namespace CryptoDebosh\Presentation\Commands;

use CryptoDebosh\Application\Services\TradingBotService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
            ->setName(self::$defaultName) // Установите имя команды явно
            ->setDescription('Runs the trading bot')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The trading pair symbol (e.g., BTCUSDT)')
            ->addArgument('investment', InputArgument::REQUIRED, 'The investment amount in USDT');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        $investment = $input->getArgument('investment');

        $this->tradingBotService->trade($symbol, $investment);
        $output->writeln('Trading bot executed for ' . $symbol);

        return Command::SUCCESS;
    }
}
