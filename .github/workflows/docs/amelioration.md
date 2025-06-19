# Améliorations du workflow GitHub Actions

## ✅ Points forts du workflow actuel

- **Authentification sécurisée** à Google Cloud via Workload Identity Federation (bonne pratique, évite les clés statiques).
- **Utilisation de secrets GitHub** pour toutes les informations sensibles.
- **Déploiement automatisé** sur GKE avec `kustomize` et `kubectl`.
- **Mise à jour dynamique de l'image** avec le SHA du commit (très bien pour le versioning).

---

## 🔎 Améliorations possibles

### 1. **Vérification de la syntaxe et du rendu Kustomize**
Avant d'appliquer, tu peux ajouter une étape pour valider la syntaxe et voir le rendu :
```yaml
- name: 'Kustomize build (dry-run)'
  run: |
    cd k8s/prod
    kustomize build .
```
Cela permet de détecter des erreurs de rendu avant d'appliquer sur le cluster.

---

### 2. **Vérification de la santé du déploiement**
Après l'application, vérifie que les pods sont bien déployés et en bonne santé :
```yaml
- name: 'Check rollout status'
  run: |
    kubectl rollout status deployment/symfony-php -n <namespace>
    kubectl rollout status deployment/symfony-nginx -n <namespace>
```
Remplace `<namespace>` par le namespace utilisé (par défaut `default` si tu n'en utilises pas).

---

### 3. **Ajout d'un step de build d'image (optionnel)**
Si tu veux builder et pousser l'image Docker dans ce workflow (et non ailleurs), ajoute :
```yaml
- name: 'Build and push Docker image'
  uses: docker/build-push-action@v5
  with:
    context: .
    push: true
    tags: xandreges/symfony-php:prod, xandreges/symfony-php:${{ github.sha }}
```
Cela garantit que l'image existe avant le déploiement.

---

### 4. **Notifications (optionnel)**
Ajoute une notification (Slack, Teams, email…) en cas d'échec ou de succès pour être alerté rapidement.

---

### 5. **Utilisation de l'option `--prune` (avancé)**
Pour supprimer les ressources obsolètes lors du déploiement :
```yaml
kustomize build . | kubectl apply --prune -l app=symfony-php -f -
```
À utiliser avec précaution, nécessite un bon label management.

---

### 6. **Ajout d'un step de nettoyage (optionnel)**
Pour supprimer les images Docker non utilisées ou faire du ménage dans le cluster (utile sur les environnements de test).

---

## 📝 Exemple de workflow enrichi

```yaml
name: Deploy to GKE

on:
  push:
    branches: [ main ]

env:
  PROJECT_ID: ${{ secrets.GCP_PROJECT_ID }}
  GKE_CLUSTER: ${{ secrets.GKE_CLUSTER }}
  GKE_ZONE: ${{ secrets.GKE_ZONE }}

jobs:
  deploy:
    runs-on: ubuntu-latest
    permissions:
      contents: read
      id-token: write

    steps:
    - uses: actions/checkout@v3

    - id: auth
      name: 'Authenticate to Google Cloud'
      uses: google-github-actions/auth@v2
      with:
        workload_identity_provider: ${{ secrets.WIF_PROVIDER }}
        service_account: ${{ secrets.GCP_SA_EMAIL }}

    - name: 'Set up Cloud SDK'
      uses: 'google-github-actions/setup-gcloud@v1'

    - name: 'Get GKE Credentials'
      run: |
        gcloud container clusters get-credentials $GKE_CLUSTER --zone $GKE_ZONE --project $PROJECT_ID

    - name: 'Kustomize build (dry-run)'
      run: |
        cd k8s/prod
        kustomize build .

    - name: 'Deploy to GKE'
      run: |
        cd k8s/prod
        kustomize edit set image xandreges/symfony-php:prod=${{ github.sha }}
        kustomize build . | kubectl apply -f -

    - name: 'Check rollout status'
      run: |
        kubectl rollout status deployment/symfony-php
        kubectl rollout status deployment/symfony-nginx
```

---

## **Résumé**

- Le workflow est déjà solide et sécurisé.
- Les ajouts proposés permettent d'améliorer la robustesse, la visibilité et la fiabilité du déploiement.
- Adapte selon tes besoins (build d'image, notifications, etc.).

---

**Besoin d'aide pour implémenter une amélioration spécifique ? N'hésite pas à demander !** 