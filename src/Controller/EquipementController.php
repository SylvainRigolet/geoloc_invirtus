<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipementController extends AbstractController
{

    private function jsonToArray(): Array 
    {
        $json = file_get_contents("../public/json/equipements.json");
        $listArray = json_decode($json);

        return $listArray;
    }

    //Affichage de la map sans infos + menu déroulant.
    #[Route('/equipement', name: 'app_equipement')]
    public function index(): Response
    {
        $listEquipements = $this->jsonToArray();

        return $this->render('equipement/index.html.twig', [
            'controller_name' => 'EquipementController',
            "listEquipements" => $listEquipements
        ]);
    }

    //Affichage des marqueurs d'un équipement en fonction de son id.
    #[Route('/equipement/{id}', name: 'app_equipement_id')]
    public function view($id): Response
    {
        $listEquipements = $this->jsonToArray();

        // Tester si la clé existe ou non dans le tableau.
        if (!array_key_exists($id, $listEquipements)) {
            $this->addFlash('notice',"L'équipement demandé n'existe pas.");
            return $this->redirectToRoute('app_equipement');
        }

        $equipement = $listEquipements[$id];

        return $this->render('equipement/index.html.twig', [
            'controller_name' => 'EquipementController',
            "listEquipements" => $listEquipements,
            "equipement" => $equipement,
        ]);
    }

    //Renvoie l'historique des localisations d'un équipement en fonction de son id.
    #[Route('/getLocation/{id}', name: 'app_getLocations_id', options: ['expose' => true])]
    public function getLocations($id): Response
    {
        $listEquipements = $this->jsonToArray();

        // Tester si la clé existe ou non dans le tableau.
        if (!array_key_exists($id, $listEquipements)) {
            $this->addFlash('notice',"L'équipement demandé n'existe pas.");
            return $this->redirectToRoute('app_equipement');
        }

        $historic = $listEquipements[$id];

        // $historic = json_encode($historic, JSON_PRETTY_PRINT);

        return $this->json(
            $historic,
        );
    }

}