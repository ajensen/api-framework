# api-framework 
Sample API Framework

Serves as integration layer API framework to be used for transactions with the data layer, as well as between the presentation and business layers

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
backend-framework repository

AWS SDK https://github.com/aws/aws-sdk-php

PHP Redis https://anton.logvinenko.site/en/blog/how-to-install-redis-and-redis-php-client.html

SendGrid credentials (if using email methods)

Twilio credentials (if using SMS messaging methods)
```

### Installing

Set and configure a port for the API to be reached from

Clone the backend-framework repository and set the appropriate local environment paths and port within the included env.php

```
git clone https://github.com/ajensen/backend-framework.git
```

## Deployment

Install and/or configure all prerequisites listed above

If using non-locally, ensure that the configured API port is accessible remotely. Ignore if connecting to this api-framework only locally.

Import SQL files from the sql folder

```

SQL files supporting this deployment have not yet been added to this repository 

```

## Versioning

* 0.1

## Authors

* **Andrew Jensen** - [ajensen](https://github.com/ajensen)

## Contributing

* No additional contributors

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to AndrewMichaelJensen.com

## Built With

* Awesomeness!