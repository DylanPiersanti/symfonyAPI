<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Game;

class GameController extends AbstractController
{

    /**
     * @Route("/games", name="games")
     */
    public function getEditors()
    {
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findAll();

        $response = [];

        foreach ($games as $game) {
            $response[] = [
                'id' => $game->getId(),
                'name' => preg_replace('/\s+/', ' ', str_replace("\n", '', $game->getName())),
            ];
        }

        return $this->json($response);
    }

    /**
     * @Route("/game/{id}", name="game_detail", requirements={"id"="\d+"})
     */
    public function getGame($id)
    {
        $game = $this->getDoctrine()
            ->getManager()
            ->getRepository(Game::class)
            ->find($id);

        if (!$game) {
            throw $this->createNotFoundException("Game not exist");
        }

        $editor = $game->getEditor();

        $games[] = [
            'id' => $editor->getId(),
            'name' => preg_replace('/\s+/', ' ', str_replace("\n", '', $editor->getName()))
        ];

        $response = [
            'id' => $game->getId(),
            'name' => preg_replace('/\s+/', ' ', str_replace("\n", '', $game->getName())),
            'description' => preg_replace('/\s+/', ' ', str_replace("\n", '', $game->getDescription())),
            'date' => $game->getDate(),
            'classification' => preg_replace('/\s+/', ' ', str_replace("\n", '', $game->getClassification())),
            'cover' => preg_replace('/\s+/', ' ', str_replace("\n", '', $game->getCover())),
            'review' => $game->getReview(),
            'link' => preg_replace('/\s+/', ' ', str_replace("\n", '', $game->getLink())),
            'editor' => $games
        ];

        return $this->json($response);
    }

}