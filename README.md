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
symfony server:start (Question answer Yes/NO) ? n  (Enter)
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


# Make form
symfony console make:form
config/packages/twig.yaml
twig:
 default_path: '%kernel.project_dir%/templates'
 form_themes: ['bootstrap_4_layout.html.twig']

# Make Entity (mise ajour de l'entite User)
symfony console make:entity User

config/packages/translation.yaml
framework:
    default_locale: fr # langue locale par default
    translator:
      default_path: '%kernel.project_dir%/translations'
      fallbacks:
        - en


# Make Guard Authentificator
symfony console make:auth
email: john@doe.com
password: qwerty


# Make Account Controller for users management account
symfony console make:controller

# Debug routes
symfony console debug:router

```

BACKOFFICE : EasyAdmin
```markdown
composer require easycorp/easyadmin-bundle

CREATE DASHBOARD
symfony console make:admin:dashboard
symfony console make:admin:crud

CREATE ENTITY CATEGORY
symfony console make:entity Category
symfony console make:migration
symfony console doctrine:migrations:migrate

symfony console make:admin:crud


CREATE ENTITY PRODUCT
symfony console make:entity Product
symfony console make:migration
symfony console doctrine:migrations:migrate

=============================
ICONS:
https://flaticon.com
https://www.flaticon.com/search?word=plus&k=1609384768215

# Make entity Address
symfony console make:entity Address
symfony console make:controller


# Make entity Carrier (Transport courriel DHL,COLISSIMO ...)
symfony console make:entity Carrier
symfony console make:migration
symfony console doctrine:migrations:migrate (d:m:m)


# Make entity Order (Entity pour la gestion de mes Commandes)
On va lier une Commande a un utilisateur
symfony console make:entity Order
symfony console make:migration
symfony console doctrine:migrations:migrate (d:m:m)


# Make entity OrderDetails  (Stock les details de la commande)
symfony console make:entity OrderDetails
symfony console make:migration
symfony console doctrine:migrations:migrate (d:m:m)

# PAIEMENT STRIPE
https://stripe.com/
Francais: https://stripe.com/fr

https://stripe.com/fr/payments/checkout

Essayer:
https://checkout.stripe.dev/
One-time payments (cas d' une boutique en ligne)
https://checkout.stripe.dev/preview

TESTS CARD
https://stripe.com/docs/
https://stripe.com/docs/payments/checkout
https://dashboard.stripe.com/test/apikeys

FOR WORKING WITH API
https://stripe.com/docs/checkout/integration-builder

1. Install Stripe
composer require stripe/stripe-php


CARD TEST
NUMBER CARD TEST : 4242 4242 4242 4242
Date Expired 12/33  CODE CVV/CVC 631

DASHBOARD/PAYMENTS
https://dashboard.stripe.com/test/payments?status%5B%5D=successful
```

ADMIN
```markdown
LOGIN: john@doe.com/jeanyao@ymail.com
PASS: 123456
```

SYSTEM MAIL (Notification)
```markdown
Service Symfony Component
https://symfony.com/doc/current/email.html

Service Mailjet
https://fr.mailjet.com/

CHOICE PHP
https://app.mailjet.com/auth/get_started/developer

https://app.mailjet.com/account/api_keys
```

PEXELS PHOTOS
```markdown 
https://www.pexels.com/ru-ru/
https://pexels.com/fr-fr/photo/citadin-diversite-espace-exterieur-exterieur-1154861/
https://pexels.com/fr-fr/photo/acheter-beau-beaute-bijoux-994234/
https://www.pexels.com/fr-fr/photo/photo-de-femme-portant-des-lunettes-de-soleil-994234/
https://www.pexels.com/fr-fr/photo/gros-plan-de-rangee-325876/

https://pexels.com/fr-fr/photo/a-l-interieur-bourse-brouiller-costumes-326876/
```

TWIG PACK (package pour la gestion des pages d' erreurs)
```markdown
composer require symfony/twig-pack

/templates/bundles/TwigBundle/Exception/error.html.twig

symfony console cache:clear

https://symfony.com/doc/current/deployment.html
```