Tâches API - Symfony
Ce projet implémente une API simple de gestion des tâches à l'aide de Symfony. L'API permet de créer, récupérer, mettre à jour et supprimer des tâches via des requêtes HTTP.

Prérequis
PHP version 8.1 ou supérieure
Composer pour la gestion des dépendances
Symfony CLI pour exécuter le serveur de développement local (facultatif)
Postman pour tester l'API
Installation du projet
1. Cloner le projet
Clonez ce repository sur votre machine locale :

bash
Copier le code
git clone https://github.com/ArmandBRAUD/Districall-Test.git
2. Installer les dépendances
Naviguez dans le répertoire du projet et installez les dépendances avec Composer :

bash
Copier le code
cd mon-projet
composer install
3. Configurer l'environnement
Assurez-vous que le fichier .env est correctement configuré, notamment pour les configurations de base de données (si nécessaire). Si vous utilisez une base de données ou un autre service, configurez-le dans le fichier .env.

4. Lancer le serveur Symfony
Vous pouvez utiliser le serveur local de Symfony pour tester l'API en développement. Si vous avez installé Symfony CLI, lancez le serveur avec cette commande :

bash
Copier le code
symfony server:start
Cela démarrera le serveur à l'adresse http://127.0.0.1:8000.

Utilisation de l'API
L'API vous permet de manipuler des tâches via les méthodes HTTP suivantes : GET, POST, PUT, et DELETE.

1. Récupérer toutes les tâches (GET)
URL: GET /api/tasks

Cette route permet de récupérer toutes les tâches de l'API avec pagination.

Paramètres :
page: Le numéro de la page (par défaut = 1).
Exemple de requête Postman :

Méthode : GET
URL : http://127.0.0.1:8000/api/tasks?page=1
La réponse retournera une liste de tâches, ainsi que des informations sur la pagination (nombre total de tâches, nombre total de pages, etc.).

2. Créer une nouvelle tâche (POST)
URL: POST /api/tasks

Cette route permet de créer une nouvelle tâche.

Paramètres (Body en JSON) :
json
Copier le code
{
  "title": "Nouvelle Tâche",
  "description": "Description de la nouvelle tâche",
  "status": "todo"
}
Exemple de requête Postman :

Méthode : POST
URL : http://127.0.0.1:8000/api/tasks
Headers : Content-Type: application/json
Body (raw, JSON) :
json
Copier le code
{
  "title": "Nouvelle Tâche",
  "description": "Description de la nouvelle tâche",
  "status": "todo"
}
La réponse retournera les informations de la tâche nouvellement créée.

3. Mettre à jour une tâche (PUT)
URL: PUT /api/tasks/{id}

Cette route permet de mettre à jour les informations d'une tâche existante.

Paramètres (Body en JSON) :
json
Copier le code
{
  "title": "Titre mis à jour",
  "description": "Description mise à jour",
  "status": "in_progress"
}
Exemple de requête Postman :

Méthode : PUT
URL : http://127.0.0.1:8000/api/tasks/1 (Remplacez 1 par l'ID de la tâche à modifier)
Headers : Content-Type: application/json
Body (raw, JSON) :
json
Copier le code
{
  "title": "Titre mis à jour",
  "description": "Description mise à jour",
  "status": "in_progress"
}
La réponse retournera les informations de la tâche mise à jour.

4. Supprimer une tâche (DELETE)
URL: DELETE /api/tasks/{id}

Cette route permet de supprimer une tâche existante par son ID.

Exemple de requête Postman :

Méthode : DELETE
URL : http://127.0.0.1:8000/api/tasks/1 (Remplacez 1 par l'ID de la tâche à supprimer)
La réponse retournera un message de confirmation indiquant que la tâche a été supprimée avec succès.

Tester l'API avec Postman
1. Ouvrir Postman
Si vous ne l'avez pas déjà installé, vous pouvez télécharger Postman depuis https://www.postman.com/downloads/.
2. Créer des requêtes dans Postman
GET : Testez la récupération des tâches en envoyant une requête GET à http://127.0.0.1:8000/api/tasks.
POST : Testez la création d'une nouvelle tâche en envoyant une requête POST avec un corps JSON comme spécifié ci-dessus.
PUT : Testez la mise à jour d'une tâche en envoyant une requête PUT avec un corps JSON et un ID de tâche valide.
DELETE : Testez la suppression d'une tâche en envoyant une requête DELETE avec l'ID de la tâche à supprimer.
Structure des fichiers
src/Controller/TaskController.php : Le contrôleur principal qui gère les routes API pour les tâches.
public/tasks.json : Le fichier JSON qui stocke les tâches.
Contributions
Les contributions sont les bienvenues. Si vous souhaitez améliorer ce projet, vous pouvez soumettre des pull requests.

Licence
Ce projet est sous la licence MIT - voir le fichier LICENSE pour plus de détails.

Ce fichier README fournit des instructions complètes pour installer et tester le projet via Postman. Il inclut des exemples pour toutes les méthodes HTTP disponibles (GET, POST, PUT, DELETE).
