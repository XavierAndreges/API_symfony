# Différence entre `secrets` et `secretObjects` dans SecretProviderClass (CSI driver)

---

## 1. `secrets`

- **But** : Indique au CSI driver quels secrets il doit aller chercher dans Google Secret Manager (ou un autre provider cloud).
- **Format** :
  ```yaml
  secrets:
    - resourceName: "projects/xxx/secrets/mon-secret/versions/latest"
      path: "mon-secret"
  ```
- **Effet** :
  - Le secret est monté dans le pod (par défaut dans un volume, ex: `/mnt/secrets/mon-secret`)
  - **Pas de création automatique** d'un secret Kubernetes natif (type `Secret`)

---

## 2. `secretObjects`

- **But** : Demande au CSI driver de **synchroniser** le secret cloud dans un secret Kubernetes natif (type `Secret`), utilisable par `secretKeyRef` dans les pods.
- **Format** :
  ```yaml
  secretObjects:
    - secretName: mon-secret-k8s
      type: Opaque
      data:
        - objectName: mon-secret
          key: MA_CLE
  ```
- **Effet** :
  - Le secret cloud est **copié** dans un secret Kubernetes natif
  - Tu peux l'utiliser dans `envFrom`, `secretKeyRef`, etc.

---

## 3. Pourquoi n'avoir que `GOOGLE_MAPS_API_KEY` dans `secretObjects` ?

- **Actuellement** : Seul le secret `google-maps-api-key` est synchronisé en secret Kubernetes natif.
- **Conséquence** :
  - Seul ce secret est accessible via `secretKeyRef` dans tes pods.
  - Les autres (`symfony-db-credentials`, `symfony-app-secret`) ne seront accessibles **que** via le volume monté (`/mnt/secrets/...`), **sauf** si tu les ajoutes aussi dans `secretObjects`.

---

## 4. Bonnes pratiques

- **Si tu veux utiliser tous tes secrets comme des secrets Kubernetes natifs** (via `secretKeyRef`), ajoute-les tous dans `secretObjects` :
  ```yaml
  secretObjects:
    - secretName: symfony-db-credentials
      type: Opaque
      data:
        - objectName: symfony-db-credentials
          key: DATABASE_URL
    - secretName: symfony-app-secret
      type: Opaque
      data:
        - objectName: symfony-app-secret
          key: APP_SECRET
    - secretName: google-maps-api-key
      type: Opaque
      data:
        - objectName: google-maps-api-key
          key: GOOGLE_MAPS_API_KEY
  ```
- **Si tu veux juste les monter comme fichiers** dans le pod, laisse-les dans `secrets` uniquement.

---

## Résumé

- **`secrets`** = ce que le CSI va chercher dans le cloud (monté comme fichier dans le pod)
- **`secretObjects`** = ce que le CSI synchronise en secret Kubernetes natif (utilisable par `secretKeyRef`)
- **Pour utiliser un secret dans `env` ou `secretKeyRef`, il doit être dans `secretObjects`**

---

**Si tu veux que tous tes secrets soient utilisables comme secrets Kubernetes natifs, ajoute-les tous dans `secretObjects` !** 