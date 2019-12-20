Quick Start
===========

Installation
------------

1. Download
2. Enable
3. Interface User object
4. (Optional) Override templates
5. Configuration
6. Update database schema

### Step 1: Download BlogBundle via Composer

Modify `composer.json` to have git repository and package.

```json
{
    "repositories": [
        {
        "type": "vcs",
        "url": "https://github.com/bizbink/blog-bundle"
        }
    ],
    "require": {
        "bizbink/blog-bundle": "master"
    }
}
```

Run command to update dependencies.

```bash
composer update
```

[Composer Documentation](https://getcomposer.org/doc/05-repositories.md#vcs)

### Step 2: Enable bundle

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new bizbink\BlogBundle\BlogBundle(),
            // ...
        ];
    }
}
```

### Step 3: Interface with User object

There are advantages of interfacing rather than including a User object within BlogBundle.

```php
// bizbink/BlogBundle/Model/AuthorInterface.php
interface AuthorInterface
{
    public function getId(): int;
    public function getUsername(): string;
    public function getFirstName(): string;
    public function getLastName(): string;
}
```

Mappings are done using `id` field. First and last name are displayed by default, this can be changed when overriding templates.

```yaml
# app/config/config.ymal
doctrine:
    orm:
        resolve_target_entities:
            bizbink\BlogBundle\Model\AuthorInterface: AppBundle\Entity\User
```

Add to `resolve_target_entities` for doctrine configuration. It'll resolve to an existing User object.

### Step 4: (Optional) Override template

There are a few templates for overriding:

- `index.html.twig`
- `manage.html.twig`
- `create.html.twig`

[Symfony Documentation](https://symfony.com/doc/3.4/templating/overriding.html)

### Step 5: Configuration

Autoload services (e.g. event subscribers, extensions)
```yaml
# app/config/services.ymal
services:
    _defaults:
        autowire: true
        autoconfigure: true
```

```yaml
# app/config/routing.ymal
blog:
    resource: '@BlogBundle/Controller/'
    type: annotation
```

### Step 6: Update database schema

```bash
php bin/console doctrine:schema:update --force
```