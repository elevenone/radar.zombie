#!/bin/bash

replace() {
    for file in $(git grep -l $1); do
        sed -i '' -e "s/$1/$2/g" $file
    done
}

# replace "Spark" "Equip"
# replace "sparkphp\/spark" "equip\/framework"
# replace "spark.readthedocs" "equipframework.readthedocs"
# replace "sparkphp" "equip"
# replace "spark" "equip"