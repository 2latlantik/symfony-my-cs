# Symfony-My-Cs

The goal of this library is to gather a set of process to maintain a good code quality of my **symfony** projects. Failure to respect some good practices will block the consideration of commit git

I combine the use of different libraries to approve git commit which are:
- PHP-Lint (A tool to analyze PHP code and to return usage errors)
- PHP-CS (A tool to control compliance with PSR2 standards here)
- PHP-CS-FIXER (A tool to control the respect of several coding conventions)

It is also possible to use PHP Mess Detector to further refine the detection of a moderately explicit and error-prone code

# Requirements

 - Usage on Symfony4 project
 - Usage with a versioned project with Git

# Installation and Usage

## I - Install the process of this library

To be able to install the different code verification processes you have to put these lines in the composer.json file.

```
"scripts" : {
    "my-cs-install": [
        "SymfonyMyCs\\Git\\Hooks\\PostInstall::installHooks"
    ] 
}
```

Then you can run the installaton script via the command

``` 
composer run-script my-cs-install
```

## II - Installation of the Symfony Code Quality executable

Symfony created his own code parser and named it [http://cs.sensiolabs.org/](php-cs-fixer) .

You must install it to allow your commit to be validated.

## III - Main moment of library actions



# License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details