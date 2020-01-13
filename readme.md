# Magento 2 Code Generator

Inspired by https://github.com/staempfli/magento2-code-generator

## Purpose

In day-to-day Magento 2 development there are some common tasks which require developent of repeatable code that is hard to simply copy-paste. The purpose of this app is to automatize creation of such code, so the developers may focus on business logic, beeing thanks to that much more efficient and happy. 

## Installation

Recommended way to install this app is to use Composer. Right now there is no repository provided, so you need to perform the following steps:

1. Update composer.json manually with GIT repository:

```
"repositories":[
    {
        "type": "vcs",
        "url": "git@bitbucket.org:orbainternalprojects/magento2-codegen.git"
    }
]
```

2. Add your SSH key (if you didn't already) to your BitBucket account:

https://confluence.atlassian.com/bitbucket/set-up-an-ssh-key-728138079.html

3. Run Composer:

```
composer require --dev orba/magento2-codegen:dev-master
```

4. Create your custom config file:

```
cp vendor/orba/magento2-codegen/config/codegen.yml.dist vendor/orba/magento2-codegen/config/codegen.yml
```

Edit default values if needed.

## Usage

1. List all templates:

```
bin/codegen template:list
```

2. Show template info:

```
bin/codegen template:info <template>
```

3. Generate template:

```bin/codegen template:generate <template>```

This command must be executed on the module root folder where the `registration.php` file is.
You can also use option `--root-dir` to specify this path, if you execute it from a different location.

Examples:

```
cd /path/to/magento/app/code/Orba/TestModule
../../../../vendor/orba/magento2-codegen/bin/codegen template:generate block
```

```
cd /path/to/magento/vendor/orba/magento2-codegen
bin/codegen --root-dir="/path/to/magento/app/code/Orba/TestModule" template:generate block
```

If specified root directory doesn't exist, it will be created automatically.

## Contribution

Feel free to contribute with new templates, bugfixes and features. Submit your code to review using pull request.

In [dev/docs.md](dev/docs.md) you can find additional documentation for developers.