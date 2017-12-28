#!/bin/bash

if [ -z "$1" ]
  then
    echo "No theme name supplied as script argument."
    exit 1
fi

BASH_RC=~/.bashrc
MAGE_ROOT=~/magento2
TOOLS_FOLDER=~/tools

YARN_ALIAS="alias yarn='/data/web/tools/node_modules/yarn/bin/yarn'"
GULP_CLI_ALIAS="alias gulp-cli='/data/web/tools/node_modules/gulp-cli/bin/gulp.js'"
GULP_ALIAS="alias gulp='/data/web/tools/node_modules/gulp/bin/gulp.js'"

cd ~/
mkdir "$TOOLS_FOLDER"
cd "$TOOLS_FOLDER"
npm install gulp-install yarn gulp-cli gulp-clean
cat "$YARN_ALIAS" >> "$BASH_RC"
cat "$GULP_CLI_ALIAS" >> "$BASH_RC"
cat "$GULP_ALIAS" >> "$BASH_RC"
source "$BASH_RC"
cd "$MAGE_ROOT"
gulp styles --theme "$1"
exit 0