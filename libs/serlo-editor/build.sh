#!/usr/bin/env bash

SCRIPTPATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPTPATH"

echo "Installing node_modules"

npm install

echo "Runing rollup"

# npm run build
npx rollup --config rollup.config.mjs

rm -rf node_modules

# If everything runs smoothly, the file amd/src/serlo-lazy.js will be generated
# Open this file and add these lines to the top
#   /** @ts-ignore */
#   /* eslint-disable */
