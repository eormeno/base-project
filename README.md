# Damos's Quest Guide
## The .env file
The .env file is used to store environment variables for the project. It is used by the docker-compose.yml file to set the environment variables for the containers. The .env file is not included in the repository for security reasons. The .env file should be placed in the root directory of the project.

Start copying the .env.example file:

```bash
$ cp .env.example .env
```

Then edit the .env file and set the environment variables.


## Scripts

This repository contains scripts for managing Docker containers in the Damo's Quest project.

## rebuild.sh
This script is used to rebuild the Docker images. It accepts the following arguments:

- `local`: Rebuilds for a local environment.
- `production`: Rebuilds for a production environment.

Example usage:
    
```bash
$ sudo ./rebuild.sh local
$ sudo ./rebuild.sh prod
```

### containers.sh

This script is used to start or stop Docker containers. It accepts the following arguments:

- `start`: Starts all the containers.
- `stop`: Stops all the containers.

Example usage:
    
```bash 
$ sudo ./container.sh start
$ sudo ./container.sh stop
```
### cont-list.sh

This script is used to list all the containers.

Example usage:
    
```bash
$ sudo ./cont-list.sh
```

### remove-containers.sh
This script is used to remove all the containers. PLEASE USE WITH CAUTION, THIS WILL REMOVE ALL THE CONTAINERS.

Example usage:
    
```bash
$ sudo ./remove-containers.sh
```

### remove-images.sh
This script is used to remove all the images. PLEASE USE WITH CAUTION, THIS WILL REMOVE ALL THE IMAGES AND VOLUMES.

Example usage:
    
```bash
$ sudo ./remove-images.sh
```