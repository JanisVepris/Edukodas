#!/bin/sh

vendor/bin/phpcs --colors -p --standard=PSR2 --extensions=php ./src
