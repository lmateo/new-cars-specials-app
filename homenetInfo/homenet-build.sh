#!/bin/sh

cd /srv/www/files/inventory

#Copy inventory files to the homenet directory

echo "Copying Files to Homenet Directory... "

if [ -f "quirkkia.csv" ]
then
  cp quirkkia.csv homenet/quirkkia.csv
else
  cp archived/quirkkia.csv homenet/quirkkia.csv
fi


if [ -f "quirkmazda.csv" ]
then
  cp quirkmazda.csv homenet/quirkmazda.csv
else
  cp archived/quirkmazda.csv homenet/quirkmazda.csv
fi


if [ -f "quirkbuickgmc.csv" ]
then
  cp quirkbuickgmc.csv homenet/quirkbuickgmc.csv
else
  cp archived/quirkbuickgmc.csv homenet/quirkbuickgmc.csv
fi


if [ -f "quirkchevrolet.csv" ]
then
  cp quirkchevrolet.csv homenet/quirkchevrolet.csv
else
  cp archived/quirkchevrolet.csv homenet/quirkchevrolet.csv
fi


if [ -f "quirknissan.csv" ]
then
  cp quirknissan.csv homenet/quirknissan.csv
else
  cp archived/quirknissan.csv homenet/quirknissan.csv
fi


if [ -f "quirkchevroletnh.csv" ]
then
  cp quirkchevroletnh.csv homenet/quirkchevroletnh.csv
else
  cp archived/quirkchevroletnh.csv homenet/quirkchevroletnh.csv
fi


if [ -f "quirkpreowned.csv" ]
then
  cp quirkpreowned.csv homenet/quirkpreowned.csv
else
  cp archived/quirkpreowned.csv homenet/quirkpreowned.csv
fi


if [ -f "quirkdodge.csv" ]
then
  cp quirkdodge.csv homenet/quirkdodge.csv
else
  cp archived/quirkdodge.csv homenet/quirkdodge.csv
fi


if [ -f "quirksubaru.csv" ]
then
  cp quirksubaru.csv homenet/quirksubaru.csv
else
  cp archived/quirksubaru.csv homenet/quirksubaru.csv
fi


if [ -f "quirkford.csv" ]
then
  cp quirkford.csv homenet/quirkford.csv
else
  cp archived/quirkford.csv homenet/quirkford.csv
fi


if [ -f "quirkvolkswagen.csv" ]
then
  cp quirkvolkswagen.csv homenet/quirkvolkswagen.csv
else
  cp archived/quirkvolkswagen.csv homenet/quirkvolkswagen.csv
fi


if [ -f "quirkjeep.csv" ]
then
  cp quirkjeep.csv homenet/quirkjeep.csv
else
  cp archived/quirkjeep.csv homenet/quirkjeep.csv
fi


if [ -f "quirkvolkswagennh.csv" ]
then
  cp quirkvolkswagennh.csv homenet/quirkvolkswagennh.csv
else
  cp archived/quirkvolkswagennh.csv homenet/quirkvolkswagennh.csv
fi

if [ -f "quirkkianh.csv" ]
then
  cp quirkkianh.csv homenet/quirkkianh.csv
else
  cp archived/quirkkianh.csv homenet/quirkkianh.csv
fi

if [ -f "quirkjeepdorch.csv" ]
then 
  cp quirkjeepdorch.csv homenet/quirkjeepdorch.csv
fi


echo "... done "


cd /srv/www/files/inventory/homenet


echo "Cut away the header line and save as a new file... "


# Cut away the header line and save as a new file
sed -i '1d'  quirkkia.csv
sed -i '1d'  quirkmazda.csv
sed -i '1d'  quirkbuickgmc.csv
sed -i '1d'  quirkchevrolet.csv
sed -i '1d'  quirknissan.csv
sed -i '1d'  quirkchevroletnh.csv
sed -i '1d'  quirkpreowned.csv
sed -i '1d'  quirkdodge.csv
sed -i '1d'  quirksubaru.csv
sed -i '1d'  quirkford.csv
sed -i '1d'  quirkvolkswagen.csv
sed -i '1d'  quirkjeep.csv
sed -i '1d'  quirkvolkswagennh.csv
sed -i '1d'  quirkkianh.csv
sed -i '1d'  quirkjeepdorch.csv

echo "... done "


echo "Removing the 2nd column from the files... "


# remove the second column and save as temp file
cut -d, --complement -f 2,30- quirkkia.csv > quirkkia_temp.csv
cut -d, --complement -f 2,30- quirkmazda.csv > quirkmazda_temp.csv
cut -d, --complement -f 2,30- quirkbuickgmc.csv > quirkbuickgmc_temp.csv
cut -d, --complement -f 2,30- quirkchevrolet.csv > quirkchevrolet_temp.csv
cut -d, --complement -f 2,30- quirknissan.csv > quirknissan_temp.csv
cut -d, --complement -f 2,30- quirkchevroletnh.csv > quirkchevroletnh_temp.csv
cut -d, --complement -f 2,30- quirkpreowned.csv > quirkpreowned_temp.csv
cut -d, --complement -f 2,30- quirkdodge.csv > quirkdodge_temp.csv
cut -d, --complement -f 2,30- quirksubaru.csv > quirksubaru_temp.csv
cut -d, --complement -f 2,30- quirkford.csv > quirkford_temp.csv
cut -d, --complement -f 2,30- quirkvolkswagen.csv > quirkvolkswagen_temp.csv
cut -d, --complement -f 2,30- quirkjeep.csv > quirkjeep_temp.csv
cut -d, --complement -f 2,30- quirkvolkswagennh.csv > quirkvolkswagennh_temp.csv
cut -d, --complement -f 2,30- quirkkianh.csv > quirkkianh_temp.csv
cut -d, --complement -f 2,30- quirkjeepdorch.csv > quirkjeepdorch_temp.csv
echo "... done "


echo "a little cleaning... "

# replace temp files as originals
cp quirkkia_temp.csv quirkkia.csv
cp quirkmazda_temp.csv quirkmazda.csv
cp quirkbuickgmc_temp.csv quirkbuickgmc.csv
cp quirkchevrolet_temp.csv quirkchevrolet.csv
cp quirknissan_temp.csv quirknissan.csv
cp quirkchevroletnh_temp.csv quirkchevroletnh.csv
cp quirkpreowned_temp.csv quirkpreowned.csv
cp quirkdodge_temp.csv quirkdodge.csv
cp quirksubaru_temp.csv quirksubaru.csv
cp quirkford_temp.csv quirkford.csv
cp quirkvolkswagen_temp.csv quirkvolkswagen.csv
cp quirkjeep_temp.csv quirkjeep.csv
cp quirkvolkswagennh_temp.csv quirkvolkswagennh.csv
cp quirkkianh_temp.csv quirkkianh.csv
cp quirkjeepdorch_temp.csv quirkjeepdorch.csv

# remove temp files
rm quirkkia_temp.csv
rm quirkmazda_temp.csv
rm quirkbuickgmc_temp.csv
rm quirkchevrolet_temp.csv
rm quirknissan_temp.csv
rm quirkchevroletnh_temp.csv
rm quirkpreowned_temp.csv
rm quirkdodge_temp.csv
rm quirksubaru_temp.csv
rm quirkford_temp.csv
rm quirkvolkswagen_temp.csv
rm quirkjeep_temp.csv
rm quirkvolkswagennh_temp.csv
rm quirkkianh_temp.csv
rm quirkjeepdorch_temp.csv

echo "... done "

echo "Build Homenet File... "

# set header line
echo "HNetID,VIN,Stock #,Stock-Type,Year,Make,Model,Model-No,Model-Type,Transmission,Trim-Level,# of Doors,Mileage,# of Cyl,Engine,Drivetrain,Ext. Color,Int. Color,Invoice Price,Retail Price,Book Value,Selling Price,Entry Date,Certified,Description,Options,Wheelbase,Commercial" > quirk-auto-dealers-homenet.csv
cat quirkkia.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkmazda.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkbuickgmc.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkchevrolet.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirknissan.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkchevroletnh.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkpreowned.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkdodge.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirksubaru.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkford.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkvolkswagen.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkjeep.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkvolkswagennh.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkkianh.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv
cat quirkjeepdorch.csv >> quirk-auto-dealers-homenet.csv
#echo "\r\n" >> quirk-auto-dealers-homenet.csv


echo "... done "

echo "a little cleaning... "

rm quirkkia.csv
rm quirkmazda.csv
rm quirkbuickgmc.csv
rm quirkchevrolet.csv
rm quirknissan.csv
rm quirkchevroletnh.csv
rm quirkpreowned.csv
rm quirkdodge.csv
rm quirksubaru.csv
rm quirkford.csv
rm quirkvolkswagen.csv
rm quirkjeep.csv
rm quirkvolkswagennh.csv
rm quirkkianh.csv
rm quirkjeepdorch.csv

chown webdev quirk-auto-dealers-homenet.csv

chgrp users quirk-auto-dealers-homenet.csv

chmod 0777 quirk-auto-dealers-homenet.csv

echo "... done "

echo "Preparing to FTP file "


cd /srv/www/schedule


./homenet-build.php


echo "Script Complete"


#done
