# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.3.0] - 2021-03-22
### Added
- Template: `queueMessage` [ZZ01-62](https://orba.atlassian.net/browse/ZZ01-62)
- Template: `widget` [ZZ01-105](https://orba.atlassian.net/browse/ZZ01-105)
- Twig filter: `titleize` [ZZ01-105](https://orba.atlassian.net/browse/ZZ01-105)

### Fixed
- Missing event prefix in model classes [ZZ01-125](https://orba.atlassian.net/browse/ZZ01-125)

## [2.2.1] - 2020-12-15
### Added
- `module` template: composer.json PHP version property [ZZ01-122](https://orba.atlassian.net/browse/ZZ01-122)

### Fixed
- Use raw value of `commentsCompanyName` in comments in all templates [ZZ01-121](https://orba.atlassian.net/browse/ZZ01-121)

## [2.2.0] - 2020-12-10
### Added
- Compatibility with Magento 2.4.x [ZZ01-115](https://orba.atlassian.net/browse/ZZ01-115)

### Changed
- `modelRelation1To1` and `modelRelation1ToN` templates marked as experimental

### Fixed
- `addProductAttributes` template: invalid XML declaration in extension_attributes.xml file [ZZ01-116](https://orba.atlassian.net/browse/ZZ01-116)
- `addProductAttributes` template: patch dependencies are not generated [ZZ01-119](https://orba.atlassian.net/browse/ZZ01-119)
- `configField` template: syntax error in generated code for global scope [ZZ01-120](https://orba.atlassian.net/browse/ZZ01-120)
- `eventObserver` template: events.xml merged incorrectly [ZZ01-117](https://orba.atlassian.net/browse/ZZ01-117)
- `jsModule` template: incorrect escaping of JSON config [ZZ01-118](https://orba.atlassian.net/browse/ZZ01-118)
- `quoteFields` template: invalid interfaces used in generated code [ZZ01-112](https://orba.atlassian.net/browse/ZZ01-112)

## [2.1.0] - Skipped (do not install this version)

## [2.0.0] - 2020-08-10
### Added
- Template type definition [ZZ01-72](https://orba.atlassian.net/browse/ZZ01-72)
- Template type: `basic` [ZZ01-72](https://orba.atlassian.net/browse/ZZ01-72)
- Template type: `root` [ZZ01-72](https://orba.atlassian.net/browse/ZZ01-72)
- Template type: `module` [ZZ01-72](https://orba.atlassian.net/browse/ZZ01-72)
- Template: `cache` [ZZ01-71](https://orba.atlassian.net/browse/ZZ01-71)
- Template: `import` [ZZ01-104](https://orba.atlassian.net/browse/ZZ01-104)
- Template: `jsMixin` [ZZ01-69](https://orba.atlassian.net/browse/ZZ01-69)
- Template: `modelRelation1To1` [ZZ01-90](https://orba.atlassian.net/browse/ZZ01-90)
- Template: `modelRelation1ToN` [ZZ01-89](https://orba.atlassian.net/browse/ZZ01-89)
- Template: `searchCriteriaUsage` [ZZ01-66](https://orba.atlassian.net/browse/ZZ01-66)
- Template: `theme` [ZZ01-99](https://orba.atlassian.net/browse/ZZ01-99)
- File merger: `app/etc/config.php` [ZZ01-98](https://orba.atlassian.net/browse/ZZ01-98)
- File merger: `cache.xml` [ZZ01-71](https://orba.atlassian.net/browse/ZZ01-71)
- File merger: `import.xml` [ZZ01-104](https://orba.atlassian.net/browse/ZZ01-104)
- File merger: `module.xml` [ZZ01-101](https://orba.atlassian.net/browse/ZZ01-101)
- Twig filter: `pluralize` [ZZ01-88](https://orba.atlassian.net/browse/ZZ01-88)
- Possibility to use `depend` property param on an array item [ZZ01-84](https://orba.atlassian.net/browse/ZZ01-84)

### Changed
- Refactoring of template generation: template object is now represented by a model [ZZ01-72](https://orba.atlassian.net/browse/ZZ01-72)
- Refactoring of template generation: template properties have now cleaner structure, property builders were introduced and used in property factories [ZZ01-86](https://orba.atlassian.net/browse/ZZ01-86)
- Options for JSON merger can be now injected in constructor [ZZ01-101](https://orba.atlassian.net/browse/ZZ01-101)
- Template: `consoleCommand` (changed convention of naming command/filepath) [ZZ01-107](https://orba.atlassian.net/browse/ZZ01-107)

### Fixed
- `declare (strict_types=1)` is not merged correctly by PHP merger [ZZ01-108](https://orba.atlassian.net/browse/ZZ01-108)

## [1.1.3] - 2020-07-24
### Added
- Dev documentation about `depend` and `required` property params [ZZ01-83](https://orba.atlassian.net/browse/ZZ01-83)

### Changed
- Behavior of `camel`, `pascal`, `snake` and `kebab` filters for strings with multiple capital letters [ZZ01-100](https://orba.atlassian.net/browse/ZZ01-100)

### Fixed
- Error thrown when trying to generate code from external template [ZZ01-102](https://orba.atlassian.net/browse/ZZ01-102)
- "The attribute 'length' is not allowed" error thrown when generating 'model' or 'quoteFields' template [ZZ01-96](https://orba.atlassian.net/browse/ZZ01-96)

## [1.1.2] - 2020-07-20
### Fixed
- Issue of `frontPageController` and `frontPostController` templates generating invalid code for controller action named `action` [ZZ01-95](https://orba.atlassian.net/browse/ZZ01-95)

## [1.1.1] - 2020-07-01
### Fixed
- Issue of `addProductAttributes` template param `patchDependencies` being required [ZZ01-87](https://orba.atlassian.net/browse/ZZ01-87)

## [1.1.0] - 2020-06-30
### Added
- Template: `addProductAttributes` [ZZ01-46](https://orba.atlassian.net/browse/ZZ01-46)
- Template: `apiEndpoint` [ZZ01-61](https://orba.atlassian.net/browse/ZZ01-61)
- Template: `cron` [ZZ01-70](https://orba.atlassian.net/browse/ZZ01-70)
- Template: `emailTemplate` [ZZ01-65](https://orba.atlassian.net/browse/ZZ01-65)
- Template: `jsModule` [ZZ01-63](https://orba.atlassian.net/browse/ZZ01-63)
- Template: `quoteFields` [ZZ01-59](https://orba.atlassian.net/browse/ZZ01-59)
- File merger: `cron_groups.xml` [ZZ01-70](https://orba.atlassian.net/browse/ZZ01-70)
- File merger: `crontab.xml` [ZZ01-70](https://orba.atlassian.net/browse/ZZ01-70)
- File merger: `email_templates.xml` [ZZ01-65](https://orba.atlassian.net/browse/ZZ01-65)
- File merger: `extension_attributes.xml` [ZZ01-59](https://orba.atlassian.net/browse/ZZ01-59)
- File merger: `fieldset.xml` [ZZ01-59](https://orba.atlassian.net/browse/ZZ01-59)
- File merger: JSON [ZZ01-74](https://orba.atlassian.net/browse/ZZ01-74)
- File merger: `requirejs-config.js` [ZZ01-63](https://orba.atlassian.net/browse/ZZ01-63)
- File merger: UI listing component [ZZ01-45](https://orba.atlassian.net/browse/ZZ01-45)
- File merger: UI form component [ZZ01-45](https://orba.atlassian.net/browse/ZZ01-45)
- File merger: `webapi.xml` [ZZ01-61](https://orba.atlassian.net/browse/ZZ01-61)
- Template config parameter: `isAbstract` [ZZ01-50](https://orba.atlassian.net/browse/ZZ01-50)
- Property attribute: `required` (for top-level properties) [ZZ01-85](https://orba.atlassian.net/browse/ZZ01-85)
- Property attribute: `required` (for array property children) [ZZ01-61](https://orba.atlassian.net/browse/ZZ01-61)
- Property attribute: `depend` (for top-level properties) [ZZ01-70](https://orba.atlassian.net/browse/ZZ01-70)
- Unit tests for PHP file merger [ZZ01-73](https://orba.atlassian.net/browse/ZZ01-73)
- Generated files with empty content are now not saved [ZZ01-70](https://orba.atlassian.net/browse/ZZ01-70) 
- Possibility to use codegen.yml config file from Magento root [ZZ01-77](https://orba.atlassian.net/browse/ZZ01-77)
- Possibility to define additional folders with templates [ZZ01-78](https://orba.atlassian.net/browse/ZZ01-78)
- Basic Bitbucket pipeline [ZZ01-82](https://orba.atlassian.net/browse/ZZ01-82)
- Changelog file [ZZ01-85](https://orba.atlassian.net/browse/ZZ01-85)

### Changed
- All templates from 1.0.0 updated to use `required` property attribute [ZZ01-85](https://orba.atlassian.net/browse/ZZ01-85)

### Fixed
- PHP merger method's concatenation issue [ZZ01-73](https://orba.atlassian.net/browse/ZZ01-73)
- Minor issues in Magento Dom helper class [ZZ01-45](https://orba.atlassian.net/browse/ZZ01-45)

### Removed
- Unnecessary Magento Composer repository definition in `composer.json`
