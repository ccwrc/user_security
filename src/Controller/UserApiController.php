<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function apiUserCreate(Request $request)
    {
        $data = $this->getArrayFromJson($request);

        $isSuccessful = true;
        try {
            $user = new User();
            $user->setEmail($data['email'])
                ->setPassword($data['password']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        } catch (\Throwable $throwable) {
            $isSuccessful = false;
        }

        return $this->json($isSuccessful);
    }

    /**
     * CRUD Read - required int id from json
     * @Route("/api/user", methods={"GET"})
     */
    public function apiUserRead(Request $request)
    {
        $data = $this->getArrayFromJson($request);

        $user = $this->getDoctrine()->getRepository('App:User')->find($data['id']);

        return $this->json(
            $user,
            200,
            [],
            ['groups' => 'api']
        );
    }

    /**
     * @Route("/api/user", methods={"PUT"})
     */
    public function apiUserUpdate(Request $request)
    {
        $data = $this->getArrayFromJson($request);

        // todo

        return $this->json(
            ''
        );
    }

    /**
     * CRUD Delete - required int id from json
     * @Route("/api/user", methods={"DELETE"})
     */
    public function apiUserDelete(Request $request)
    {
        $data = $this->getArrayFromJson($request);

        $isSuccessful = true;
        try {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->find($data['id']);
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
