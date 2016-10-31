<?php

namespace NFQ\WeatherBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WeatherController extends Controller
{
    /**
     * @Route("/weather")
     */
    public function defaultAction()
    {
        return $this->render('NFQWeatherBundle:Default:index.html.twig');
    }

    /**
     * @Route("/weather/{city}")
     */
    public function weatherAction($city)
    {
        $api_key = "76513f6fc6bc0fd823e0e658eb7c5ecb";
        $api_call_url = "api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $api_key;

        //guzzle request
        $client = new \GuzzleHttp\Client();
        $response = $client ->get($api_call_url);
        $data = json_decode($response->getBody());

        //checking if city is found
        if ($data->cod == 502)
            return $this->render('NFQWeatherBundle:Default:fail.html.twig');

        //extracting data from json
        $temp = $data->main->temp;
        $name = $data->name;

        return $this->render('NFQWeatherBundle:Default:weather.html.twig', [
            'city' => $city,
            'temp' => $temp,
            'name' => $name
        ]);
    }
}
