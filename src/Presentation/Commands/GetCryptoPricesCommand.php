<?php

declare(strict_types=1);

namespace CryptoDebosh\Presentation\Commands;

use CryptoDebosh\Application\Services\CryptoPriceService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GetCryptoPricesCommand extends Command
{
    protected static $defaultName = 'app:get-crypto-prices';
    private $cryptoPriceService;

    public function __construct(CryptoPriceService $cryptoPriceService)
    {
        $this->cryptoPriceService = $cryptoPriceService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Gets the current prices of cryptocurrencies.')
            ->setHelp('This command allows you to get the current prices of cryptocurrencies from Binance.');
    }

    protected function execute(InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output): int
    {
        $prices = $this->cryptoPriceService->getCryptoPrices();
        foreach ($prices as $price) {
            $output->writeln($price->getSymbol() . ': ' . $price->getPrice());
        }

        return Command::SUCCESS;
    }
}