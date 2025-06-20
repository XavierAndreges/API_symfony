# Création des Secrets dans Google Secret Manager

## 1. Secret symfony-db-credentials

Ce secret contient les informations de connexion à la base de données. Il est recommandé de stocker ces informations au format JSON.

```bash
# Créer le secret
gcloud secrets create symfony-db-credentials \
    --replication-policy="automatic"

# Créer un fichier temporaire avec les credentials
cat > db-credentials.json << EOF
{
    "DATABASE_URL": "mysql://symfony:VOTRE_MOT_DE_PASSE@gcp-mysql-host:3306/mp?serverVersion=8.0",
    "GCP_DB_USER": "symfony",
    "GCP_DB_PASSWORD": "VOTRE_MOT_DE_PASSE",
    "GCP_DB_HOST": "gcp-mysql-host",
    "GCP_DB_NAME": "mp"
}
EOF

# Ajouter la version du secret
gcloud secrets versions add symfony-db-credentials \
    --data-file="db-credentials.json"

# Supprimer le fichier temporaire
rm db-credentials.json
```

## 2. Secret symfony-app-secret

Ce secret contient la clé secrète de l'application Symfony. Il est recommandé de générer une nouvelle clé secrète pour la production.

```bash
# Générer une nouvelle clé secrète Symfony
APP_SECRET=$(php -r 'echo bin2hex(random_bytes(32));')

# Créer le secret
gcloud secrets create symfony-app-secret \
    --replication-policy="automatic"

# Ajouter la version du secret
echo -n "$APP_SECRET" | gcloud secrets versions add symfony-app-secret --data-file=-
```

## 2. Secret google-maps-api-key

echo -n "AIzaS....." | gcloud secrets create google-maps-api-key \
  --replication-policy="automatic" \
  --data-file=-


## Vérification des Secrets

Pour vérifier que les secrets ont été créés correctement :

```bash
# Lister tous les secrets
gcloud secrets list

# Voir les versions d'un secret
gcloud secrets versions list symfony-db-credentials
gcloud secrets versions list symfony-app-secret
```

## Mise à jour des Secrets

Pour mettre à jour un secret existant :

```bash
# Pour symfony-db-credentials
gcloud secrets versions add symfony-db-credentials \
    --data-file="db-credentials.json"

# Pour symfony-app-secret
echo -n "$APP_SECRET" | gcloud secrets versions add symfony-app-secret --data-file=-
```

## Bonnes Pratiques

1. **Rotation des Secrets** :
   - Changez régulièrement les mots de passe et les clés secrètes
   - Gardez une trace des versions des secrets
   - Testez la nouvelle version avant de supprimer l'ancienne

2. **Sécurité** :
   - Utilisez des mots de passe forts
   - Ne stockez jamais les secrets en clair dans le code
   - Limitez l'accès aux secrets aux seuls services qui en ont besoin

3. **Gestion des Versions** :
   - Gardez une trace des changements de secrets
   - Documentez les raisons des changements
   - Testez les nouvelles versions avant de les mettre en production 




   CAS SPECIFIQUE MAPS API


4. ✅ Définir un SecretProviderClass
Un fichier google-maps-secret-provider.yaml :

yaml
apiVersion: secrets-store.csi.x-k8s.io/v1
kind: SecretProviderClass
metadata:
  name: google-maps-api-key-provider
spec:
  provider: gcp
  parameters:
    secrets:
      - resourceName: "projects/<PROJECT_ID>/secrets/google-maps-api-key"
        fileName: "google-maps-api-key"
5. ✅ Monter le secret dans le pod Symfony
Dans ton Deployment ou Pod :

yaml
spec:
  containers:
    - name: symfony
      env:
        - name: GOOGLE_MAPS_API_KEY
          valueFrom:
            secretKeyRef:
              name: google-maps-api-key-secret
              key: google-maps-api-key
  volumes:
    - name: secrets-store-inline
      csi:
        driver: secrets-store.csi.k8s.io
        readOnly: true
        volumeAttributes:
          secretProviderClass: "google-maps-api-key-provider"


Et optionnellement, créer un Secret K8s synchronisé avec le CSI (si tu veux y accéder comme secretKeyRef) :

yaml

spec:
  ...
  secretObjects:
    - secretName: google-maps-api-key-secret
      type: Opaque
      data:
        - key: google-maps-api-key
          objectName: google-maps-api-key

6. ✅ Utiliser dans Symfony
Dans .env :

GOOGLE_MAPS_API_KEY=xxx (sera remplacé dynamiquement dans K8s)
Dans services.yaml ou tes contrôleurs/services :

php
$mapsApiKey = $_ENV['GOOGLE_MAPS_API_KEY'] ?? null;