FROM motherbrain/sylius-standard:1.13-traditional

RUN apk update --no-cache && apk add --no-cache \
    php81-bcmath \
    php81-sodium \
    php81-soap \
    php81-xmlreader
