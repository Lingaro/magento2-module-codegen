# Magento 2 Code Generator

Inspired by https://github.com/staempfli/magento2-code-generator

## Purpose

In day-to-day Magento 2 development there are some common tasks which require development of repeatable code that is hard to simply copy-paste. The purpose of this app is to automatize creation of such code, so the developers may focus on business logic and thanks to that being much more efficient and happy. 

## Supported Magento versions

* 2.3.x
* 2.4.x

## Installation

Recommended way to install this app is to add it as Magento's Composer dev dependency.

The package is stored in Orba's private repository. You have to configure it first:

```
composer config repositories.orba composer https://composer.orbalab.com/
```

You can obtain credentials to the repository from Orba's DevOps team.

When it's done, you simply need to add dev dependency:

```
composer require --dev orba/magento2-codegen
```

If you don't want to attach this app to your Magento, you can also simply clone the repository and use it as a standalone library.

## Configuration

Create your custom config file (not needed for Orba developers) either in package `config` directory or in your Magento root directory:

```
cp vendor/orba/magento2-codegen/config/codegen.yml.dist vendor/orba/magento2-codegen/config/codegen.yml
or
cp vendor/orba/magento2-codegen/config/codegen.yml.dist codegen.yml
```

and edit default values.

You may add your own template directory by including the following in your `codegen.yml`: 

```
templateDirectories:
  - { path: "path/to/templates" }
```

Template folder `path` must be relative to package directory, ex. if you want to add private templates to your Magento `dev` folder, you should use `../../../dev/codegen_templates` path.

You may include multiple template directories.

To overwrite a core template just copy a core template to your template directory and make changes as necessary.

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
../../../../vendor/bin/codegen template:generate block
```

```
cd /path/to/magento/vendor
bin/codegen --root-dir="/path/to/magento/app/code/Orba/TestModule" template:generate block
```

If specified root directory doesn't exist, it will be created automatically.

## Contribution

Feel free to contribute with new templates, bugfixes and features. Submit your code to review using pull request.

In [dev/docs.md](dev/docs.md) you can find the additional documentation for developers.
