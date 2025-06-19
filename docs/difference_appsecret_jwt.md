# Différence entre APP_SECRET, JWT_SECRET_KEY et JWT_PASSPHRASE

Voici la différence d'utilisation entre `APP_SECRET`, `JWT_SECRET_KEY` et `JWT_PASSPHRASE` dans un projet Symfony/API moderne :

---

## 1. **APP_SECRET**

- **Usage** : C'est la clé secrète principale de Symfony.
- **À quoi ça sert** :
  - Génération et validation des tokens CSRF (protection des formulaires)
  - Signature/chiffrement des sessions
  - Signature de certains cookies sécurisés
  - Chiffrement de données sensibles via le composant `secrets` de Symfony
- **Où est-elle utilisée** :  
  - Dans `config/packages/framework.yaml` :  
    ```yaml
    framework:
      secret: '%env(APP_SECRET)%'
    ```
  - Par le framework lui-même, pas pour le JWT.

---

## 2. **JWT_SECRET_KEY**

- **Usage** : C'est la clé privée utilisée pour signer les tokens JWT (JSON Web Token) dans une API.
- **À quoi ça sert** :
  - Générer des tokens JWT valides (authentification API, OAuth, etc.)
  - Vérifier la validité des tokens JWT côté serveur
- **Où est-elle utilisée** :
  - Par des bundles comme `lexik/jwt-authentication-bundle` ou tout système d'auth JWT
  - Dans la config du bundle, ex :  
    ```yaml
    lexik_jwt_authentication:
      secret_key: '%env(JWT_SECRET_KEY)%'
    ```
- **Format** : Peut être une clé symétrique (string) ou une clé privée RSA/EC (fichier PEM).

---

## 3. **JWT_PASSPHRASE**

- **Usage** : C'est le mot de passe (passphrase) qui protège la clé privée JWT si celle-ci est chiffrée (ex : clé RSA protégée par un mot de passe).
- **À quoi ça sert** :
  - Déverrouiller la clé privée lors de la génération des tokens JWT
- **Où est-elle utilisée** :
  - Toujours avec une clé privée protégée (souvent un fichier PEM)
  - Dans la config du bundle JWT, ex :  
    ```yaml
    lexik_jwt_authentication:
      pass_phrase: '%env(JWT_PASSPHRASE)%'
    ```
- **Format** : Un mot de passe texte, jamais commité.

---

## **Résumé visuel**

| Variable           | Utilité principale                  | Utilisée par         | Format         |
|--------------------|-------------------------------------|----------------------|---------------|
| `APP_SECRET`       | Sécurité Symfony (sessions, CSRF…)  | Symfony core         | String        |
| `JWT_SECRET_KEY`   | Signature des tokens JWT            | Bundle JWT/API       | String ou PEM |
| `JWT_PASSPHRASE`   | Déverrouiller la clé privée JWT     | Bundle JWT/API       | String        |

---

**En résumé** :  
- `APP_SECRET` = sécurité interne Symfony  
- `JWT_SECRET_KEY` = sécurité des tokens API  
- `JWT_PASSPHRASE` = mot de passe pour la clé privée JWT (si chiffrée)

Si tu veux des exemples de config ou de code pour chaque cas, demande-moi ! 