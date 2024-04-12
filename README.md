# ShortLink service

## Prerequisites:
GIT and docker should be installed before
Make tool also good to have

## Installation:
```bash
make run
```

(Or just check sequence of commands in the Makefile)

## Cleaning up:
```bash
make clean
```


### /encode
```http request
POST http://localhost:8081/encode
Content-Type: application/json

{"url":  "https://example.com"}
```

### /decode
```http request
GET http://localhost:8081/decode?url=[PREVIOUSLY ENCODED URL]
```

### How to test:
```bash
docker-compose up
```
```bash
curl -X POST "http://localhost:8081/encode" -H "Content-Type: application/json" -d '{"url": "https://example.com"}' --no-progress-meter | jq
```
```bash
curl "http://localhost:8081/decode?url=[PREVIOUSLY ENCODED URL]" --no-progress-meter | jq
```
