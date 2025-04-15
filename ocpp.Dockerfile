FROM node:23.11

WORKDIR /var/www/ocpp

COPY ocpp/package.json ./

RUN yarn install

COPY  ./ocpp .

CMD [ "yarn", "start" ]