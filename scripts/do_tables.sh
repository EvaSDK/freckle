#!/bin/bash
# Script de g�n�ration des tables de fichiers et de reference

REPS="EL IN PH MA EN"
BASE="/home/web/htdocs/freckle/files"
cpt=0;

cd $BASE
echo "USE freckle;" > generated.sql
echo "USE freckle;" > reference.sql

for y in ${REPS}
do
  cd ${BASE}/${y}
  echo "[working] ${y}"
  FILES=$(ls -Q)
  for x in ${FILES}
  do
    cpt=$(echo $[$cpt+1]);
    file=$(echo $x | sed "s/\"//g;s/^/\'$y\//;s/$/\'/")
    echo "INSERT INTO fichiers (url,annee_prod,commentaire) VALUES ($file,0,'');" >> ${BASE}/generated.sql
    
    #mati�re
    case $y in
    "EL") id_cat=6;;
    "IN") id_cat=7;;
    "PH") id_cat=8;;
    "MA") id_cat=9;;
    "EN") id_cat=10;;
    esac

    echo "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ($id_cat,$cpt,0);" >> ${BASE}/reference.sql 

    #ann�e
    annee=$(echo $file | egrep -o "[-|.]+[0-9xX]{1,3}[-|.]+" | egrep -o "[0-9]+" | sed "s/..$//" )
    for z in ${annee}
    do
      echo "INSERT INTO reference (id_categorie,id_fichier,id_type) VALUES ($z,$cpt,0);" >> ${BASE}/reference.sql
    done
    
  done
done

echo "Job's done";

