#!/bin/sh

CREATE DATABASE blog;
CREATE DATABASE blog_dusk;
GRANT ALL PRIVILEGES ON *.* TO 'blog'@'%';
