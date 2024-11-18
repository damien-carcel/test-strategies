# Testing Strategies

## Plan

### Introduction

- Rappels sur ce qu'est un « bon » test => F.I.R.S.T. (article medium qui synthétise bien le sujet)
- Les différents types de tests : unitaires, intégration, end-to-end, applicatif/fonctionnel
  - On reste très simple sur cette partie : les définitions de ces termes varient beaucoup selon les sources, ce qui compte, c'est ce qu'ils signifient pour nous, qu'on soit tous d'accord sur le sens du terme quand on l'emploie.

### L'état des tests chez Obat

- Revenir à F.I.R.S.T. et insister qu'il y a déjà eu beaucoup de progrès entre le monolithe et les services : tests plus clairs, fixtures propres et indépendantes, pas de data conservées entre les tests
- Mais peux mieux faire :
  - Trop de tests utilisant la base de donnée => relativement lents
  - Mélange au sein des "tests d'intégration" de tests métiers et de tests d'adaptateurs (au sens archi hexagonale)
  - Utilisation massive des mocks dans les tests unitaires, rendant les tests couplés à l'implémentation ⇒ fragiles et rendant la refacto pénible
    - On peut rappeler au passage les différents « doubles » existant (in memory, stub, mock)

### Comment mieux faire

- Rappel de l'archi hexagonale via un schéma pour introduire la notion de "test boundaries"
- Présentation du concept de « tests d'acceptance » : des tests métier, appelant le command et query (use-cases) et utilisant un adaptateur « in-memory» pour les ports « contrôlés » (à droite de l'hexagone) : DB, système de fichier, temps, etc.
  - Les tests eux-mêmes peuvent être vus comme un adaptateur des ports « contrôlant » (à gauche de l'hexagone)
- Les tests d'intégrations viennent compléter les tests d'acceptance, en testant toutes les implémentations des adaptateurs contrôlés (par exemple repo Docrtine + son double in-memory) via le même test
  - Permet d'avoir pleine confiance dans les tests d'acceptance puisque les adaptateurs « in-memory » sont fiables
  - Les actuels tests d'intégration testant des contrôleurs et commandes Symfony sont en fait des tests « end-to-end» et doivent être réduits au strict minimum, uniquement sur les chemins critiques
- Les tests de contrats peuvent être lancés en « in-memory ».

### Questions/Échanges

- Prévoir du temps à la fin de la présentation.

## Example

- Simple use case of updating an existing user
  - Need to retrieve the existing user through a find
  - Refactor to use a get instead
    - Classic unit-test with mock will be broken by this simple refacto
    - Acceptance test will not
      - Acceptance test needs in memory storage to be as fast as unit test with mock
- Update of a user password leads to warning email
  - We want to make the email being sent through an event subscriber reacting to the password update
    - Here again, mocks will get in the way as we have to mock the event bus and test handler and subscriber separately
      Acceptance test will still work without any change in such a case
