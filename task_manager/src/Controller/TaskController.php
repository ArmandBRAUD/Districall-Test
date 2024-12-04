<?php
// src/Controller/TaskController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    // Route pour récupérer toutes les tâches (GET) avec pagination
    #[Route('/api/tasks', name: 'get_tasks', methods: ['GET'])]
    public function getTasks(Request $request): Response
    {
        // Chemin vers le fichier JSON
        $filePath = $this->getParameter('kernel.project_dir') . '/public/tasks.json';

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            return $this->json(['message' => 'No tasks found'], 404);
        }

        // Lire le fichier JSON
        $tasks = json_decode(file_get_contents($filePath), true);

        // Si le fichier est vide ou invalide
        if ($tasks === null) {
            return $this->json(['message' => 'Error reading tasks'], 500);
        }

        // Récupérer le paramètre de pagination pour la page (page par défaut = 1)
        $page = (int) $request->query->get('page', 1); // Page par défaut = 1
        $perPage = 10; // Nombre fixe de tâches par page

        // Calculer le nombre total de pages
        $totalTasks = count($tasks);
        $totalPages = ceil($totalTasks / $perPage);

        // Vérifier si la page demandée est valide
        if ($page > $totalPages || $page < 1) {
            return $this->json(['message' => 'Page not found'], 404);
        }

        // Calculer l'offset (où commencer à récupérer les éléments)
        $offset = ($page - 1) * $perPage;

        // Extraire les tâches pour cette page
        $pagedTasks = array_slice($tasks, $offset, $perPage);

        // Retourner les tâches pour cette page avec la pagination
        return $this->json([
            'tasks' => $pagedTasks,
            'total_tasks' => $totalTasks,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'per_page' => $perPage
        ]);
    }

    // Route pour créer une nouvelle tâche (POST)
    #[Route('/api/tasks', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request): Response
    {
        // Récupérer les données JSON envoyées dans la requête
        $data = json_decode($request->getContent(), true);

        // Validation des données
        if (!isset($data['title']) || !isset($data['description']) || !isset($data['status'])) {
            return $this->json(['message' => 'Title, description, and status are required.'], 400);
        }

        // Validation de la longueur du titre (doit être entre 3 et 255 caractères)
        if (strlen($data['title']) < 3 || strlen($data['title']) > 255) {
            return $this->json(['message' => 'Title must be between 3 and 255 characters.'], 400);
        }

        // Validation de la description (ne doit pas être vide)
        if (empty($data['description'])) {
            return $this->json(['message' => 'Description cannot be empty.'], 400);
        }

        // Validation du statut (doit être l'un des valeurs autorisées)
        $validStatuses = ['todo', 'in_progress', 'done'];
        if (!in_array($data['status'], $validStatuses)) {
            return $this->json(['message' => 'Status must be one of: todo, in_progress, done.'], 400);
        }

        // Chemin du fichier JSON
        $filePath = $this->getParameter('kernel.project_dir') . '/public/tasks.json';

        // Lire les tâches existantes
        if (file_exists($filePath)) {
            $tasks = json_decode(file_get_contents($filePath), true);
        } else {
            $tasks = [];
        }

        // Créer une nouvelle tâche
        $newTask = [
            'id' => count($tasks) + 1,  // ID simple basé sur la taille du tableau
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        // Ajouter la tâche à la liste
        $tasks[] = $newTask;

        // Sauvegarder la liste des tâches dans le fichier JSON
        file_put_contents($filePath, json_encode($tasks, JSON_PRETTY_PRINT));

        // Retourner la tâche créée
        return $this->json($newTask, 201);
    }
    // Route pour modifier une tâche existante (PUT)
    #[Route('/api/tasks/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id, Request $request): Response
    {
        // Chemin du fichier JSON
        $filePath = $this->getParameter('kernel.project_dir') . '/public/tasks.json';

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            return $this->json(['message' => 'No tasks found'], 404);
        }

        // Lire les tâches existantes
        $tasks = json_decode(file_get_contents($filePath), true);

        // Trouver la tâche à mettre à jour par son ID
        $taskIndex = null;
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                $taskIndex = $index;
                break;
            }
        }

        // Si la tâche n'a pas été trouvée, retourner une erreur
        if ($taskIndex === null) {
            return $this->json(['message' => 'Task not found'], 404);
        }

        // Récupérer les nouvelles données de la tâche
        $data = json_decode($request->getContent(), true);

        // Validation des données
        if (!isset($data['title']) || !isset($data['description']) || !isset($data['status'])) {
            return $this->json(['message' => 'Title, description, and status are required.'], 400);
        }

        // Validation de la longueur du titre (doit être entre 3 et 255 caractères)
        if (strlen($data['title']) < 3 || strlen($data['title']) > 255) {
            return $this->json(['message' => 'Title must be between 3 and 255 characters.'], 400);
        }

        // Validation de la description (ne doit pas être vide)
        if (empty($data['description'])) {
            return $this->json(['message' => 'Description cannot be empty.'], 400);
        }

        // Validation du statut (doit être l'un des valeurs autorisées)
        $validStatuses = ['todo', 'in_progress', 'done'];
        if (!in_array($data['status'], $validStatuses)) {
            return $this->json(['message' => 'Status must be one of: todo, in_progress, done.'], 400);
        }

        // Mettre à jour les informations de la tâche
        $tasks[$taskIndex]['title'] = $data['title'];
        $tasks[$taskIndex]['description'] = $data['description'];
        $tasks[$taskIndex]['status'] = $data['status'];
        $tasks[$taskIndex]['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        // Sauvegarder les tâches mises à jour dans le fichier JSON
        file_put_contents($filePath, json_encode($tasks, JSON_PRETTY_PRINT));

        // Retourner la tâche mise à jour
        return $this->json($tasks[$taskIndex], 200);
    }
    // Route pour supprimer une tâche (DELETE)
    #[Route('/api/tasks/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(int $id): Response
    {
        // Chemin du fichier JSON
        $filePath = $this->getParameter('kernel.project_dir') . '/public/tasks.json';

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            return $this->json(['message' => 'No tasks found'], 404);
        }

        // Lire les tâches existantes
        $tasks = json_decode(file_get_contents($filePath), true);

        // Trouver la tâche à supprimer par son ID
        $taskIndex = null;
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                $taskIndex = $index;
                break;
            }
        }

        // Si la tâche n'a pas été trouvée, retourner une erreur
        if ($taskIndex === null) {
            return $this->json(['message' => 'Task not found'], 404);
        }

        // Supprimer la tâche
        array_splice($tasks, $taskIndex, 1);

        // Sauvegarder la liste des tâches dans le fichier JSON
        file_put_contents($filePath, json_encode($tasks, JSON_PRETTY_PRINT));

        // Retourner un message de succès
        return $this->json(['message' => 'Task deleted successfully'], 200);
    }
}
