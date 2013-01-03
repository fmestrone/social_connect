CURDIR=`pwd`
DIR="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR/..
zip -r social_connect.zip social_connect/ -x \*/.\* \*/.git\* \*/__MACOSX/\* \*/vendors/examples/\* \*/README\* \*/log/\* \*/CHANGELOG\* \*/release.sh
mv social_connect.zip $CURDIR
cd $CURDIR
