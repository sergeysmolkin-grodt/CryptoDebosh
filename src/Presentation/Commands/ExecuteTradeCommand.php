<?php

declare(strict_types=1);

namespace App\Presentation\Commands;


use App\Application\Services\TradeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteTradeCommand extends Command
{
    protected static $defaultName = 'app:execute-trade';
    private $tradeService;

    public function __construct(TradeService $tradeService)
    {
        $this->tradeService = $tradeService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Executes a trade on Binance.')
            ->setHelp('This command allows you to execute a trade on Binance.')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The symbol of the cryptocurrency.')
            ->addArgument('side', InputArgument::REQUIRED, 'The side of the trade (BUY or SELL).')
            ->addArgument('quantity', InputArgument::REQUIRED, 'The quantity to trade.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        $side = $input->getArgument('side');
        $quantity = $input->getArgument('quantity');

        $result = $this->tradeService->executeTrade($symbol, $side, $quantity);
        $output->writeln('Order executed: ' . json_encode($result));

        return Command::SUCCESS;
    }
}