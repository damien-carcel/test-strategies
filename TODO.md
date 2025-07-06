# Testing Strategies

## Plan

### Introduction

- Reminder of what makes a "good" test ⇒ F.I.R.S.T. (Medium article that summarizes the topic well).
- The different types of tests: unit, integration, end-to-end, application/functional.
  - Keep it very simple for this part: the definitions of these terms vary greatly depending on the sources. What matters is what they mean to us, ensuring we all agree on the meaning of the term when we use it.

### The state of tests at Obat

- Return to F.I.R.S.T. and emphasize that there has already been significant progress between the monolith and the services: clearer tests, clean and independent fixtures, no data retained between tests.
- But there is room for improvement:
  - Too many tests using the database ⇒ relatively slow.
  - Mixing within "integration tests" of business tests and adapter tests (in the sense of hexagonal architecture).
  - Massive use of mocks in unit tests, making tests coupled to the implementation ⇒ fragile and making refactoring painful.
    - We can recall the different "doubles" that exist (in-memory, stub, mock).

### How to improve

- Reminder of hexagonal architecture via a diagram to introduce the notion of "test boundaries."
- Presentation of the concept of "acceptance tests": business tests, calling the command and query (use cases) and using an "in-memory" adapter for the "controlled" ports (on the right side of the hexagon): DB, file system, time, etc.
  - The tests themselves can be seen as an adapter for the "controlling" ports (on the left side of the hexagon).
- Integration tests complement acceptance tests by testing all implementations of controlled adapters (e.g., Doctrine repo + its in-memory double) via the same test.
  - Allows full confidence in acceptance tests since the "in-memory" adapters are reliable.
- Current integration tests that test Symfony controllers and commands are actually "end-to-end" tests and should be reduced to the bare minimum, only on critical paths.
  - Like acceptance tests, these tests do not break during refactoring.
- Contract tests can be run "in-memory."

### Questions/Discussion

- Allocate time at the end of the presentation.

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

- Serve the API with Franklin PHP.
