echo "starting wLeaf v2"
noHup=0
logIt=0
while getopts ":nl" opt
do
  case $opt in
    n)
      noHup=1
      ;;
    l)
      logIt=1
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      echo "Posible options are: [nl]"
      exit
      ;;
  esac
done

if [ $noHup -ne 1 -a $logIt -ne 1 ]
then
    php start.php
else
    if [ $noHup -eq 1 -a $logIt -ne 1 ]
    then
        nohup php start.php
    else
        if [ $logIt -eq 1 -a $noHup -ne 1 ]
        then
            php start.php > ~/wleafv2/wLeaf.log &
        else
            nohup php start.php > ~/wleafv2/wLeaf.log &
        fi
    fi
fi