@echo off

SET CURRENTPATH=%~dp0..
SET "CURRENTPATH=%CURRENTPATH:\=/%"
:: SET CURRENTPATH=I:/DESARROLLO/witrac-backend-test

:: docker exec witrac-backend-test-fpm php %*
docker run --rm --interactive --tty --volume %CURRENTPATH%:/app witrac-backend-test_fpm php %*
