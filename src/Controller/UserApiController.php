<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use Doctrine\ORM\EntityManagerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class UserApiController extends AbstractController
{
    /**
     * CRUD Create - required string email, string password from json
     * @Route("/api/user", methods={"POST"})
     */
    public function apiUserCreate(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $this->getArrayFromJson($request);

        $isSuccessful = true;
        try {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->submit($data);

            $em->persist($user);
            $em->flush();
        } catch (\Throwable $throwable) {
            $isSuccessful = false;
        }

        return $this->json($isSuccessful);
    }

    /**
     * CRUD Read one user - required int id
     * @Route("/api/user/{id}", methods={"GET"})
     */
    public function apiUserRead(EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository('App:User')->find((int)$id);
        if (!$user) {
            throw $this->createNotFoundException('No user found.');
        }

        return $this->json(
            $user,
            200,
            [],
            ['groups' => 'api']
        );
    }

    /**
     * CRUD Read all users
     * @Route("/api/user", methods={"GET"})
     */
    public function apiUserReadAll(EntityManagerInterface $em): JsonResponse
    {
        $users = $em->getRepository('App:User')->findAll();

        return $this->json(
            $users,
            200,
            [],
            ['groups' => 'api']
        );
    }

    /**
     * CRUD Update - required int id, string email, string password from json
     * @Route("/api/user", methods={"PUT"})
     */
    public function apiUserUpdate(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $this->getArrayFromJson($request);

        $isSuccessful = true;
        try {
            /** @var User $user */
            $user = $em->getRepository('App:User')->find((int)$data['id']);
            $form = $this->createForm(UserType::class, $user);
            $form->submit($data);
            $em->flush();
        } catch (\Throwable $throwable) {
            $isSuccessful = false;
        }

        return $this->json($isSuccessful);
    }

    /**
     * CRUD Delete - required int id from json
     * @Route("/api/user", methods={"DELETE"})
     */
    public function apiUserDelete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $this->getArrayFromJson($request);

        $isSuccessful = true;
        try {
            $user = $em->getRepository('App:User')->find((int)$data['id']);
            $em->remove($user);
            $em->flush();
        } catch (\Throwable $throwable) {
            $isSuccessful = false;
        }

        return $this->json($isSuccessful);
    }

    private function getArrayFromJson(Request $request)
    {
        $body = $request->getContent();
        return json_decode($body, true);
    }
}
