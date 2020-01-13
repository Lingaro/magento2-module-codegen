# Technical documentation

## Working with templates

### Template files

Each template consists of set of files localized in a subdirectory of `templates` dir. The name of the template is the name of the subdirectory.

During template generation all its files are processed using Twig and the results are copied to the destination dir.

In template files contents (and their names too!) you can:

1. Use properties, so a user can personalize generated code; properties must be defined in template's config file (explained further),
2. Use filters:
   * [escape](https://twig.symfony.com/doc/3.x/filters/escape.html),
   * [upper](https://twig.symfony.com/doc/3.x/filters/upper.html),
   * [lower](https://twig.symfony.com/doc/3.x/filters/lower.html),
   * camel (converts a string to camelCase),
   * pascal (converts a string to PascalCase),
   * snake (converts a string to snake_case),
   * kebab (converts a string to kebab-case),
3. Use [ifs](https://twig.symfony.com/doc/3.x/tags/if.html),
4. Use [for loops](https://twig.symfony.com/doc/3.x/tags/for.html).
   
Each template dir has a special subdirectory `.no-copied-config`, which is not processed and copied as others. Inside there is a place for template config files.

### Template config

Main template config file can be found in `.no-copied-config/config.yml`. You can configure there the following aspects of a template:

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

If a template is dependant on some other templates (ie. they should be genearted while the main template is), their names should be placed in `dependencies` root node of `config.yml` file. 

Example:

```yaml
dependencies:
  - foo
  - bar
```

You can check how it works by playing with `frontPageController` template. It's dependant on `viewModel`.

### Template description

Longer description of a template can be placed inside `.no-copied-config/description.txt` file. It will be shown during template generation and when using `template:info` command.

### Additional info after template generation

If a user needs to take some additional steps after code is generated, their description may be added to `.no-copied-config/after-generate-info.txt` file. The content of this file is processed by Twig, so you can use properties inside.

Check `viewModel` template for real life example.

## 