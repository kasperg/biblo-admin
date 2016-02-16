FROM node:5.6

# The original repo requires the database to run on the same host as the app
# This fork/branch allows for configuration of database through environment
# variables. Thus we can run postgres on a separate host using standard images.
# Also we need to update the loopback explorer to get Swagger 2.0 specification.
RUN git clone https://github.com/kasperg/dbc-community-service.git -b biblo-admin /usr/src/app
WORKDIR /usr/src/app

RUN npm install

EXPOSE 3000

CMD [ "npm", "start" ]
