# Base Project Guide
## Table of contents
- [Configuration](#configuration)
- [Installing dependencies](#installing-dependencies)
- [Running the project](#running-the-project)
- [Scripts](#scripts)
  - [Windows *PowerShell* scripts](#windows-powershell-scripts)
  - [Linux docker scripts](#linux-scripts)

### Configuration
This project can be configured to run in a local environment or in a production environment. The configuration is done through the `.env` file located in the `src` folder.
The `.env` file is used to store environment variables for the project. It is used by the `docker-compose.yml` file to set the environment variables for the containers. The `.env` file is not included in the repository for security reasons. To set up the `.env` file, follow the steps below:
- Open a terminal in the `src` folder.
- Copy the `.env.example` file to a new file called `.env`.
- Edit the `.env` file and set the environment variables.

```powershell
/src> copy .env.example .env
```
Then edit the `.env` file and set the environment variables.

### Installing dependencies
Open a terminal in the `src` folder and run the following commands to install the dependencies for the project.
- Install the PHP dependencies using composer.
- Generate the application key.
- Run the migrations.
- Seed the database.
- Install the Node.js dependencies.

```powershell
/src> composer install
/src> php artisan key:generate
/src> php artisan migrate
/src> php artisan db:seed
/src> npm install
```

### Running the project
To run the project, open a terminal in the `src` folder and run the following command:

In Windows:
```powershell
/src> ../start
```
In Linux:
```bash
/src$ sudo ../start.sh
```
## Scripts

This repository contains scripts for managing both docker containers of the project, as well as the development services like artisan and node vite.

### Windows *PowerShell* scripts
At the root of the project, there are two powershell scripts that can be used to manage the containers and the development services. The scripts are:
- `start.ps1`: Starts the containers and the development services.
- `stop.ps1`: Stops the containers and the development services.

### Linux scripts

#### rebuild.sh
This script is used to rebuild the Docker images. It accepts the following arguments:

- `local`: Rebuilds for a local environment.
- `production`: Rebuilds for a production environment.

Example usage:
    
```bash
$ sudo ./rebuild.sh local
$ sudo ./rebuild.sh prod
```

#### containers.sh

This script is used to start or stop Docker containers. It accepts the following arguments:

- `start`: Starts all the containers.
- `stop`: Stops all the containers.

Example usage:
    
```bash 
$ sudo ./container.sh start
$ sudo ./container.sh stop
```
#### cont-list.sh

This script is used to list all the containers.

Example usage:
    
```bash
$ sudo ./cont-list.sh
```

#### remove-containers.sh
This script is used to remove all the containers. **PLEASE USE WITH CAUTION, THIS WILL REMOVE ALL THE CONTAINERS**.

Example usage:
    
```bash
$ sudo ./remove-containers.sh
```

#### remove-images.sh
This script is used to remove all the images. **PLEASE USE WITH CAUTION, THIS WILL REMOVE ALL THE IMAGES AND VOLUMES**.

Example usage:
    
```bash
$ sudo ./remove-images.sh
```