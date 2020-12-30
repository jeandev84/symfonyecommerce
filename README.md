# Symfony Ecommerce

```markdown
#Link MAMP :
- mamp.info/enc/mac

#Install Symfony CLI Globally
Linux: wget https://get.symfony.com/cli/installer -O - | bash
Mac: curl -sS https://get.symfony.com/cli/installer | bash

#Install Symfony Project website-skeleton
symfony new ecommerce --full


#Lunch Server
symfony serve
symfony serve -d (pour ne pas afficher les logs et continuer a lancer les autres commandes)
symfony server:log (pour afficher les logs du server)


# Create Controller
symfony console (affiche tout pour la console)
symfony console make:controller HomeController

# Espace membre
Phase 1: Creation de l'entite User()
Phase 2: Creation d' un formulaire d' inscription
Phase 3: Creation d' un formulaire de connexion 
Phase 4: Creation d' un espace prive (membre)

1. symfony console make:user
2. symfony console doctrine:database:create
3. symfony console make:migration
4. symfony console doctrine:migrations:migrate
```