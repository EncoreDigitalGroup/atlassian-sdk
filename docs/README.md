---
title: Atlassian SDK
sidebar_position: 1
---

# EncoreDigitalGroup/Atlassian-Sdk

This Atlassian SDK developed by [Encore Digital Group](https://github.com/EncoreDigitalGroup) provides a strongly typed method of interacting with Atlassian Products.

# Who Uses This SDK

Aside from Encore Digital Group, some notable users of this SDK are:

<a href="https://thunk.dev" target="_blank"><img src="./img/thunk.png" width="250"></img></a>

# We Love Laravel

The Atlassian SDK was originally built for use with Laravel only, but now it can be used in any framework, or no framework thanks to
[PHPGenesis](https://github.com/EncoreDigitalGroup/PHPGenesis).

# What's Included

Atlassian offers a number of cloud products, this SDK includes:

- [Jira Cloud](https://developer.atlassian.com/cloud/jira/platform/rest/v2/)
- More Upon Request or PR

# Installation

To install the Atlassian SDK, you can use Composer:

```bash
composer require encoredigitalgroup/atlassian-sdk
```

# Backwards Compatibility Promise

This SDK is built with backwards compatibility in mind. Encore Digital Group follows the [Semantic Versioning](https://semver.org) standard with a few exceptions:

- We will not bump the major version for a breaking change if the current major version is `0`. Instead, we will bump the minor version.
- We will not bump the major version for a breaking change if the breaking change is a result of a bug fix.
- Classes marked as `@internal` are not considered part of the public API and may change at any time.
- Classes marked as `@experimental` are not considered stable and may change at any time.

# License

License information can be found [here](https://docs.encoredigitalgroup.com/LicenseTerms/) file.