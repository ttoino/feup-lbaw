#!/bin/bash
docker-compose up -d --build app && docker-compose logs -f --tail 100 app
