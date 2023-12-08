# Project Amortization

This PHP project is designed to manage project amortizations, process payments, and optimize the amortization processing function for enhanced performance.

To configure the function, navigate to the sendEmail function in the `util/sendEmail.php` file. Update lines 19 and 20 with your personal email and password. Additionally, in the `index.php` file, modify lines 26/27, changing the email to the desired recipient for notifications.

## Files Overview
### index.php
This file serves as an entry point and is used to set up a "production environment" for testing the `projectAmortizationOptimize` function.

### Amortization.php
The `Amortization.php` class manages instances of amortization. It includes a function `processPaymentsOnAmortization` responsible for handling payments and sending notifications.

### GlobalGroup.php
The `GlobalGroup.php` class manages groups of project members.

### Member.php
The `Member.php` class represents individual project members.

### Payment.php
This class represents payment instances associated with amortizations.

### Project.php
The Project class represents projects, including functions for finding a project and adding amortizations.

### Promoter.php
The `Promoter.php` class represents project promoter.

-----

# tests

### processPaymentsOnAmortizationTest.php
This file contains PHPUnit test cases for the sensitive processPaymentsOnAmortization function, ensuring robustness against various payment scenarios.

## How to Run Tests
To run tests, execute the following command in the terminal:

## Important Points
- For testing purposes, comment lines 96 and 97 in `Amortization.php`, and insert `return $reasonsString;` instead. 

```bash
php vendor/bin/phpunit test/AmortizationTest.php.php

php vendor/bin/phpunit test/ProjectTest.php
```

# DATABASE EXPLANATION
In the project's root directory, an image with the database schema is provided.

In addition to the three initial tables (projects, amortizations, payments), I found it interesting and advantageous to add two more tables.

The first one, `global groups`, seemed interesting to have a table that gathers all the members of a project because not only does it make it easier to send notifications if something has happened with the project, but we can also later analyze the group's and members' history.

The second one, `members`, is used to store specific information about a member (email, name, username), and if it makes sense later, we can add more columns (nationality, age, etc) to understand patterns that exist for certain projects and collect more information to the markting/sales team.
