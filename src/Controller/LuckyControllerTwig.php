<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $random_number = random_int(0, 9);

        $numbers = [
            "ZERO",
            "ONE",
            "TWO",
            "THREE",
            "FOUR",
            "FIVE",
            "SIX",
            "SEVEN",
            "EIGHT",
            "NINE"
        ];

        $gifs = [
            '0' => "https://giphy.com/embed/26gs9hWZig4XobSTe",
            '1' => "https://giphy.com/embed/l0ExncehJzexFpRHq",
            '2' => "https://giphy.com/embed/26gsqQxPQXHBiBEUU",
            '3' => "https://giphy.com/embed/l0EwYkyU1JCExVquc",
            '4' => "https://giphy.com/embed/d1E1szXDsHUs3WvK",
            '5' => "https://giphy.com/embed/l0ExvMqtnw7aTzPCE",
            '6' => "https://giphy.com/embed/l0Ex9pftnvPgw0nPa",
            '7' => "https://giphy.com/embed/l0ExiSoCkhCfSm94k",
            '8' => "https://giphy.com/embed/26gsasKHkeH0VP8d2",
            '9' => "https://giphy.com/embed/26gsjCWitFy3euTeM"
        ];

        $gif = $gifs[strval($random_number)];

        $data = [
            "number" => $numbers[$random_number],
            "gif" => $gif
        ];

        return $this->render('lucky.html.twig', $data);
    }

    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/api/quote", name: "api_quote")]
    public function quotes(): Response
    {
        date_default_timezone_set('Europe/Stockholm');
        $today = date('l M j Y, G:i:s');
        $quotes = [
            "We delight in the beauty of the butterfly, but rarely admit the changes it has gone through to achieve that beauty. (Maya Angelou)",
            "To live is the rarest thing in the world. Most people exist, that is all. (Oscar Wilde)",
            "Do one thing every day that scares you. (Eleanor Roosevelt)",
            "Happiness is not something ready made. It comes from your own actions. (Dalai Lama XIV)"
        ];
        $rand_quote = array_rand($quotes, 1);

        $data = [
            'dagens-datum' => $today,
            'citat' => $quotes[$rand_quote],
        ];

        // return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;

        //return $this->render('api_quote.html.twig');
    }
}
