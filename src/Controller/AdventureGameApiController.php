<?php

namespace App\Controller;

use App\AdventureGame\Game;
use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;
use App\Repository\AdventureItemsRepository;
use App\Repository\AdventureRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdventureGameApiController extends AdventureGameController
{
    #[Route('/proj/api', name: 'proj_api')]
    public function projectApi(): Response
    {
        return $this->render('adventure/api.html.twig');
    }
}
