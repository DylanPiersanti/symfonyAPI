<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Editor;

class EditorController extends AbstractController
{
    /**
     * @Route("/editors", name="editors")
     */
    public function getEditors()
    {
        $editors = $this->getDoctrine()
            ->getRepository(Editor::class)
            ->findAll();

        $response = [];

        foreach ($editors as $editor) {
            $response[] = [
                'id' => $editor->getId(),
                'name' => preg_replace('/\s+/', ' ', str_replace("\n", '', $editor->getName())),
            ];
        }

        return $this->json($response);
    }

     /**
     * @Route("/editor/{id}", name="editor_detail", requirements={"id"="\d+"})
     */
    public function getEditor($id)
    {
        $editor = $this->getDoctrine()
            ->getManager()
            ->getRepository(Editor::class)
            ->find($id);

        if (!$editor) {
            throw $this->createNotFoundException("Editor not exist");
        }

        $games = [];

        foreach ($editor->getGames() as $game) {
            $games[] = [
                'id' => $game->getId(),
                'name' => $game->getName()
            ];
        }

        $response = [
            'id' => $editor->getId(),
            'name' => preg_replace('/\s+/', ' ', str_replace("\n", '', $editor->getName())),
            'games' => $games
        ];

        return $this->json($response);
    }
}
