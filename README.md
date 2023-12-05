# Project Amortization

This PHP project involves managing project amortizations, processing payments, and optimizing the amortization processing function for better performance.


To make the function work, it is necessary to go to the `sendEmail` function in the SendEmail file and change lines 19 and 20 to your personal email and password. Finally, in the index.php file, lines 25/26, change the `email` to the one you want to send the notifications.

## Files Overview
### index.php
This file serves as an entry point and is used to set up a "production environment" for testing the projectAmortizationOptimize function.

### Amortization.php
This class represents amortization instances. It includes a function processPaymentsOnAmortization responsible for managing payments and sending notifications.

### GlobalGroup.php
The GlobalGroup class manages groups of project members.

### Member.php
The Member class represents individual project members.

### Payment.php
This class represents payment instances associated with amortizations.

### Project.php
The Project class represents projects, including functions for finding a project and adding amortizations.

### Promoter.php
The Promoter class represents project promoter.

### 

### processPaymentsOnAmortizationTest.php
I created tests for the processPaymentsOnAmortization function because it is the most sensitive function, and therefore, I need it to be very robust against any type of errors.

This file contains PHPUnit test cases for the processPaymentsOnAmortization function, checking successful and unsuccessful payment scenarios.

## How to Run Tests
To run tests, execute the following command in the terminal:

### notes: 
!IMPORTANT! if you want to run tests comment the line 96, 97, file `Amortization.php` and paste this code `return $reasonsString;` 

```bash
php vendor/bin/phpunit test/AmortizationTest.php.php

php vendor/bin/phpunit test/ProjectTest.php
```

### Important Points

The `projectAmortizationOptimize` function processes amortizations in chunks, improving performance and memory management.

The find functions in Project.php and Promoter.php emphasize that they should ideally call API endpoints or fetch records from a database.


# DATABASE EXPLANATION
In the project's root directory, there is also an image with the database schema that I thougth. 

In addition to the three initial tables (projects, amortizations, payments), I found it interesting and advantageous to add two more tables.

The first one, `global groups`, seemed interesting to have a table that gathers all the members of a project because not only does it make it easier to send notifications if something has happened with the project, but we can also later analyze the group's and members' history.

The second one, `members`, is used to store specific information about a member (email, name, username), and if it makes sense later, we can add more columns (nationality, age, etc) to understand patterns that exist for certain projects and collect more information to the markting/sales team.
