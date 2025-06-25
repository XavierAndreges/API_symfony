Pour utiliser Google Secret Manager CSI Driver avec un cluster Autopilot, tu n’as rien à activer manuellement. Tu peux directement :

Créer un SecretProviderClass :

Vérifier que les bonnes permissions IAM sont en place.

Monter le secret dans un pod.

////////******* CECI NE FONCTIONNE QUE SI TU AS BIEN AUTORISE LE CSI DRIVER A LA CREATION DU CLUSTER - SINON PLUS RIEN À FAIRE -> RECOMMENCER ***************************


# 1. Vérifier si le Secret Manager CSI Driver est activé
gcloud container clusters describe symfony-cluster --region=VOTRE_REGION | grep secretManagerConfig

# 2. Si pas activé, l'activer
gcloud container clusters update symfony-cluster \
    --region=VOTRE_REGION \
    --workload-pool=trans-cosine-460409-m6.svc.id.goog \
    --enable-secret-manager-csi-driver

# 3. Créer vos secrets dans Google Secret Manager
gcloud secrets create symfony-env --data-file=.env
gcloud secrets create database-url --data-file=-  # puis tapez l'URL et Ctrl+D

# 4. Configurer les permissions IAM
gcloud secrets add-iam-policy-binding symfony-env \
    --member="serviceAccount:symfony-sa@trans-cosine-460409-m6.iam.gserviceaccount.com" \
    --role="roles/secretmanager.secretAccessor"

# 5. Appliquer la SecretProviderClass
kubectl apply -f secret-provider-class.yaml




1. ✅ Activer les APIs GCP nécessaires
Si ce n’est pas déjà fait :

gcloud services enable \
  container.googleapis.com \
  secretmanager.googleapis.com \
  iamcredentials.googleapis.com

gcloud container clusters describe symfony-cluster --region=europe-west1 | grep secretManagerConfig

gcloud container clusters update symfony-cluster \
  --region europe-west1 \
  --project trans-cosine-460409-m6 \
  --enable-secret-manager-csi-driver





✅ Vérification de l’installation
Après ces commandes :

kubectl get pods -n kube-system -l app=secrets-store-csi-driver
kubectl get daemonset -n kube-system
kubectl get crds | grep secretprovider

Tu devrais voir le DaemonSet secrets-store-csi-driver en statut Running, ainsi que les CRDs SecretProviderClass et SecretProviderClassPodStatus


🔐 Étape 3 – Créer une SecretProviderClass
Cela permet de dire : "ce pod va aller chercher tel secret de Secret Manager".

Exemple YAML :

apiVersion: secrets-store.csi.x-k8s.io/v1
kind: SecretProviderClass
metadata:
  name: gcp-secrets
  namespace: default
spec:
  provider: gcp
  parameters:
    secrets: |
      - resourceName: "projects/PROJECT_ID/secrets/symfony-app-secret/versions/latest"
        fileName: "symfony-app-secret"
🔁 Remplace PROJECT_ID par l’ID de ton projet GCP.

📥 Étape 4 – Monter le secret dans un pod

apiVersion: v1
kind: Pod
metadata:
  name: example-pod
spec:
  serviceAccountName: my-k8s-serviceaccount
  containers:
  - name: app
    image: nginx
    volumeMounts:
    - name: secrets-store
      mountPath: "/mnt/secrets"
      readOnly: true
  volumes:
  - name: secrets-store
    csi:
      driver: secrets-store.csi.k8s.io
      readOnly: true
      volumeAttributes:
        secretProviderClass: "gcp-secrets"


✅ Étape 5 – Vérification
Pod fonctionne ? → kubectl logs example-pod

Fichier monté ? → kubectl exec -it example-pod -- cat /mnt/secrets/symfony-app-secret

🧠 Remarques
Ton service account GKE (via Workload Identity) doit avoir les droits roles/secretmanager.secretAccessor sur les secrets que tu veux lire.

Si tu utilises GitHub Actions → vérifie que le Workload Identity Federation est bien configuré.