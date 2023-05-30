<?php

namespace App\Controller;

use App\AdventureGame\Game;
use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;
use App\Repository\AdventureItemsRepository;
use App\Repository\AdventureRoomRepository;
// use Doctrine\Persistence\ManagerRegistry;
// use phpDocumentor\Reflection\DocBlock\Tags\Var_;
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
        AdventureRoomRepository $roomRepository,
        AdventureItemsRepository $itemsRepository,
        SessionInterface $session
    ): Response
    {
        $start = $roomRepository->findOneBy(['name' => 'kitchen']);
        // $items = $itemsRepository->findBy(['room' => 'kitchen']);

        if(!$start) {
            return new Response('There seems to be an issue with the database.');
        }

        $game = new Game($start, []);
        $session->set("adventure", $game);
        $session->set("action", "");

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
            "action" => "",
            "options" => [],
            "basket" => []
        ];

        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        $currentRoom = $game->getCurrentRoom();

        if ($currentRoom) {
            $data["place"] = $currentRoom->getName();
            $data["image"] = $currentRoom->getImage();
            $data["description"] = 'You are at '.$currentRoom->getDescription().'.';
            $data['directions'] = $game->getDirectionsAsString($currentRoom);
            $data['options'] = $game->getActions();
        }

        if ($session->get("action")) {
            $data["action"] = $session->get("action");
        }

        $items = $itemsRepository->findAll();

        if ($items) {
            $itemsInCurrentRoom = [];

            foreach ($items as $item) {
                $itemLocation = $item->getRoom();
                if ($itemLocation == $currentRoom->getName()) {
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

        $game = $session->get("adventure");
        $action = (string) $request->request->get('action');
        $input = (string) $request->request->get('input');
        $cleanedInput = trim($input);
        // $input = explode(" ", $inputStr);
        $result = "";

        if ($action == "inspect") {
            $result = $game->inspect($cleanedInput);
        }

        if ($action == "pick up") {
            // pick up object
        }

        if ($action == "put back") {
            // put down object
        }

        if ($action == "go") {
            $locationAtDirection = $game->getLocationOfDirection($cleanedInput);
            if ($locationAtDirection) {
                $newPlace = $roomRepository->findOneBy(['name' => $locationAtDirection]);
                $items = $itemsRepository->findBy(['room' => $locationAtDirection]);
                $game->setRoomTo($newPlace, $items);
            }
        }

        $session->set("adventure", $game);
        $session->set("action", $result);

        return $this->redirectToRoute('proj_game');
        // return new Response("Input: ".$action);
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
