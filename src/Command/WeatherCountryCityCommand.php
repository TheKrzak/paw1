<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:country-city',
    description: 'Add a short description for your command',
)]
class WeatherCountryCityCommand extends Command
{
    public function __construct(
        private WeatherUtil $weatherUtil,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED)
            ->addArgument('city', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        $forecasts = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        $io->writeln(sprintf('Location: %s, %s', $city, $country));
        $io->table(['Date', 'Temperature'], array_map(fn($f) => [
            $f->getDate()->format('Y-m-d'),
            $f->getTemperature(),
        ], $forecasts));

        return Command::SUCCESS;
    }
}
