<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(Request $request): Response
    {

        $session = $request->getSession();

        if (!$session->has('todos'))
        {
            $todos = [
                'achat' => 'Acheter clé usb',
                'rdv' => 'Rendez-vous chez le dentiste',
                'cours' => 'Cours de piano'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info',"la liste des todos vient d'être créée");
        }

        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content): RedirectResponse 
    {

        $session = $request->getSession();

        if ($session->has('todos'))
        {
            $todos = $session->get('todos');
            if (isset($todos[$name])) 
            {
                $this->addFlash('error',"la tâche $name existe déjà");
                return $this->redirectToRoute('todo');
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success',"la tâche $name est ajoutée");
            }

        } else {

            $this->addFlash('error',"la liste n'est pas encore initialisée");
        }

        return $this->redirectToRoute('todo');

    }

    #[Route('/todo/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse  
    {

        $session = $request->getSession();

        if ($session->has('todos'))
        {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) 
            {
                $this->addFlash('error',"la tâche $name n'existe pas");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success',"la tâche $name est modifiée");
            }

        } else {

            $this->addFlash('error',"la liste n'est pas encore initialisée");
        }

        return $this->redirectToRoute('todo');

    }

    #[Route('/todo/delete/{name}/{content}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse  
    {

        $session = $request->getSession();

        if ($session->has('todos'))
        {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) 
            {
                $this->addFlash('error',"la tâche $name n'existe pas");
            } else {
                unset($todos[$name]);
                $this->addFlash('success',"la tâche $name est supprimée");
                $session->set('todos', $todos);
            }

        } else {

            $this->addFlash('error',"la liste n'est pas encore initialisée");
        }

        return $this->redirectToRoute('todo');

    }

    #[Route('/todo/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse  
    {

        $session = $request->getSession();
        $session->remove('todos');
        $this->addFlash('info',"la liste des todos est réinitialisée");
        return $this->redirectToRoute('todo');
        
    }


}
