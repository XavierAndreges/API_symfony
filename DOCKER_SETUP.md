# Configuration Docker avec Variables d'Environnement

## 🚀 Installation

### 1. Copier le fichier de configuration
```bash
cp .env.docker.example .env.docker
```

### 2. Modifier les variables selon vos besoins
Éditez le fichier `.env.docker` avec vos propres valeurs :

```bash
# Base de données
MYSQL_ROOT_PASSWORD=votre_mot_de_passe_root
MYSQL_DATABASE=nom_de_votre_base
MYSQL_USER=votre_utilisateur
MYSQL_PASSWORD=votre_mot_de_passe
```

### 3. Démarrer les conteneurs
```bash
docker-compose --env-file .env.docker up -d
```

## 🔧 Variables d'Environnement

| Variable | Description | Valeur par défaut |
|----------|-------------|-------------------|
| `MYSQL_ROOT_PASSWORD` | Mot de passe root MySQL | `root` |
| `MYSQL_DATABASE` | Nom de la base de données | `mp` |
| `MYSQL_USER` | Utilisateur de la base de données | `symfony` |
| `MYSQL_PASSWORD` | Mot de passe de l'utilisateur | `password` |
| `DATABASE_URL` | URL de connexion complète | Générée automatiquement |

## 🔒 Sécurité

- Le fichier `.env.docker` est dans `.gitignore` et ne sera pas commité
- Utilisez des mots de passe forts en production
- Ne partagez jamais le fichier `.env.docker` contenant de vraies valeurs

## 🚀 Utilisation en production/CI (GKE & Google Secret Manager)

Pour la production sur GKE, **les secrets applicatifs (DB, APP_SECRET, JWT, etc.) sont gérés par Google Secret Manager** et injectés dans les pods via le CSI Secret Store Driver. 

- **Aucune gestion de .env.docker.prod n'est nécessaire dans GitHub Actions.**
- Les secrets sont la "source de vérité" dans Google Cloud et sont montés automatiquement dans les pods Kubernetes.
- Le workflow CI/CD ne gère que l'authentification, le build/push d'image, et le déploiement sur GKE.

**Exemple d'architecture :**

```
GitHub Actions (OIDC)
    ↓ (Workload Identity Federation)
Google Cloud (Service Account)
    ↓ (authentification)
Secret Manager (source de vérité)
    ↓ (injection des secrets)
GKE (CSI Secret Store Driver)
    ↓ (montage sécurisé)
Pods (volumes secrets)
```

### 📝 Commandes utiles (rappel)

```bash
# Démarrer les services en local (dev)
docker-compose --env-file .env.docker.dev up -d
```

## 🐛 Dépannage

### Problème de connexion à la base de données
1. Vérifiez que les variables dans `.env.docker` sont correctes
2. Redémarrez les conteneurs : `docker-compose --env-file .env.docker down && docker-compose --env-file .env.docker up -d`
3. Vérifiez les logs : `docker-compose --env-file .env.docker logs database`

### Variables non prises en compte
1. Assurez-vous que le fichier `.env.docker` existe
2. Vérifiez la syntaxe des variables (pas d'espaces autour du `=`)
3. Utilisez toujours `--env-file .env.docker` avec docker-compose
4. Redémarrez Docker Compose

## 📝 Commandes utiles

```bash
# Démarrer les services
docker-compose --env-file .env.docker up -d

# Arrêter les services
docker-compose --env-file .env.docker down

# Voir les logs
docker-compose --env-file .env.docker logs

# Vérifier la configuration
docker-compose --env-file .env.docker config
``` 