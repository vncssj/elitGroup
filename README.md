# ElitGroup API

This API has only one endpoint at the `/tiposAtendimentos` route, which returns a list of service types stored in the database.

## Execution
To run this API, Docker and Docker Compose concepts were employed. To initialize the application, simply execute `docker compose up` or `docker compose up -d` to free the terminal. Docker and Docker Compose need to be installed in the test environment. **The database is created, and initial records are added through Docker automation**. If the application is not initialized via Docker, the database must be created manually.

## About Development
This API was developed using pure PHP as per the guidance, and to achieve better code structure, the MVC pattern was applied. I created somewhat automated routing and followed an object-oriented approach.

## Improvement Suggestions
Similar to the routing systems, I believe implementing an ORM could enhance query development. Additionally, incorporating unit tests and creating some classes and interfaces to standardize the code further could be beneficial. An example of this is the middleware, which should return only boolean values; introducing a class and interfaces would make this part more consistent.