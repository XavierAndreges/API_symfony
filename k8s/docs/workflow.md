# Workflow de déploiement Kubernetes avec secrets GCP

## ✅ Vérification de la configuration

### 1. SecretProviderClass
- Le fichier `k8s/base/secret-store.yaml` référence les secrets GCP nécessaires (`symfony-db-credentials`, `symfony-app-secret`, `google-maps-api-key`).
- La section `secretObjects` synchronise le secret GCP `google-maps-api-key` en un secret Kubernetes natif, utilisable par les pods.

### 2. Déploiement
- Dans `k8s/base/deployment.yaml`, le `SecretProviderClass` nommé `symfony-secrets` est utilisé pour monter les secrets dans le pod.
- Les variables d'environnement sont injectées via des `secretKeyRef` qui correspondent aux secrets synchronisés.
- Les volumes, services, ingress, et autres ressources sont bien structurés.

### 3. Cohérence
- Les noms des secrets, des volumes, et des références sont cohérents entre tous les fichiers.
- Le provider GCP est bien utilisé.
- Rien d'inutile ou de contradictoire dans la configuration.

**→ La configuration est correcte pour un déploiement Kubernetes avec secrets GCP synchronisés.**

---

## 🚀 Étapes à partir du moment où tu fais un push

### 1. Push sur GitHub
- Pousser les modifications sur la branche principale (ou une branche de déploiement).

### 2. Déclenchement du workflow GitHub Actions
- Un workflow GitHub Actions (ex : `.github/workflows/deploy.yml`) se déclenche automatiquement.
- Ce workflow doit :
  - Récupérer le code.
  - Se connecter à ton cluster Kubernetes (via un service account ou un secret Kubeconfig).
  - Se connecter à Google Cloud (via un secret de type service account JSON).
  - Exporter les secrets nécessaires comme variables d'environnement (ou les passer à `kubectl`/`kustomize`).

### 3. Déploiement sur Kubernetes
- Le workflow applique les manifests K8s :
  - `kubectl apply -k k8s/base` (ou équivalent)
- Le CSI driver va chercher les secrets dans GCP et les synchroniser en secrets Kubernetes natifs.
- Les pods démarrent, récupèrent les secrets via les `secretKeyRef` et les montages de volumes.

### 4. Vérification
- Vérifier le déploiement avec :
  - `kubectl get pods`
  - `kubectl get secrets`
  - `kubectl logs <pod>`
- Les applications devraient fonctionner avec les secrets injectés automatiquement.

---

## 📝 Résumé des étapes

1. **git push**
2. **GitHub Actions** s'exécute (build, login GCP, login K8s, déploiement)
3. **Secrets GCP** synchronisés automatiquement dans K8s via le CSI driver
4. **Pods** démarrent avec les secrets injectés
5. **Vérification** du bon fonctionnement

---
