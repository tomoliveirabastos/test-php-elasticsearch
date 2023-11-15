docker network create elasticnetwork
docker pull elasticsearch:8.11.0
docker run -d --name elasticsearch -p 9200:9200 -p 9300:9300 -e "discovery.type=single-node" -e ELASTIC_PASSWORD=123Mudar elasticsearch:8.11.0