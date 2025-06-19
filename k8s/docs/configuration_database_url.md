# Configuration de l'URL de connexion à la base de données (DATABASE_URL) pour GCP

Lorsque tu utilises le Secret Manager pour stocker la variable `DATABASE_URL`, il est important d'adapter cette URL à ta configuration réelle sur Google Cloud Platform (GCP).

## Exemple d'URL pour MySQL

```
mysql://symfony:VOTRE_MOT_DE_PASSE@gcp-mysql-host:3306/mp?serverVersion=8.0
```

## Exemple d'URL pour MariaDB

```
mariadb://symfony:VOTRE_MOT_DE_PASSE@gcp-mysql-host:3306/mp?serverVersion=10.5
```

> **Remarque :**
> - Utilise le schéma `mariadb://` si tu utilises MariaDB.
> - Adapte la version dans `serverVersion` à la version réelle de MariaDB installée sur ta VM (ex : `10.5`, `10.6`, etc).
> - Le reste de l'URL (utilisateur, mot de passe, hôte, port, nom de la base) se configure comme pour MySQL.

## Explication des éléments à modifier

| Élément                | Exemple dans l'URL                | À modifier ? | Explication                                                                 |
|------------------------|-----------------------------------|--------------|-----------------------------------------------------------------------------|
| **Utilisateur**        | `symfony`                         | Oui/Non      | L'utilisateur MySQL/MariaDB utilisé pour se connecter. Change-le si besoin.  |
| **Mot de passe**       | `VOTRE_MOT_DE_PASSE`              | Oui          | Le mot de passe réel de l'utilisateur.                                      |
| **Hôte**               | `gcp-mysql-host`                  | Oui          | Le nom d'hôte ou l'IP de ta VM GCP où tourne la base.                       |
| **Port**               | `3306`                            | Non (sauf cas particulier) | Le port MySQL/MariaDB (par défaut 3306). Change-le si ta config est différente. |
| **Nom de la base**     | `mp`                              | Oui/Non      | Le nom de la base de données à utiliser.                                    |
| **serverVersion**      | `8.0` (MySQL) / `10.5` (MariaDB)  | Oui          | La version de MySQL ou MariaDB utilisée (doit correspondre à ta version réelle). |
| **Schéma**             | `mysql` ou `mariadb`              | Oui          | Utilise `mysql` pour MySQL, `mariadb` pour MariaDB.                         |

## Exemple concret (MariaDB)

Si :
- ton utilisateur est `symfony`
- ton mot de passe est `SuperSecret123`
- l'IP de ta VM est `34.123.45.67`
- le nom de ta base est `mp`
- la version de MariaDB est 10.5

Alors l'URL sera :

```
mariadb://symfony:SuperSecret123@34.123.45.67:3306/mp?serverVersion=10.5
```

## À retenir
- Remplace **VOTRE_MOT_DE_PASSE** par le vrai mot de passe de la base sur la VM GCP.
- Adapte **gcp-mysql-host** avec l'IP ou le nom DNS de ta VM GCP.
- Modifie l'utilisateur, le nom de la base, la version et le schéma (`mysql` ou `mariadb`) si besoin.

**Ne stocke jamais ces informations en clair dans le code source. Utilise toujours le Secret Manager ou des variables d'environnement pour la production.** 