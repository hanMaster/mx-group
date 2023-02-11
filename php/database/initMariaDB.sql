CREATE TABLE IF NOT EXISTS rowaddress (
                                     id         SERIAL PRIMARY KEY,
                                     address       VARCHAR(1024)
);

CREATE TABLE IF NOT EXISTS kladr (
                                     id         SERIAL PRIMARY KEY,
                                     region     VARCHAR(256),
                                     city       VARCHAR(256),
                                     street     VARCHAR(256),
                                     house      VARCHAR(256)
);
