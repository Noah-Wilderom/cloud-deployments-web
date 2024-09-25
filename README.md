# Cloud Deployments
This is a personal project which i have made many times before, in other languages and in CLI.  
This project is inspired by other softwares like Plesk, Coolify, Vercel, Ploi etc.

## Tech Stack
- PHP 8.3
- Laravel 11
- Websockets (Laravel Reverb)
- Shell scripts
- Vue (Inertia) for the front-end
- Laravel Modules for the structure
- [Metronic 9](https://keenthemes.com/metronic/) for the dashboard
- Kubernetes (production folder)

## Installation

### Kubernetes
1. Clone the project
2. Install dependencies `composer install && npm i`
3. Copy the .env.example and adjust the needed settings `cp .env.example .env`
4. Run the deploy command `make deploy`
 
### Local
1. Clone the project
2. Install dependencies `composer install && npm i`
3. Copy the .env.example and adjust the needed settings `cp .env.example .env`
4. Start the webserver `php artisan serve`
5. Build the assets `npm run dev`
6. Start the queue `php artisan horizon`
7. Start the scheduler `php artisan schedule:work`
8. Start the websockets `make websocket`

## Features  

### Auth  (app/Http/Controllers/Auth)

- [x] Login
  - [x] Obligated 2FA
  - [x] Obligated password change
- [ ] Register

### Services (Modules/Services)
- [ ] Customers
  - [x] Create new customer
  - [ ] Update customers

- [ ] Domains
  - [x] Create new domain
  - [x] Use the Cloudflare integration
  - [ ] Update domains
  - [ ] DNS check if domain is pointed to one of the servers
- [ ] Service Plans
  - [x] Create new service plan
  - [ ] Update service plans
- [ ] Subscriptions
  - [x] Create new subscription using service plans
  - [ ] Update subscriptions
  - [ ] Generate automatic invoices
  - [ ] Accept payments with Stripe
  - [ ] Payout with Stripe 

### Teams
- [ ] Create teams
- [ ] Update teams
- [ ] Manage users in teams

### Cloud (Modules/Cloud)
- [ ] Servers
  - [x] Create new server
  - [x] Use Hetzner integration to fetch VPS servers
  - [x] Check when server is accepting connections
  - [x] Add user public key to authorized_keys
  - [x] Provision server with shell scripts (storage/app/scripts/server)
  - [x] Stream the logs from the shell scripts through websockets
  - [x] Make a master kubernetes cluster so other kubernetes clusters can join as workers
  - [ ] Choose between normal webserver and kubernetes cluster
- [ ] Projects
  - [x] Create new project
  - [x] Use the Github integration
  - [x] Provision project on selected server with shell scripts (storage/app/scripts/project)
  - [x] Automatic deployments through Github workflow or webhook (like Vercel on PR and/or commits)
  - [ ] Deploy with multiple environments (dev, staging, production)
- [ ] Database
  - [ ] Create a new database node (docker, kubernetes or on server itself?)
  - [ ] Create new databases inside the node
  - [ ] Create new users for the databases inside the node
- [ ] Scripts
  - [ ] Create custom script
  - [ ] Update scripts
  - [ ] Hook script on events
- [ ] Backups
  - [ ] Automatic backups for servers, projects and database nodes
  - [ ] Option to use AWS S3 or SFTP to server

### User Portal (Modules/UserPortal)
- [ ] Profile
  - [x] Update profile information
  - [x] Add Public SSH Keys
  - [x] Connect with integrations (Github, Slack, Cloudflare, Hetzner, Google Analytics)
  - [ ] General preferences for backups (maybe Hetzner snapshot features?)
  - [ ] Security preferences
  - [ ] Settings (General)
  - [ ] Invoices
- [ ] Subscription
  - [ ] Show all packages
  - [ ] Subscribe to package
  - [ ] Manage subscription
- [ ] Documentation
  - [ ] Some kind of docs framework with markdown support
