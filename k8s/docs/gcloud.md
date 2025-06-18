Voici les étapes à suivre pour mettre en place cette architecture :
- Dans Google Cloud :
    Créer un projet GCP si ce n'est pas déjà fait
    Activer Secret Manager
        Créer les secrets dans Secret Manager :
            symfony-db-credentials
            symfony-app-secret
    Configurer Workload Identity Federation
    Créer un Service Account avec les permissions nécessaires
- Dans GitHub :
    Configurer les secrets suivants :
        GCP_PROJECT_ID
        GKE_CLUSTER
        GKE_ZONE
        WIF_PROVIDER
        GCP_SA_EMAIL
- Dans GKE :
    Installer le CSI Driver pour Secret Store
    Configurer les IAM policies pour permettre l'accès aux secrets


# Configuration de Google Cloud et Obtention des Secrets

## Installation et Configuration Initiale de gcloud

1. **Installation de Google Cloud SDK** :
   - Si ce n'est pas déjà fait, vous devez d'abord installer le Google Cloud SDK sur votre machine
   - Pour macOS, vous pouvez l'installer via Homebrew :
   ```bash
   brew install google-cloud-sdk
   ```

2. **Initialisation et authentification** :
   ```bash
   # Initialiser gcloud
   gcloud init

   # Se connecter à votre compte Google
   gcloud auth login
   ```

3. **Vérifier que vous êtes bien connecté** :
   ```bash
   gcloud auth list
   ```

4. **Sélectionner votre projet** :
   ```bash
   # Lister vos projets
   gcloud projects list

   # Sélectionner votre projet
   gcloud config set project VOTRE_PROJECT_ID
   ```

5. **Vérifier la configuration** :
   ```bash
   gcloud config list
   ```

## Obtention des Secrets WIF_PROVIDER et GCP_SA_EMAIL

### 1. WIF_PROVIDER (Workload Identity Federation Provider)
C'est l'identifiant du fournisseur d'identité qui permet à GitHub Actions de s'authentifier auprès de Google Cloud.
Format : `projects/PROJECT_NUMBER/locations/global/workloadIdentityPools/POOL_ID/providers/PROVIDER_ID`

Pour l'obtenir :

1. Créer un Workload Identity Pool :
```bash
gcloud iam workload-identity-pools create "github-actions-pool" \
  --location="global" \
  --display-name="GitHub Actions Pool"
```

2. Créer un Provider dans ce pool : (attention à bien renseigner un attribute-condition )
```bash
  gcloud iam workload-identity-pools providers create-oidc github-provider-3 \
  --project="PROJECT_ID" \
  --location="global" \
  --workload-identity-pool="github-actions-pool-3" \
  --issuer-uri="https://token.actions.githubusercontent.com" \
  --allowed-audiences="github-actions" \
  --attribute-mapping="google.subject=assertion.sub,attribute.actor=assertion.actor,attribute.repository=assertion.repository,attribute.repository_owner=assertion.repository_owner" \
  --attribute-condition="assertion.repository=='XavierAndreges/API_symfony'"

```

3. Obtenir le WIF_PROVIDER :
```bash
gcloud iam workload-identity-pools providers describe "github-provider-3" \
  --location="global" \
  --workload-identity-pool="github-actions-pool-3" \
  --format="value(name)"
```

### 2. GCP_SA_EMAIL (Service Account Email)
C'est l'email du Service Account qui sera utilisé par GitHub Actions pour accéder à GCP.
Format : `SERVICE_ACCOUNT_NAME@PROJECT_ID.iam.gserviceaccount.com`

Pour l'obtenir :

1. Créer un Service Account :
```bash
gcloud iam service-accounts create "github-actions-sa" \
  --display-name="GitHub Actions Service Account"
```

2. L'email du Service Account sera :
```bash
github-actions-sa@VOTRE_PROJECT_ID.iam.gserviceaccount.com
```

3. Donner les permissions nécessaires au Service Account :
```bash
gcloud projects add-iam-policy-binding VOTRE_PROJECT_ID \
  --member="serviceAccount:github-actions-sa@VOTRE_PROJECT_ID.iam.gserviceaccount.com" \
  --role="roles/secretmanager.secretAccessor"

gcloud projects add-iam-policy-binding VOTRE_PROJECT_ID \
  --member="serviceAccount:github-actions-sa@VOTRE_PROJECT_ID.iam.gserviceaccount.com" \
  --role="roles/container.developer"
```

4. Lier le Service Account au Workload Identity Provider :
```bash
gcloud iam service-accounts add-iam-policy-binding "github-actions-sa@VOTRE_PROJECT_ID.iam.gserviceaccount.com" \
  --role="roles/iam.workloadIdentityUser" \
  --member="principalSet://iam.googleapis.com/projects/VOTRE_PROJECT_NUMBER/locations/global/workloadIdentityPools/github-actions-pool/attribute.repository/VOTRE_ORGANISATION/VOTRE_REPO"
```

## Configuration des Secrets dans GitHub

Une fois ces étapes effectuées, vous aurez :
- `WIF_PROVIDER` : l'identifiant du provider (commence par `projects/...`)
- `GCP_SA_EMAIL` : l'email du service account (format `github-actions-sa@VOTRE_PROJECT_ID.iam.gserviceaccount.com`)

Ces valeurs devront être ajoutées dans les secrets de votre repository GitHub :
1. Allez dans votre repository GitHub
2. Cliquez sur "Settings" > "Secrets and variables" > "Actions"
3. Ajoutez deux nouveaux secrets :
   - `WIF_PROVIDER` avec la valeur obtenue
   - `GCP_SA_EMAIL` avec l'email du service account 




->>>>> La liaison au service account GCP avec IAM policy binding