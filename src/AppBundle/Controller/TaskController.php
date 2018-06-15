<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        $task = $em->getRepository('AppBundle:Task')->getTasks();

        // If task not found
        if (!$task) {
            return new JsonResponse(['message' => 'Tasks not found'], Response::HTTP_NOT_FOUND);
        }

        return $task;
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("task/{id}")
     */
    public function getTaskAction(Task $task)
    {
        // If task not found
        if (!$task) {
            return new JsonResponse(['message' => 'Tasks not found'], Response::HTTP_NOT_FOUND);
        }

        return $task;
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("tasks/completed")
     */
    public function getCompletedTaskAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('AppBundle:Task')->getCompletedTasks();

        // If task not found
        if (!$tasks) {
            return new JsonResponse(['message' => 'Tasks not found'], Response::HTTP_NOT_FOUND);
        }

        return $tasks;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/task")
     */
    public function postTaskAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // Create new task
        $task = new Task();

        // Create task form
        $form = $this->createForm(TaskType::class, $task);
        // Fill form with request data
        $form->submit($request->request->all());

        // If form is task
        if ($form->isValid()) {
            // Persist profile
            $em->persist($task);
            // Update database
            $em->flush();

            return $task;
        }
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Put("/task/{id}")
     */
    public function putTaskAction(Request $request, Task $task)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $tagId = $request->get('tag');

        if ($tagId) {
            $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['id' => $tagId]);

            if ($tag) {
                $task->setTag($tag);
                $em->flush();
            }
        }

        // Edit task
        $form = $this->createForm(TaskType::class, $task);

        // Fill form
        $form->submit($request->request->all(), false);

        // If form is valid
        if ($form->isValid()) {
            // Update database
            $em->flush();

            return $task;
        }
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Delete("/task/{id}")
     */
    public function deleteTaskAction(Task $task)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($task);
        $em->flush();
    }
}
