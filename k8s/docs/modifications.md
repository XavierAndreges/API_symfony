# Modifications dans le Projet Symfony et Prise en Compte dans les Pods

## Question
**Q : Si j'apporte des modifications à mon projet Symfony, est-ce que les fichiers modifiés vont être pris en compte dans mes pods ?**

**R :** Pour que les modifications apportées à ton projet Symfony soient prises en compte dans tes pods Kubernetes, cela dépend de la façon dont tu as configuré ton déploiement et de la manière dont les fichiers sont montés dans les pods. Voici quelques points à considérer :

1. **Utilisation de Volumes** :  
   Si tu utilises un volume pour monter les fichiers de ton projet Symfony dans les pods (comme un `PersistentVolumeClaim` ou un `hostPath`), les modifications que tu apportes aux fichiers sur ton système de fichiers local seront reflétées dans les pods, à condition que le volume soit correctement monté et que les permissions soient correctes.

2. **Redémarrage des Pods** :  
   Si tu as modifié des fichiers qui nécessitent un redémarrage des pods (par exemple, des fichiers de configuration PHP ou des fichiers de dépendances), tu devras redémarrer les pods pour que les modifications prennent effet. Tu peux le faire en supprimant les pods existants, et Kubernetes les recréera automatiquement avec les nouvelles configurations.

3. **Utilisation de `kubectl port-forward`** :  
   Si tu utilises `kubectl port-forward` pour accéder à ton application, les modifications que tu apportes aux fichiers seront visibles dans le pod, mais tu devras peut-être redémarrer le pod pour que les changements soient pris en compte, surtout si tu as modifié des fichiers de configuration.

4. **Vérification des Logs** :  
   Pour t'assurer que les modifications sont bien prises en compte, tu peux vérifier les logs des pods pour voir si des erreurs ou des messages indiquent que les fichiers ont été modifiés ou si des problèmes surviennent.

En résumé, si tu utilises des volumes pour monter les fichiers de ton projet, les modifications seront reflétées dans les pods. Cependant, pour les changements qui nécessitent un redémarrage (comme les fichiers de configuration), tu devras redémarrer les pods. Si tu as besoin d'aide pour redémarrer les pods ou pour vérifier les logs, fais-le moi savoir ! 