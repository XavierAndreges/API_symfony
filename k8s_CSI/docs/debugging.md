# Débogage avec kubectl port-forward

## Question 1
**Q : Si je saisis http://localhost:8080/ j'accède à mon projet Symfony. Mais comment je sais que je passe bien par mes pods définis par K8s ?**

**R :** Pour vérifier que tu passes bien par tes pods Kubernetes, tu peux utiliser plusieurs commandes et techniques :

1. **Vérifier les logs des pods** :  
   Tu peux consulter les logs des pods nginx et PHP pour voir les requêtes qui passent. Par exemple, pour le pod nginx, exécute :
   ```bash
   kubectl logs -n symfony-dev <nom-du-pod-nginx>
   ```
   Remplace `<nom-du-pod-nginx>` par le nom réel du pod (tu peux le récupérer avec `kubectl get pods -n symfony-dev`).

2. **Vérifier les endpoints et les services** :  
   Assure-toi que ton service nginx est bien configuré pour rediriger vers le pod. Tu peux vérifier les endpoints avec :
   ```bash
   kubectl get endpoints -n symfony-dev
   ```
   Cela te montrera les adresses IP des pods associés à ton service.

3. **Utiliser `kubectl port-forward`** :  
   Pour t'assurer que tu accèdes bien à ton pod, tu peux utiliser `kubectl port-forward` pour rediriger le trafic local vers le pod. Par exemple :
   ```bash
   kubectl port-forward -n symfony-dev <nom-du-pod-nginx> 8080:80
   ```
   Ensuite, accède à `http://localhost:8080/` et vérifie les logs du pod pour confirmer que la requête passe bien par ce pod.

4. **Vérifier les annotations et les règles d'ingress** :  
   Si tu utilises un Ingress, vérifie que les règles sont correctement configurées pour rediriger le trafic vers ton service nginx.

## Question 2
**Q : Tu mentionnes "Utiliser kubectl port-forward". Quel est l'intérêt de saisir cette commande si tout fonctionne bien ?**

**R :** L'intérêt de la commande `kubectl port-forward` est de te permettre de tester et de déboguer ton application en accédant directement à un pod spécifique, même si ton service et ton Ingress sont déjà configurés pour rediriger le trafic. Voici quelques raisons pour lesquelles cela peut être utile :

1. **Débogage direct** :  
   Si tu rencontres des problèmes avec ton application et que tu veux vérifier si le problème vient du pod lui-même, tu peux utiliser `port-forward` pour accéder directement au pod et tester son comportement sans passer par le service ou l'Ingress.

2. **Tests isolés** :  
   En utilisant `port-forward`, tu peux isoler le trafic vers un pod spécifique, ce qui te permet de tester des modifications ou des configurations sans affecter les autres pods ou services.

3. **Accès temporaire** :  
   Si tu as besoin d'accéder à un pod pour des raisons de maintenance ou de développement, `port-forward` te permet de le faire rapidement sans avoir à modifier les configurations de service ou d'Ingress.

4. **Vérification des logs en temps réel** :  
   En accédant directement au pod, tu peux voir les logs en temps réel et comprendre comment les requêtes sont traitées, ce qui peut être très utile pour le débogage.

## Question 3
**Q : Et une fois qu'on a terminé le debug, y a-t-il besoin d'arrêter port-forward et une commande pour cela ?**

**R :** Oui, une fois que tu as terminé le débogage avec `kubectl port-forward`, il est important d'arrêter la redirection pour libérer le port et éviter toute confusion. Voici comment procéder :

1. **Arrêter `kubectl port-forward`** :  
   Si tu as lancé `kubectl port-forward` dans un terminal, tu peux simplement arrêter le processus en appuyant sur `Ctrl + C`. Cela mettra fin à la redirection de port.

2. **Vérifier que le port est libéré** :  
   Tu peux vérifier que le port est bien libéré en utilisant la commande suivante (sur macOS ou Linux) :
   ```bash
   lsof -i :8080
   ```
   Si le port est libéré, cette commande ne devrait rien retourner.

En résumé, pour arrêter `kubectl port-forward`, il suffit d'interrompre le processus dans le terminal où il a été lancé. Cela garantit que le port est libéré et que tu peux continuer à utiliser ton application normalement. 