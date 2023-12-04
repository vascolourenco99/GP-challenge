# Project Amortization Optimization

This PHP project involves managing project amortizations, processing payments, and optimizing the amortization processing function for better performance.

## Files Overview
index.php
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

### processPaymentsOnAmortizationTest.php
This file contains PHPUnit test cases for the processPaymentsOnAmortization function, checking successful and unsuccessful payment scenarios.

## How to Run Tests
To run tests, execute the following command in the terminal:

```bash
phpunit processPaymentsOnAmortizationTest.php
```

### Important Points

The `projectAmortizationOptimize` function processes amortizations in chunks, improving performance and memory management.

The find functions in Project.php and Promoter.php emphasize that they should ideally call API endpoints or fetch records from a database.
