Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require corerely/entity-association-inspector-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require corerely/entity-association-inspector-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Corerely\EntityAssociationInspectorBundle\CorerelyEntityAssociationInspectorBundle::class => ['all' => true],
];
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
        if (false === $this->inspector->isSafeDelete($entity)) {
            throw new \Exception('It\'s not safe to delete this entity. It\'s associated with more than one other entity');
        }
        
        // Do delete entity...
    }
} 
```

What it can do?
--------------

So far there is one inspector service `Corerely\EntityAssociationInspectorBundle\InspectorInterface`.

### 1. With help of this service you can check if it's safe to delete given entity by checking if it has any associated entities.

`Corerely\EntityAssociationInspectorBundle\InspectorInterface::isSafeDelete(object $entity): bool`

Return `true` if there is no any connected associations or all is configured to **cascade remove**.

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
