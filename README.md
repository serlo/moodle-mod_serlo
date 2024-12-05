## Moodle - mod Serlo moodle integration
This plugin adds a serlo mod to moodle

## Author
* Author: [adornis.de](https://adornis.de/)
* Min. required: Moodle 4.4
* Supports PHP: 8.1

![Moodle404](https://img.shields.io/badge/moodle-4.4-brightgreen.svg?logo=moodle)

![PHP8.1](https://img.shields.io/badge/PHP-8.1-brightgreen.svg?logo=php)

## List of features

- List of features
- ...

## Installation

1.  Copy this plugin to the `mod\serlo` folder on the server
2.  Login as administrator
3.  Go to Site Administrator > Notification
4.  Install the plugin

## Usage
- 

## License

Apache License, Version 2.0. Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via Pull Requests on Github.

## Development

This plugin uses the @serlo/editor-web-component npm package which need to be bundled to be used correctly by moodle.

The package.json includes a `libs/serlo-editor` script which bundles the package and runs the moodle grunt task to generate the file used by moodle.

Since the complete task takes some time you might want to use the `npx grunt watch` task in the moodle root directory.

To make this work, you have to patch the watch task to not run the eslint stage:

```js
//moodle/.grunt/tasks/javascript.js
- 189 tasks: ['amd']
+ 189 tasks: ['rollup']
```

Also you need the `watchman` binary to be present in your path: See [here](https://facebook.github.io/watchman/docs/install.html) on how to get it.

> If this binary is missing grunt may mistakenly throw the "Watchman CLI is installed but failed due to permission errors" error.

then you can start `npx grunt watch` in the moodle root dir.

Afterwards start the `watch` script out of the serlo plugin dir to start esbuild in watch mode.

## File Storage Configuration

On testing environments, use this secret in the moodle options: `VJN8pHhqVj8RtO+TfY2/Ka1JN4JdH/oSOAdPHz5a`. It will allow access to the default Serlo asset server.