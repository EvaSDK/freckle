#!/bin/bash

WCOPY="/home/eva/devel/freckle_hydre/branches/eva_develsummer/"
WWW="/home/eva/web/freckle/"
#WWW="/mnt/usb/www/"
#WCOPY="/mnt/usb/wcopy"

if [[ -z $1 ]]; then
        echo "[svn script] cleaning ${WWW}"
        rm -rf ${WWW}
        svn export ${WCOPY} ${WWW}
        echo "[svn script] working copy copied to ${WWW}"
else
        echo "#!/bin/bash" > ._temp
        find -type f| egrep $1 | egrep -v "svn" | awk '{ print "cp -f "$0" '${WWW}'" substr($0,3) }' >> ._temp
        chmod +x ._temp
        sh ./._temp 2> /dev/null
        rm ._temp
        echo "[svn script] *.$1 from working copy copied to ${WWW}"
fi;


