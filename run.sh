#!/usr/bin/bash

docker build -t apache-grufsst .
docker run -d -p 3040:3040 -v $(pwd):/var/www/html --name apache-grufsst apache-grufsst