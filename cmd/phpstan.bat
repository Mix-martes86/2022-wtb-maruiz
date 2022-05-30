@echo off

SET CURRENTPATH=%~dp0..
SET "CURRENTPATH=%CURRENTPATH:\=/%"
:: SET CURRENTPATH=I:/DESARROLLO/witrac-backend-test

:: docker exec phpstan analyse src src/Tests %*
:: cmd/php vendor/bin/phpstan analyse src src/Tests %*
docker run --rm --interactive --tty --volume %CURRENTPATH%:/app ghcr.io/phpstan/phpstan analyse src src/Tests %*
