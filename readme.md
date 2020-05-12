# Magento 2 Code Generator

Inspired by https://github.com/staempfli/magento2-code-generator

## Usage

1. List all templates: `bin/codegen template:list`
2. Show template info: `bin/codegen template:info <template>`
3. Generate template: `bin/codegen template:generate <template>`

**NOTE**:
    
* `template:generate` command must be executed on the module root folder where the `registration.php` file is.
You can also use option `--root-dir` to specify this path, if you execute it from a different location.

* When creating a new `module`, you must create first the module parent folder and execute the command from there.