# Stop containers
sudo docker stop $(docker ps -a -q) &>/dev/null
echo "\e[32mAll containers have been stopped.\e[0m"

# Remove containers
sudo docker rm $(docker ps -a -q) &>/dev/null
echo "\e[32mAll containers have been removed.\e[0m"

# Remove images
sudo docker rmi $(docker images -a -q) &>/dev/null
echo "\e[32mAll images have been removed.\e[0m"

# Remove volumes
sudo docker volume rm $(docker volume ls -q) &>/dev/null
echo "\e[32mAll volumes have been removed.\e[0m"

# Restart Docker
sudo docker compose down -v
echo "\e[32mDocker has been restarted.\e[0m"

# Remove all unused containers, networks, images (both dangling and unreferenced), and optionally, volumes.
sudo docker system prune -a -f
echo "\e[32mAll unused containers, networks, images, and volumes have been removed.\e[0m"

# Restart Docker
sudo systemctl restart docker
echo "\e[32mDocker has been restarted.\e[0m"

sudo docker ps -a --format "{{.Names}}\t\t{{.Status}}\t{{.Ports}}"