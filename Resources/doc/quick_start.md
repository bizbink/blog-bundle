Quick Start
===========

Installation
------------

1. Download
2. Implementation
3. (Optional) Override templates
4. Configuration
5. Update database schema

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
        "bizbink/blog-bundle": "^4.4"
    }
}
```

Run command to update dependencies and enable bundle.

```bash
composer update
```

[Composer Documentation](https://getcomposer.org/doc/05-repositories.md#vcs)

### Step 2: Implement the interface

There are advantages of interfacing rather than including a `User` object within BlogBundle.

```php
// Model/AuthorInterface.php
interface AuthorInterface
{
    public function getId(): ?int;
    public function getUsername(): ?string;
    public function getFirstName(): ?string;
    public function getLastName(): ?string;
}
```

Mappings are done using `id` field. First and last name are displayed by default, this can be changed when overriding templates.

```yaml
# config\packages\doctrine.ymal
doctrine:
    orm:
        resolve_target_entities:
            bizbink\BlogBundle\Model\AuthorInterface: App\Entity\User
```

Add to `resolve_target_entities` for doctrine configuration. It'll resolve to an existing `User` object that implements `AuthorInterface`.

### Step 3: (Optional) Override template

There are a few templates for overriding:

- `index.html.twig`
- `manage.html.twig`
- `create.html.twig`

[Symfony Documentation](https://symfony.com/doc/3.4/templating/overriding.html)

### Step 4: Configuration

Autoload services (e.g. event subscribers, extensions)
```yaml
# config/services.ymal
services:
    _defaults:
        autowire: true
        autoconfigure: true
```

Register routes with optional prefix
```yaml
# config/routes.ymal
blog:
    resource: '@BlogBundle/Controller/'
    type: annotation
    prefix: /blog
```

### Step 5: Update database schema

```bash
php bin/console doctrine:schema:update --force
```