## API Laravel 11 - HelloCSE

Ce projet est une API Laravel 11 qui gère les administrateurs et les profils. Il inclut l'authentification, la création, la mise à jour, la suppression et l'affichage public des profils.

### Installation

1. **Cloner le dépôt**
   ```sh
   git clone https://github.com/dastagirkhan/hellocse-admin-api
   cd hellocse-admin-api
   ```

2. **Installer les dépendances**
   ```sh
   composer install
   ```

3. **Configurer les variables d'environnement**
   ```sh
   cp .env.example .env
   ```
   Configurer la base de données et les autres paramètres dans le fichier `.env`.

4. **Générer la clé de l'application**
   ```sh
   php artisan key:generate
   ```

5. **Exécuter les migrations et insérer des données de test**
   ```sh
   php artisan migrate --seed
   ```

6. **Démarrer le serveur**
   ```sh
   php artisan serve
   ```

### Points d'Entrée de l'API

#### Authentification
- **Inscription d'un administrateur**
  ```
  POST /api/register
  {
    "email": "admin@example.com",
    "password": "password123"
  }
  ```

- **Connexion d'un administrateur**
  ```
  POST /api/login
  {
    "email": "admin@example.com",
    "password": "password123"
  }
  ```
  Réponse : `{ "token": "your_token_here" }`

#### Gestion des Profils (Nécessite une authentification)
- **Créer un profil**
  ```
  POST /api/profiles
  Authorization: Bearer {token}
  {
    "nom": "John",
    "prenom": "Doe",
    "image": [fichier]
  }
  ```

- **Mettre à jour un profil**
  ```
  PUT /api/profiles/{id}
  Authorization: Bearer {token}
  {
    "nom": "Jane",
    "prenom": "Doe",
    "image": [fichier]
  }
  ```

- **Supprimer un profil**
  ```
  DELETE /api/profiles/{id}
  Authorization: Bearer {token}
  ```

#### Affichage Public des Profils (Aucune authentification requise)
- **Récupérer les profils actifs**
  ```
  GET /api/profiles
  ```

### Règles de Validation
- `email` : Requis, unique, format valide.
- `password` : Requis, minimum 8 caractères.
- `nom` : Requis, chaîne de caractères, max 255 caractères.
- `prenom` : Requis, chaîne de caractères, max 255 caractères.
- `image` : Requise, doit être une image (jpeg, png, jpg), max 2MB.

### Exécution des Tests
Lancer les tests fonctionnels pour vérifier le bon fonctionnement de l'API :
```sh
php artisan test
```

### Remarques
- Utilisation de Laravel Sanctum pour l'authentification.
- Stockage des images dans le répertoire `storage/app/public/profiles`.
- Les réponses de l'API respectent les standards RESTful.

### Commentaires du Code

#### AuthController
- `register(Request $request)`: Gère l'inscription d'un administrateur avec validation de l'email et du mot de passe.
- `login(Request $request)`: Authentifie un administrateur et retourne un token API si les identifiants sont valides.

#### ProfileController
- `index()`: Récupère tous les profils actifs.
- `store(ProfileRequest $request)`: Crée un nouveau profil avec téléchargement d'image et statut par défaut.
- `update(ProfileRequest $request, Profile $profile)`: Met à jour un profil existant et gère le remplacement de l'image.
- `destroy(Profile $profile)`: Supprime un profil et son image associée.

#### ProfileRequest
- Définit les règles de validation pour la création et la mise à jour des profils.

#### Modèle Profile
- Spécifie les champs remplissables et définit la relation avec le modèle `Administrator`.

