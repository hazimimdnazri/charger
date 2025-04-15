FROM node:23.11

WORKDIR /var/www/app

COPY app/package.json ./

RUN yarn

COPY  ./app .

CMD [ "yarn", "dev", "--host", "0.0.0.0" ]