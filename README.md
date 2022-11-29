Database
========

The `AbstractDatabase` class used for our projects.


Installation
------------

```bash
composer require deloachtech/database
```


Usage
-----

Create a project database class that extends the `AbstractDatabase` class and add project-specific methods as needed.


```php
// Database.php

use DeLoachTech\Database\AbstractDatabase;

class Database extends AbstractDatabase {

    public function __construct(string $host, string $user, string $password, string $database, string $charset = 'utf8mb4')
    {
        parent::__construct($host, $user, $password, $database, $charset);
    }

    // Add methods as required
}

// Initilization Layer //

$db = new Database($host, $user, $password, $database, $charset);

```