@echo off

SET CURRENTPATH=%~dp0..
SET "CURRENTPATH=%CURRENTPATH:\=/%"
:: SET CURRENTPATH=I:/DESARROLLO/witrac-backend-test

:: docker exec phpunit run %*
cmd/php vendor/bin/phpunit --configuration phpunit.xml %*
:: docker run --rm --interactive --tty --volume %CURRENTPATH%:/app phpunit/phpunit run %*
