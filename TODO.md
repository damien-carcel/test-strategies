# Testing Strategies

## Plan

### Introduction

- Rappels sur ce qu'est un « bon » test ⇒ F.I.R.S.T. (article medium qui synthétise bien le sujet).
- Les différents types de tests : unitaires, intégration, end-to-end, applicatif/fonctionnel.
  - On reste très simple sur cette partie : les définitions de ces termes varient beaucoup selon les sources, ce qui compte, c'est ce qu'ils signifient pour nous, qu'on soit tous d'accord sur le sens du terme quand on l'emploie.

### L'état des tests chez Obat

- Revenir à F.I.R.S.T. et insister qu'il y a déjà eu beaucoup de progrès entre le monolithe et les services : tests plus clairs, fixtures propres et indépendantes, pas de data conservées entre les tests.
- Mais peut mieux faire :
  - Trop de tests utilisant la base de donnée ⇒ relativement lents.
  - Mélange au sein des "tests d'intégration" de tests métiers et de tests d'adaptateurs (au sens archi hexagonale).
  - Utilisation massive des mocks dans les tests unitaires, rendant les tests couplés à l'implémentation ⇒ fragiles et rendant la refacto pénible.
    - On peut rappeler au passage les différents « doubles » existant (in memory, stub, mock).

### Comment mieux faire

- Rappel de l'archi hexagonale via un schéma pour introduire la notion de "test boundaries"
- Présentation du concept de « tests d'acceptance » : des tests métier, appelant le command et query (use cases) et utilisant un adaptateur « in memory » pour les ports « contrôlés » (à droite de l'hexagone) : DB, système de fichier, temps, etc.
  - Les tests eux-mêmes peuvent être vus comme un adaptateur des ports « contrôlant » (à gauche de l'hexagone)
- Les tests d'intégrations viennent compléter les tests d'acceptance, en testant toutes les implémentations des adaptateurs contrôlés (par exemple repo Docrtine + son double in-memory) via le même test.
  - Permet d'avoir pleine confiance dans les tests d'acceptance puisque les adaptateurs « in-memory » sont fiables
- Les actuels tests d'intégration testant des contrôleurs et commandes Symfony sont en fait des tests « end-to-end» et doivent être réduits au strict minimum, uniquement sur les chemins critiques.
  - Comme les testst d'acceptance, ces tests ne cassent pas en cas de refacto.
- Les tests de contrats peuvent être lancés en « in-memory ».

### Questions/Échanges

- Prévoir du temps à la fin de la présentation.

## Example

- Simple use case of creating user account:
  - Try to find a user with the same email ⇒ Will throw an exception if the user already exists.
    - Just adding this feature breaks the unit test, as this repo call was not mocked previously, only the save was.
  - Send an e-mail to the user to notify them their account was created.
  - Tested both with unit tests and acceptance tests
- Two refactorizations to be done each in a dedicated commit (will allow to link them in the presentation):
  - Use a "get" instead of a "find" in the repository.
  - Send the e-mail in a separate service following the dispatch of an event.
  - Classic unit-test with mock will be broken by this simple refacto, acceptance test will not be.

## Tasks

- Move the presentation in a dedicated "presentation" sub-folder.
- Update to Symfony 7.3
- Update to PHPUnit 12.
- Move VS Code project settings to editorconfig.