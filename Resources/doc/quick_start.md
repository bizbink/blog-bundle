Quick Start
===========

Installation
------------

1. Download
2. (Optional) Include development
3. Implementation
4. (Optional) Override templates
5. Configuration
6. Update database schema

### Step 1: Download BlogBundle via Composer

Modify `composer.json` to include git repository and require stable release of bundle..

```json
{
    "repositories": [
        {
        "type": "vcs",
        "url": "https://github.com/bizbink/blog-bundle"
        }
    ],
    "require": {
        "bizbink/blog-bundle": "^5.4"
    }
}
```

### Step 2: (Optional) Include development for testing and/or non-production/critical.

```json
{
    "require-dev": {
        "bizbink/blog-bundle": "^5.4.x-dev"
    }
}
```

Run command to update dependencies and enable bundle.

```bash
composer update
```

[Composer Documentation](https://getcomposer.org/doc/05-repositories.md#vcs)

### Step 3: Implement author interface with current design

There are advantages of interfacing rather than including a `User` object within BlogBundle. The most obvious advantage is ability to use an existing object for authentication.

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

Mappings are done using `id` field. First and last name are displayed by default, this can be changed by overriding templates.

```yaml
# config\packages\doctrine.ymal
doctrine:
    orm:
        resolve_target_entities:
            bizbink\BlogBundle\Model\AuthorInterface: App\Entity\User
```

Add to `resolve_target_entities` for doctrine configuration. It'll resolve to an existing `User` object that implements `AuthorInterface`.

### Step 4: (Optional) Override template

There are a few templates for overriding:

- `index.html.twig`
- `manage.html.twig`
- `create.html.twig`

[Symfony Documentation](https://symfony.com/doc/5.4/templating/overriding.html)

### Step 5: Configuration

Register routes with optional prefix
```yaml
# config/routes.ymal
blog:
    resource: '@BlogBundle/Controller/'
    type: annotation
    prefix: /blog
```

### Step 6: Update database schema (not recommended)

It's recommended to use migrations, but the following can used to forcefully update database schema.

```bash
php bin/console doctrine:schema:update --force
```