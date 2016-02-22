FROM node:5.6

RUN git clone https://github.com/DBCDK/dbc-community-service.git /usr/src/app
WORKDIR /usr/src/app

RUN npm install

EXPOSE 3000

CMD [ "npm", "start" ]
