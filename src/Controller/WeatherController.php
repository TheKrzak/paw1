<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{name}', name: 'app_weather')]
    public function name(
        string $country,
        string $name,
        WeatherUtil $util,
    ): Response {
        $forecasts = $util->getWeatherForCountryAndCity($country, $name);

        return $this->render('weather/city.html.twig', [
            'location' => [
                'country' => $country,
                'name' => $name,
            ],
            'forecasts' => $forecasts,
        ]);
    }
}
