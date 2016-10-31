<?php

namespace WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Endroid\OpenWeatherMap\Client;

class WeatherController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        return $this->render('WeatherBundle:Default:index.html.twig');
    }

    /**
     * @Route("{city}")
     */
    public function weatherAction($city)
    {

        /** @var Client $client */
        $client = $this->get('endroid.openweathermap.client');
        try {
            $weather = $client->getWeather($city);
            return $this->render('WeatherBundle:Default:weather.html.twig', [
                'city' => $city,
                'temperature' => $weather->main->temp
            ]);
        } catch (\Exception $e) {
            return $this->render('WeatherBundle:Default:error.html.twig',[]);
        }

    }
}