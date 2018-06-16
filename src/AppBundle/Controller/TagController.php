<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"tag"})
     * @Rest\Get("tags")
     */
    public function getTagsAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tag')->findAll();

        // If tags not found
        if (!$tags) {
            return new JsonResponse(['message' => 'Tags not found'], Response::HTTP_NOT_FOUND);
        }

        return $tags;
    }
}
