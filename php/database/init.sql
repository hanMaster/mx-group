CREATE TABLE IF NOT EXISTS street (
                                     id         SERIAL PRIMARY KEY,
                                     name       VARCHAR(40),
                                     socr       VARCHAR(10),
                                     code       VARCHAR(17),
                                     index      VARCHAR(6),
                                     gninmb     VARCHAR(4),
                                     uno        VARCHAR(4),
                                     ocatd      VARCHAR(11)
);

CREATE TABLE IF NOT EXISTS kladr (
                                     id         SERIAL PRIMARY KEY,
                                     name       VARCHAR(40),
                                     socr       VARCHAR(10),
                                     code       VARCHAR(13),
                                     index      VARCHAR(6),
                                     gninmb     VARCHAR(4),
                                     uno        VARCHAR(4),
                                     ocatd      VARCHAR(11),
                                     status     VARCHAR(1)
);

CREATE TABLE IF NOT EXISTS doma (
                                     id         SERIAL PRIMARY KEY,
                                     name       VARCHAR(40),
                                     korp       VARCHAR(10),
                                     socr       VARCHAR(10),
                                     code       VARCHAR(19),
                                     index      VARCHAR(6),
                                     gninmb     VARCHAR(4),
                                     uno        VARCHAR(4),
                                     ocatd      VARCHAR(11)
);