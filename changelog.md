# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
