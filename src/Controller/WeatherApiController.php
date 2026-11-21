<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter] bool $twig = false,
        WeatherUtil $weatherUtil = null,
    ): Response {
        $forecasts = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $forecasts,
                ], new Response('', 200, ['Content-Type' => 'text/plain']));
            }

            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'forecasts' => $forecasts,
            ], new Response('', 200, ['Content-Type' => 'application/json']));
        }

        if ($format === 'csv') {
            $rows = ['city,country,date,celsius,fahrenheit'];

            foreach ($forecasts as $f) {
                $rows[] = sprintf(
                    '%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $f->getDate()->format('Y-m-d'),
                    $f->getTemperature(),
                    $f->getFahrenheit()
                );
            }

            return new Response(implode("\n", $rows), 200, ['Content-Type' => 'text/plain']);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Forecast $f) => [
                'date' => $f->getDate()->format('Y-m-d'),
                'celsius' => $f->getTemperature(),
                'fahrenheit' => $f->getFahrenheit(),
            ], $forecasts),
        ]);
    }
}
