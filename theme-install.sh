#/usr/env/bin bash

if [[ $EUID -ne 0 ]]; then
      echo "This script must be run as root" 1>&2
      exit 1
fi

MAGE_ROOT=$(pwd);
SNOW_DOG_FOLDER=$MAGE_ROOT/vendor/snowdog/frontools;

sudo chmod -Rf 777 "$MAGE_ROOT";

composer require snowdog/theme-blank-sass;

sudo php bin/magento setup:upgrade;
sudo php bin/magento setup:static-content:deploy;
sudo chmod -Rf 777 "$MAGE_ROOT";

composer require snowdog/frontools;

cd "$SNOW_DOG_FOLDER";
npm install;
cd "$MAGE_ROOT";

sudo php bin/magento indexer:reindex;
sudo chmod -Rf 777 "$MAGE_ROOT";

npm install -g gulp-cli;
cd "$SNOW_DOG_FOLDER";

gulp;
gulp setup;
gulp dev;
