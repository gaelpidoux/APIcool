APIcool est une API réaliser dans une combinaison entre un besoin de solution et dans le cadre de ma formation à Ynov.
Elle sera en lien avec d'autres outils internes à l'entreprise SAVOYE. Son objectif est de retourner des valeurs d'outil tel que LMD depuis une BDD.
Pour la partie vizualisation elle sera combinée avec une application mobile et un Grafana.

Pour utiliser l'API, il vous faudra vous connecter à l'aide d'un compte les identifiants sont dans l'exemple de la doc.
Pour accéder à la documentation de l'API : http://localhost:8000/api/doc

Commandes à réaliser : ```composer install ``` ```composer update``` ```php bin/console doctrine:database:create``` ```php bin/console doctrine:schema:update --force``` ```php bin/console doctrine:fixtures:load```

Modifier .env les ChangeMe
Certifica dans le repo JWT_PASSPHRASE=b39b1984e86dca3a380b4dda80e6d1d9

User à utiliser: admin password

PS: Pour cette partie, quatre points sont importants :

    L'API ne stockera pas les connexions client, c'est pourquoi je ne me suis pas embêté à encoder les mots de passe de dataclient (car là c'est du test) (pour les utilisateurs, par contre, oui).

    La partie métier est TRES simplifiée. Cela peut donner l'impression que l'API est simpliste, mais j'ai regroupé exprès les vérifications les plus simple dans la table devclientprincipalverif.

    Je n'ai mis qu'un getAllclient avec du cache parce que toutes mes autres tables évoluent constamment et ne sont jamais static (sauf arrêt de la bdd)

    la table request ne se met à jour qu'à la creation d'une dataconnection car c'est par cette méthode que l'api en interne ce mettra à jour (en gros mon create sonde la base et stock les infos)

Voilà voilà, Enjoy!
