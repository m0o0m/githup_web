#!/bin/sh
ProjectPath=`pwd`
export GOPATH=$ProjectPath:$GOPATH
#SrcPath=$ProjectPath/src
if [ -z "$1" ]
then
	echo "place select a version to build...\n\n"
	exit 1
fi

if [ "X$1" = "Xtest" ]
then
	go test test
	exit 0
fi

rm -f $ProjectPath/bin/api
rm -f $ProjectPath/bin/collection

cd $ProjectPath
echo  $1 "version building..."
##-gcflags "-N -l"
go build -ldflags "-X main._VERSION_ '$1' "  -o ./bin/api ./src/bin/api
go build -ldflags "-X main._VERSION_ '$1' "  -o ./bin/collection ./src/bin/collection

if [ "X$1" = "Xonline" ]
then
cd $ProjectPath
./bin/main
else
cd $ProjectPath/src
../bin/main
fi

#调试的时候使用
echo  $1 "version build finish"
