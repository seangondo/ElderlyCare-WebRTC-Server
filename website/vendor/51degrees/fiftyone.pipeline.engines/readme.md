# 51Degrees PHP Pipeline Engines

![51Degrees](https://51degrees.com/DesktopModules/FiftyOne/Distributor/Logo.ashx?utm_source=github&utm_medium=repository&utm_content=readme_main&utm_campaign=php-open-source "Data rewards the curious") **PHP Pipeline API**

[Developer Documentation](https://51degrees.com/pipeline-php/index.html?utm_source=github&utm_medium=repository&utm_content=documentation&utm_campaign=php-open-source "developer documentation")

## Introduction
This project contains the source code for the 'engines' functionality for the PHP implementation of the 51Degrees Pipeline API.
		
## Tests
To run the tests in this repository, make sure PHPUnit is installed then, in the root of this repo, call:
```
phpunit --log-junit test-results.xml
```


## Development

When making changes to `pipeline.php.engines` repository, it may be necessary to link to a local development version of pipeline dependencies. For information on this, see [Composer local path](https://getcomposer.org/doc/05-repositories.md#path).

For exmaple, if a development version of `51degrees/fiftyone.pipeline.core` was stored locally, the location would be added with:

```
"repositories": [
	{
		"type": "path",
		"url": "../../path/to/packages/pipeline-php-core"
	}
]
```

then the dependency changed to:

```
"51degrees/fiftyone.pipeline.core": "*"
```
