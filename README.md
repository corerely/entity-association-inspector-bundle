Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require corerely/entity-association-inspector-bundle
```

How to use bundle?
------------------

### Inject `Inpector` service in your own service. 

```php
// app/MyService.php
namespace App;

use Corerely\EntityAssociationInspectorBundle\InspectorInterface;

class MyService
{
    public function __construct(private InspectorInterface $inspector) {
    }
    
    public function performDeleteAction(object $entity){
        if (false === $this->inspector->hasAssociations($entity)) {
            throw new \Exception('It\'s not safe to delete this entity. It\'s associated with more than one other entity');
        }
        
        // Do delete entity...
    }
} 
```

Testing
=======

### Step 1: Go to root folder using console and run

```console
$ composer install
```

### Step 2: Run code sniffer

```console
$ ./vendor/bin/phpcs
```

### Step 2: Run PHPUnit tests

```console
$ ./vendor/bin/simple-phpunit
```
