# Membership Form -- Back

Membership Form is a free open source application meant to provide a tool for associations to store and export new membership.

This repository is concerning the backend only.

## Features

Provides API endpoints to

- Store submitted form data
- Display members data  


## Getting started

### Prerequisites

- Install Docker
- Install Docker Compose
- Install Make 

### Setup dev env
- Setup composer `make up`
- Init database `make sf c="doctrine:database:create"` (if the database is already created, you will be prompted an error, just go to the next step)
- Apply migrations `make sf c="doctrine:migrations:migrate"`
- Load test contents `make sf c="doctrine:fixtures:load"`
- Set up test env `make sf c="doctrine:database:create --no-interaction --env=test" && make sf c="doctrine:migrations:migrate --no-interaction --env=test" && make sf c="doctrine:fixtures:load --no-interaction --env=test"`

### Make tests
- `make test`

### Newsletter

Newsletter sync services are deactivated by default. You can add services by editing your .env.local file. Currently supported services : mailchimp.

### Contribution
- todo

### Resources
- Docker stack used: [Docker4PHP By Wodby](https://github.com/wodby/docker4php)
- Framework: [Symfony](https://symfony.com/)
- API: [Plateform API](https://api-platform.com/)
- Authentication: [JWT Token](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)
- Deployment: [Github actions](https://docs.github.com/fr/actions)