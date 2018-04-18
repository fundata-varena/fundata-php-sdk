#!/bin/bash

git diff --name-only HEAD^ | grep "\.php$" | xargs -L 1 php -l