<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("tasks")
     */
    public function getTasksAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('AppBundle:Task')->findAll();

        // If task not found
        if (!$task) {
            return new JsonResponse(['message' => 'Tasks not found'], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        return $task;
    }
}
