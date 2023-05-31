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
        SessionInterface $session
    ): Response {
        $start = $roomRepository->findOneBy(['name' => 'kitchen']);
        // $items = $itemsRepository->findBy(['room' => 'kitchen']);

        if(!$start) {
            return new Response('There seems to be an issue with the database.');
        }

        $game = new Game($start, []);
        $session->set("adventure", $game);
        $session->set("action", "");
        $session->set("cake", "");

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/play', name: 'proj_game')]
    public function projectPlay(
        SessionInterface $session
    ): Response {
        $data = [
            "place" => "",
            "image" => "",
            "description" => "",
            "directions" => "",
            "action" => "",
            "options" => [],
            "basket" => [],
            "gameEnd" => ""
        ];

        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        assert($game instanceof Game);
        $currentRoom = $game->getCurrentRoom();

        assert($currentRoom instanceof AdventureRoom);
        $data["place"] = $currentRoom->getName();
        $data["image"] = $currentRoom->getImage();
        $data["description"] = 'You are at '.$currentRoom->getDescription().'.';
        $data['directions'] = $game->getDirectionsAsString();
        $data['options'] = $game->getActions();

        if ($session->get("action")) {
            $data["action"] = $session->get("action");
        }

        $basket = $game->getBasket();

        if (!empty($basket)) {
            foreach ($basket as $item) {
                assert($item instanceof AdventureItems);
                array_push($data["basket"], $item->getImage());
            }
        }

        if ($session->get("cake")) {
            $data["gameEnd"] = $session->get("cake");
        }

        return $this->render('adventure/game.html.twig', $data);
    }

    #[Route('/proj/game/input', name: 'proj_game_input', methods: 'POST')]
    public function projectGameInput(
        SessionInterface $session,
        Request $request
    ): Response {
        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        assert($game instanceof Game);
        $action = (string) $request->request->get('action');
        $input = (string) $request->request->get('input');
        $cleanedInput = strtolower(trim($input));
        $result = "";

        if ($action == "inspect") {
            $result = $game->inspect($cleanedInput);
        }

        if ($action == "pick up") {
            return $this->redirectToRoute('proj_game_pickup', ['input' => $cleanedInput]);
        }

        if ($action == "put back") {
            return $this->redirectToRoute('proj_game_putdown', ['input' => $cleanedInput]);

        }

        if ($action == "go") {
            return $this->redirectToRoute('proj_game_go', ['input' => $cleanedInput]);
        }

        if ($action == "bake") {
            return $this->redirectToRoute('proj_game_bake', ['input' => $cleanedInput]);

        }

        $session->set("adventure", $game);
        $session->set("action", $result);

        return $this->redirectToRoute('proj_game');
        // return new Response("Input: ".$action);
    }

    #[Route('/proj/game/bake/{input}', name: 'proj_game_bake', methods: 'GET')]
    public function projectGameBake(
        SessionInterface $session,
        string $input
    ): Response {
        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        assert($game instanceof Game);
        $currentRoom = $game->getCurrentRoom();
        assert($currentRoom instanceof AdventureRoom);
        $result = "";

        $completeRecipe = $game->checkIngredients();
        $result = "You cannot bake anything with the ingredients you have in your basket.";
        $alternatives = ["cake", "jordgubbstårta", "strawberry cake"];
        if (in_array($input, $alternatives)) {
            $result = "You cannot bake the ".$input." yet since you are missing some ingredients.";
        }
        if ($completeRecipe) {
            $result = "You gathered all the ingredients to bake the cake. ";
            $result .= "Go back to the kitchen to start baking.";
            if ($currentRoom->getName() == "kitchen") {
                $result = "Your jordgubbstårta is done. Great job! Just in time... ";
                $session->set("cake", "done");
            }
        }

        $session->set("adventure", $game);
        $session->set("action", $result);

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/game/go/{input}', name: 'proj_game_go', methods: 'GET')]
    public function projectGameGo(
        AdventureRoomRepository $roomRepository,
        AdventureItemsRepository $itemsRepository,
        SessionInterface $session,
        string $input
    ): Response {
        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        assert($game instanceof Game);
        $result = "";

        $locationAtDirection = $game->getLocationOfDirection($input);
        if ($locationAtDirection) {
            $newPlace = $roomRepository->findOneBy(['name' => $locationAtDirection]);
            $items = $itemsRepository->findBy(['room' => $locationAtDirection]);
            if ($newPlace and $items) {
                $game->setRoomTo($newPlace, $items);
            }
        }

        $session->set("adventure", $game);
        $session->set("action", $result);

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/game/pickup/{input}', name: 'proj_game_pickup', methods: 'GET')]
    public function projectGamePickup(
        AdventureItemsRepository $itemsRepository,
        SessionInterface $session,
        string $input
    ): Response {
        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        assert($game instanceof Game);
        $result = "";

        $items = $game->getCurrentRoomItems();
        $result = "You look around, but there is no ".$input." to pick up in this place. ";
        $result .= "You must be a bit tired. But no time to take a nap; you will bake this cake first!";
        if (in_array($input, $items)) {
            $itemToPickUp = $itemsRepository->findOneBy(['name' => $input]);
            if ($itemToPickUp) {
                $result = $game->pickUp($itemToPickUp);
            }
        }

        $session->set("adventure", $game);
        $session->set("action", $result);

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/game/putdown/{input}', name: 'proj_game_putdown', methods: 'GET')]
    public function projectGamPutDown(
        AdventureItemsRepository $itemsRepository,
        SessionInterface $session,
        string $input
    ): Response {
        if (!$session->get("adventure")) {
            return $this->redirectToRoute('proj_init_game');
        }

        $game = $session->get("adventure");
        assert($game instanceof Game);
        $result = "";

        $itemIsInBasket = $game->checkItemInBasket($input);
        $result = "You reach into your basket to put back the ".$input;
        $result .= ", but there is no ".$input.". Hmm... Anyways, you must focus now on the task at hand!";
        if ($itemIsInBasket) {
            $itemToPutDown = $itemsRepository->findOneBy(['name' => $input]);
            if ($itemToPutDown) {
                $result = $game->putDown($itemToPutDown);
            }
        }

        $session->set("adventure", $game);
        $session->set("action", $result);

        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function projectAbout(): Response
    {
        return $this->render('adventure/about.html.twig');
    }

    #[Route('/proj/recipe', name: 'proj_cheat')]
    public function projectCheat(): Response
    {
        return $this->render('adventure/recipe.html.twig');
    }
}
