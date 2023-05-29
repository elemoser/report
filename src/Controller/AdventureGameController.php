<?php

namespace App\Controller;

use App\AdventureGame\Game;
use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;
use App\Repository\AdventureItemsRepository;
use App\Repository\AdventureRoomRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdventureGameController extends AbstractController
{
    #[Route('/proj', name: 'proj_home')]
    public function projectHome(): Response
    {
        return $this->render('adventure/index.html.twig');
    }

    #[Route('/proj/init/game', name: 'proj_init_game')]
    public function projectInitGame(
        SessionInterface $session
    ): Response
    {
        $game = new Game();
        $session->set("adventure", $game);

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/play', name: 'proj_game')]
    public function projectPlay(
        AdventureRoomRepository $roomRepository,
        AdventureItemsRepository $itemsRepository,
        SessionInterface $session
    ): Response {
        $data = [
            "place" => "",
            "image" => "",
            "description" => "",
            "directions" => "",
            "basket" => []
        ];

        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        $currentRoom = $game->getCurrentRoom();

        $room = $roomRepository->findOneBy(['name' => $currentRoom]);

        if ($room) {
            $data["place"] = $room->getName();
            $data["image"] = $room->getImage();
            $data["description"] = 'You are at '.$room->getDescription().'.';
            $directions = [];
        }


        $items = $itemsRepository->findAll();

        if ($items) {
            $itemsInCurrentRoom = [];

            foreach ($items as $item) {
                $itemLocation = $item->getRoom();
                if ($itemLocation == $currentRoom) {
                    array_push($itemsInCurrentRoom, $item->getImage());
                }
            }

            $data["basket"] = $itemsInCurrentRoom;
        }


        return $this->render('adventure/game.html.twig', $data);
    }

    #[Route('/proj/game/input', name: 'proj_game_input', methods: 'POST')]
    public function projectGameInput(
        AdventureRoomRepository $roomRepository,
        AdventureItemsRepository $itemsRepository,
        SessionInterface $session,
        Request $request
    ): Response
    {
        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $inputStr = (string) $request->request->get('input');
        $input = explode(" ", $inputStr);
        
        if (in_array("go", $input)) {
            $game = $session->get("adventure");
            $game->goTo($input[1]);
            $session->set("adventure", $game);
        }

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function projectAbout(): Response
    {
        return $this->render('adventure/about.html.twig');
    }

    #[Route('/proj/api', name: 'proj_api')]
    public function projectApi(): Response
    {
        return $this->render('adventure/api.html.twig');
    }

    #[Route('/proj/recipe', name: 'proj_cheat')]
    public function projectCheat(): Response
    {
        return $this->render('adventure/recipe.html.twig');
    }
}
