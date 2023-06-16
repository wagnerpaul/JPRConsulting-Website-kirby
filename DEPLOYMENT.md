*Useful Docker Reference*
note: the "SLD" environment variable refers to Second Level Domain


**Build local dev**
    
    export SLD="blackletter" && \
    eval $(docker-machine env -u) && \
    docker build -t wagnerpaul/${SLD}-main-app:local .

**Run local dev container with interactive (-i flag) output for debugging, or detached (-d flag) to give you back the shell**

    export SLD="blackletter" && \
    docker run -i -v "`pwd`":/app -p 80:80 -e ENABLE_PANEL=true --name ${SLD}-local wagnerpaul/${SLD}-main-app:local
    docker run -d -v "`pwd`":/app -p 80:80 -e ENABLE_PANEL=true --name ${SLD}-local wagnerpaul/${SLD}-main-app:local



*Building Staging and Prod*

**create stg volumes**

    export SLD="blackletter" && \
    eval $(docker-machine env docker-ubuntu-4gb-sfo2-01) && \
    docker volume create --name=${SLD}-stg-content && \
    docker volume create --name=${SLD}-stg-media && \
    docker volume create --name=${SLD}-stg-sessions

**create accounts volume which is same on stg and prod**

    export SLD="blackletter" && \
    eval $(docker-machine env docker-ubuntu-4gb-sfo2-01) && \
    docker volume create --name=${SLD}-accounts

**build and deploy for stg, stg.blackletter.tech**
note: memory can be a little low so just `docker-machine ssh docker-ubuntu-4gb-sfo2-01` and `reboot now`

    export SLD="blackletter" && \
    eval $(docker-machine env docker-ubuntu-4gb-sfo2-01) && \
    docker build -t wagnerpaul/${SLD}-main-app:stg . && \
    docker-compose up -d --force-recreate

**create prod volumes**

    export SLD="blackletter" && \
    docker volume create --name=${SLD}-prod-content && \
    docker volume create --name=${SLD}-prod-media && \
    docker volume create --name=${SLD}-prod-sessions

**build and deploy for prod**

    export SLD="blackletter" && \
    eval $(docker-machine env docker-ubuntu-4gb-sfo2-01) && \
    docker build -t wagnerpaul/${SLD}-main-app:latest . && \
    docker push wagnerpaul/${SLD}-main-app:latest && \
    docker tag wagnerpaul/${SLD}-main-app:latest wagnerpaul/${SLD}-main-app:`date +%Y%m%d` && \
    docker push wagnerpaul/${SLD}-main-app:`date +%Y%m%d` && \
    docker-compose -f docker-compose.prod.yml up -d --force-recreate



*Commands for other tasks*

**get remote shell**
    
    export SLD="blackletter" && \
    docker exec -it ${SLD}-stg /bin/sh   
    docker exec -it ${SLD}-prod /bin/sh

**copy files to remote docker app**
    
    export SLD="blackletter" && \
    docker exec -it ${SLD}-stg sh -c 'rm -rf /app/content/*'  
    docker cp ./content ${SLD}-stg:app
    docker exec -it ${SLD}-stg sh -c 'chown -R nobody:nobody /app/content'
    docker cp ./storage/accounts ${SLD}-stg:app/storage



**copy files from remote docker app**

    export SLD="blackletter" && \
    docker cp ${SLD}-stg:app/content .
    docker cp ${SLD}-stg:app/storage/accounts storage/ .

**periodic cleanup commands**
https://docs.docker.com/engine/reference/commandline/image_prune/
    
    docker image prune -a

**letsencrypt certificate renew commands**
    
    docker exec PROXY-CONTAINER-NAME /app/force_renew

