#!/usr/bin/env bash

SCRIPTPATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPTPATH"

echo "Remove package-lock.json (force install)"
rm -rf package-lock.json

echo "Installing node_modules"

npm install

echo "Runing rollup"

npx rollup --config rollup.config.mjs

rm -rf node_modules
