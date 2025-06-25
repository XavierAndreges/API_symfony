# Architecture des Deployments Kubernetes

---

## 1. Pourquoi des doublons dans le fichier YAML ?

Dans le fichier `k8s/base/deployment.yaml`, tu as **deux objets Deployment** :
- Un pour `symfony-php`
- Un pour `symfony-nginx`

Chacun de ces déploiements a sa propre section :
- `spec`
- `initContainers`
- `volumes`
- etc.

C'est pourquoi tu retrouves des blocs identiques comme :
- Le volume `symfony-code`
- Le volume `nginx-config`
- L'`initContainer` qui fait un `chmod`
- Le montage du code Symfony

---

## 2. Ce que ça donne sur le cloud

### **Deux Deployments distincts**
- **symfony-php**
  - Crée un ou plusieurs pods (selon le nombre de replicas, ici 1)
  - Chaque pod contient **un container** : `php` (ton image Symfony PHP)
- **symfony-nginx**
  - Crée un ou plusieurs pods (selon le nombre de replicas, ici 1)
  - Chaque pod contient **un container** : `nginx` (ton image Nginx)

### **Ce que tu auras sur le cloud**
- **2 Deployments** (symfony-php, symfony-nginx)
- **2 Pods** (un pour chaque deployment, car `replicas: 1`)
- **Chaque pod** a :
  - Un ou plusieurs volumes montés (`symfony-code`, `nginx-config`, etc.)
  - Un initContainer pour le chmod
  - **Un seul container principal** (soit PHP, soit Nginx)

### **Schéma visuel**
```
[Deployment symfony-php] ---> [Pod symfony-php]
                                 |
                                 |-- [InitContainer: init-chmod]
                                 |-- [Container: php]
                                 |-- [Volumes: symfony-code, secrets-store]

[Deployment symfony-nginx] ---> [Pod symfony-nginx]
                                 |
                                 |-- [InitContainer: init-chmod]
                                 |-- [Container: nginx]
                                 |-- [Volumes: symfony-code, nginx-config]
```

---

## 3. Répartition des volumes

### **Pod PHP (symfony-php)**
- **Volume `symfony-code`** : ✅ Nécessaire (code de l'application)
- **Volume `secrets-store`** : ✅ Nécessaire (secrets GCP)
- **Volume `nginx-config`** : ❌ Supprimé (pas nécessaire)

### **Pod Nginx (symfony-nginx)**
- **Volume `symfony-code`** : ✅ Nécessaire (fichiers statiques)
- **Volume `nginx-config`** : ✅ Nécessaire (configuration Nginx)

---

## 4. Pourquoi cette architecture ?

### **Séparation des responsabilités**
- **Pod PHP** : Exécute le code Symfony, a besoin des secrets
- **Pod Nginx** : Sert les fichiers statiques et fait le reverse proxy vers PHP
- **Volume partagé** : Les deux pods accèdent au même code via le PVC `symfony-code-pvc`

### **Bonne pratique Kubernetes**
- Un container = un process principal par pod (sauf cas très particuliers)
- Séparation des responsabilités : PHP et Nginx sont isolés
- Tu peux scaler ou redémarrer l'un sans toucher à l'autre

---

## 5. Ce que tu verras avec kubectl

```bash
kubectl get pods
```
→ Tu verras deux pods :
- `symfony-php-xxxxxx`
- `symfony-nginx-xxxxxx`

Chacun avec son container principal.

---

## Résumé

- **Oui, c'est normal** d'avoir des blocs identiques dans un même fichier YAML si tu déclares plusieurs ressources similaires
- **Tu auras deux pods**, chacun avec un seul container (php ou nginx)
- **Chaque pod n'a que les volumes dont il a réellement besoin**
- **C'est la structure la plus classique et recommandée** pour ce type d'architecture

---

**Si tu veux éviter la duplication, tu peux utiliser Kustomize, Helm, ou des templates, mais pour un fichier YAML natif, c'est tout à fait normal !** 