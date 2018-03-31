#!/bin/bash

# Echo Colors
msg_color_magenta='\033[1;35m'
msg_color_yellow='\033[0;33m'
msg_color_blue='\033[1;34m'
msg_color_none='\033[0m' # No Color

if git rev-parse --verify HEAD >/dev/null 2>&1
then
        against=HEAD
else
        # Initial commit: diff against an empty tree object
        against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
fi

stagedFiles=$(git diff-index --name-only --cached $against --diff-filter=ACMR | grep '\.php$');
#stagedFiles=$(find . -type f -name '*.php' )

if [ "$stagedFiles" == "" ]; then
    exit 0
fi

# Start of PHP Lint process
phpLintErrors=0

echo -e "${msg_color_blue}PHP Lint search code errors ...${msg_color_none}"

for file in $stagedFiles
do
	echo "PHP is linting $file..."; 
	RETVAL=$(php -l $file)
	if [[ $RETVAL != "No syntax errors detected in $file" ]]
	then
		echo "$RETVAL"
		phpLintErrors=1
	fi
done


if [[ $phpLintErrors == 1 ]]
then
	echo -e "${msg_color_magenta}PHP Lint process find errors. Commit aborted. ${msg_color_none}"
	exit 1
else 
    echo -e "${msg_color_yellow}PHP Lint not find errors in staged files${msg_color_none}\n"    
fi

# Start of Php CodeSniffer process
echo -en "${msg_color_blue}\nPHP Codesniffer verify respect rules of coding ...${msg_color_none} \n"

# Check location of phpcs command
phpcs_vendor_command="vendor/bin/phpcs"
phpcs_local_exec="phpcs.phar"
phpcs_global_command="phpcs"
if [ -f "$phpcs_vendor_command" ]; then
	phpcs_command=$phpcs_vendor_command
else
    if hash phpcs 2>/dev/null; then
        phpcs_command=$phpcs_global_command
    else
        if [ -f "$phpcs_local_exec" ]; then
            phpcs_command=$phpcs_command
        else
            echo "${msg_color_magenta}PHP Codesniffer executable not found! Commit aborted ${msg_color_none}"
            exit 1
        fi
    fi
fi

# Specify args of phpcs command
if [ -f "phpcs.xml" ]; then
	phpcs_args=""
else 
	phpcs_args="--standard=PSR2 --encoding=utf-8 --extensions=php src"	
fi

command_result=`eval $phpcs_command $phpcs_args`

if [[ $command_result =~ ERROR ]]
then
	echo "$command_result"
    echo -en "${msg_color_magenta}PHP CodeSniffer find errors. Commit aborted. ${msg_color_none} \n"
    exit 1
else 
    echo -en "${msg_color_yellow}PHP CodeSniffer ended successfully ${msg_color_none} \n"  
fi

# Start of php-cs-fixer process
echo -en "${msg_color_blue}\nBegin PHP-CS-FIXER...${msg_color_none}"
phpcsfixer_command="php php-cs-fixer.phar fix src --quiet"

RETVAL=$($phpcsfixer_command)
echo -en "${msg_color_yellow}\nEnd of PHP-CS-FIXER${msg_color_none} \n"

exit 0