Pour utiliser Google Secret Manager CSI Driver avec un cluster Autopilot, tu n’as rien à activer manuellement. Tu peux directement :

Créer un SecretProviderClass :

Vérifier que les bonnes permissions IAM sont en place.

Monter le secret dans un pod.





1. ✅ Activer les APIs GCP nécessaires
Si ce n’est pas déjà fait :

gcloud services enable \
  container.googleapis.com \
  secretmanager.googleapis.com \
  iamcredentials.googleapis.com


gcloud container clusters update symfony-cluster \
  --region europe-west1 \
  --project trans-cosine-460409-m6 \
  --enable-secret-manager-csi-driver




OU


1.1 - Créer le namespace csi-secrets-store

    kubectl create namespace csi-secrets-store

1.2. Appliquer les manifests du CSI Driver 
    
    kubectl apply -f https://raw.githubusercontent.com/kubernetes-sigs/secrets-store-csi-driver/v1.4.0/deploy/rbac-secretproviderclass.yaml
    kubectl apply -f https://raw.githubusercontent.com/kubernetes-sigs/secrets-store-csi-driver/v1.4.0/deploy/secrets-store-csi-driver.yaml
    kubectl apply -f https://raw.githubusercontent.com/kubernetes-sigs/secrets-store-csi-driver/v1.4.0/deploy/secrets-store.csi.x-k8s.io_secretproviderclasses.yaml
    kubectl apply -f https://raw.githubusercontent.com/kubernetes-sigs/secrets-store-csi-driver/v1.4.0/deploy/secrets-store.csi.x-k8s.io_secretproviderclasspodstatuses.yaml


Étape 2 – Installer le GCP Provider du CSI Driver

    kubectl apply -f https://raw.githubusercontent.com/GoogleCloudPlatform/secrets-store-csi-driver-provider-gcp/main/deploy/provider-gcp-plugin.yaml


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
Copier
Modifier
$mapsApiKey = $_ENV['GOOGLE_MAPS_API_KEY'] ?? null;