# Technical documentation

## Dev environment

### Automated (Orba developers only)

You can use our experimental Makefile for setting up automatically Orbento Skeleton (clean Magento 2 with sample data and the whole environment needed for Codegen development). Simply run the following command and wait for magic to happen ;-).

```
make up
```

Watchout: You need to install Docker and Docker Compose on your machine. Also, special permissions to our internal tools are needed to make everything work. Please contact DevOps team to get them.

Watchout: You will be asked by script for sudo password. This is needed for automated generation of hosts.

When the environment is ready (`[info] Running regular mode...` should be visible in your command line), you can open project dir in PHPStorm and start working with all the toys like PHP Unit and XDebug already configured. Your dev Magento will be available through https://orba.local URL. Codegen files will be placed in `source/magento/lib/internal/codegen`.

### Manual

If you are not lucky enough to work in Orba, you can use whatever local environment you prefer. Just remember to use `composer install` so you get all required dependencies and your PHP version (and required modules) is checked. 

## Working with templates

### Template files

Each template consists of set of files localized in a subdirectory of `templates` dir. The name of the template is the name of the subdirectory.

During template generation all its files are processed using Twig and the results are copied to the destination dir.

In the template files contents (and their names too!) you can:

1. Use properties, so a user can personalize generated code; properties must be defined in template's config file (explained further),
2. Use filters:
   * [escape](https://twig.symfony.com/doc/3.x/filters/escape.html),
   * [upper](https://twig.symfony.com/doc/3.x/filters/upper.html),
   * [lower](https://twig.symfony.com/doc/3.x/filters/lower.html),
   * [raw](https://twig.symfony.com/doc/3.x/filters/raw.html),
   * camel (converts a string to camelCase),
   * pascal (converts a string to PascalCase),
   * snake (converts a string to snake_case),
   * kebab (converts a string to kebab-case),
   * lower_only (removes all characters except letters and digits and converts string to lowercase)
   * ucfirst (makes first letter of a string capital)
3. Use functions (see: `src/Orba/Magento2Codegen/Service/StringFunction/*` to check what they do)
   * columnDefinition
   * databaseTypeToPHP
   * fullTextIndex
   * folderScope
4. Use [ifs](https://twig.symfony.com/doc/3.x/tags/if.html),
5. Use [for loops](https://twig.symfony.com/doc/3.x/tags/for.html).
   
Each template dir has a special subdirectory `.no-copied-config`, which is not processed and copied as others. Inside there is a place for template config file.

### Template config

Template config file can be found in `.no-copied-config/config.yml`. You can configure there the following aspects of a template:

#### Template properties

Properties definition should be placed in `properties` root node of `config.yml` file. Each property has a name, type and set of other config values. There are three types of properties:

##### String

String properties are the most common ones. For each of them you can define optional description and default value. During template generation a user will be simply asked for a value of each string property.

Exemplary config:

```yaml
properties:
  vendorName:
    type: "string"
    description: "A short name describing module"
    default: "Orba"
```

Exemplary template file:

```twig
Hi, I'm a vendor and my name is {{ vendorName }}.
```

Let's say a user put "Acme" as an answer to "vendorName" question. It will produce the following result:

```
Hi, I'm a vendor and my name is Acme.
```

##### Boolean

Boolean properties are used for all these situations in which you need to generate something like "0/1", "yes/no", "true/false" or you want to build an if condition, but you don't want a user to worry about the correct form of answer. For every boolean property you can define optional description and default value. During template generation a user will be simply asked for a "yes/no" answer for each boolean property.

Exemplary config:

```yaml
properties:
  required:
    type: "boolean"
    description: "Is field required"
    default: false
```

Exemplary template file:

```twig
<input type="checkbox"{% if required %} required="required"{% endif %}/>
```

Let's say a user put "yes" as an answer to "required" question. It will produce the following result:

```
<input type="checkbox" required="required"/>
```

##### Choice

Choice properties are used for all these situations in which you want a user to choose specific string value from an array of possible options. For every choice property you must define array of options and you can define optional description and default value. During template generation a user will see all possible options for each choice property and will not be allowed to use value not specified in config.

Exemplary config:

```yaml
properties:
  puppyType:
    type: "choice"
    options:
      - "dog"
      - "cat"
      - "snake"
    description: "Puppy type"
    default: "cat"
```

Exemplary template file:

```twig
My puppy is a {{ puppyType }}.
```

Let's say a user put "2" or "snake" (they can use index of option or a value) as an answer to "puppyType" question. It will produce the following result:

```
My puppy is a snake.
```

##### Array

Array properties are used for complex structures with for-in loops. For each of them you need to define `children`, ie. its subproperties (of any type). Array property has an optional description, but no default value. During template generation a user will be asked for a value of each child property, forming a single item. Then they will be asked if they want to proceed with another item, and so on.

Exemplary config:

```yaml
properties:
  columns:
    type: "array"
    description: "Array of database columns defintions"
    children:
      name:
        type: "string"
        description: "Column name"
      type:
        type: "string"
        description: "Column type"
        default: "varchar"
```

Exemplary template file:

```twig
My table has the following columns:
{% for column in columns %}
{{ column.name }} ({{ column.type }})
{% endfor %}
```

Let's say a user put "name" and "varchar" as answers to questions for first item and "age" and "int" as answers for questions for second item. Then they finished. It will produce the following result:

```
My table has the following columns:
name (varchar)
age (int)
```

##### Const

Const properties are rarely used. Each of them must have defined value. During template generation this value will be used. User will not be asked for any input.

Exemplary config:

```yaml
properties:
  pi:
    type: "const"
    description: "Value of pi"
    value: "3.1415"
```

Exemplary template file:

```twig
Hi, I'm Pi and my value is {{ pi }}.
```

It will produce the following result:

```
Hi, I'm Pi and my value is 3.1415.
```

#### Template dependencies

If a template is dependent on some other templates (ie. they should be generated while the main template is), their names should be placed in `dependencies` root node of `config.yml` file. 

Example:

```yaml
dependencies:
  - foo
  - bar
```

You can check how it works by playing with `frontPageController` template. It's dependent on `viewModel`.

#### Template description

Longer description of a template can be placed in `description` root node of `config.yml` file. It will be shown during template generation and when using `template:info` command.

Example:

```yaml
description: "This template is used to create a custom block and phtml template file for it."
```

#### Abstract template

If you want to create an abstract template that will be later used as a dependency of other template but cannot be generated itself, set `isAbstract` root node of `config.yml` file to `true`. Such template won't be shown in template list.

Example:

```yaml
isAbstract: true
```

#### Additional info after template generation

If a user needs to take some additional steps after code is generated, their description may be added in `afterGenearte` root node of `config.yml` file. The content of this param is processed by Twig, so you can use properties inside.

Check `viewModel` template for real life example.
