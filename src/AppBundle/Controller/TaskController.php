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

        $tasks = $em->getRepository('AppBundle:Task')->getTasks();

        return $tasks;
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

        return $tasks;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/task")
     */
    public function postTaskAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // Create new task
        $task = new Task();
        $task->setCompleted(0);

        // Persist profile
        $em->persist($task);
        // Update database
        $em->flush();

        return $task;
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Put("/task/{id}")
     */
    public function putTaskAction(Request $request, Task $task)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $tag = $request->get('tag');

        if ($tag) {
            $task->setTag($tag);
        }

        $taskContent = $request->get('content');

        if ($taskContent) {
            $task->setContent($taskContent);
        }

        $completed = $request->get('completed');

        if ($completed === 0 || $completed != null) {
            $task->setCompleted($completed);
        }

        $em->flush();

        return $task;
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
