<?php

declare(strict_types=1);

namespace App\Presentation\Commands;


use App\Domain\Services\CryptoAssetService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchMarketDataCommand extends Command
{
    protected static $defaultName = 'app:fetch-market-data';

    private $binanceService;
    private $cryptoAssetService;

    public function __construct(BinanceService $binanceService, CryptoAssetService $cryptoAssetService)
    {
        $this->binanceService = $binanceService;
        $this->cryptoAssetService = $cryptoAssetService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetch market data from Binance and save to database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->binanceService->getHistoricalData('BTCUSDT', '1h', strtotime('-1 week') * 1000);
        $this->cryptoAssetService->saveAssetData($data);

        $output->writeln('Market data fetched and saved successfully.');

        return Command::SUCCESS;
    }
}
