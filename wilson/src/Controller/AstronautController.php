<?php

namespace App\Controller;

use App\Entity\Astronaut;
use App\Form\AstronautType;
use App\Repository\AstronautRepository;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api/v1/astronauts", name="astronaut")
 */
class AstronautController extends AbstractFOSRestController
{
    private AstronautRepository $astronautRepository;

    public function __construct(AstronautRepository $astronautRepository)
    {
        $this->astronautRepository = $astronautRepository;
    }

    /**
     * List all astronauts
     *
     * @Rest\Get(
     *     path="/",
     *     name="_list"
     * )
     * @Rest\View()
     *
     * @return Astronaut[]
     */
    public function getAllAstronauts()
    {
        return $this->astronautRepository->findAll();
    }


    /**
     * Create a new astronaut
     *
     * @Rest\Post(
     *     path="/",
     *     name="_new"
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     *
     * @param Request $request
     * @return Astronaut|FormInterface
     */
    public function createAstronaut(Request $request)
    {
        $astronaut = new Astronaut();
        $form = $this->createForm(AstronautType::class, $astronaut);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->astronautRepository->add($astronaut);
            return $astronaut;
        }
        return $form;
    }

    /**
     * Update an existing astronaut
     *
     * @Rest\Put(
     *     path="/{id}",
     *     name="_update"
     * )
     * @Rest\View()
     *
     * @param Astronaut $astronaut
     * @param Request $request
     * @return Astronaut|View|FormInterface
     */
    public function editAstronaut(Astronaut $astronaut, Request $request)
    {
        return $this->updateAstronaut($astronaut, $request, true);
    }

    /**
     * Patch an existing astronaut
     *
     * @Rest\Patch(
     *     path="/{id}",
     *     name="_patch"
     * )
     * @Rest\View()
     *
     * @param Astronaut $astronaut
     * @param Request $request
     * @return Astronaut|View|FormInterface
     */
    public function patchAstronaut(Astronaut $astronaut, Request $request)
    {
        return $this->updateAstronaut($astronaut, $request, false);
    }

    /**
     * @param Astronaut $astronaut
     * @param Request $request
     * @param bool $clearMissing
     * @return Astronaut|View|FormInterface
     */
    private function updateAstronaut(Astronaut $astronaut, Request $request, bool $clearMissing)
    {
        if (!$astronaut) {
            return new View(['message' => 'Astronaut not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(AstronautType::class, $astronaut);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->astronautRepository->add($astronaut);
            return $astronaut;
        }
        return $form;
    }
}
