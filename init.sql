
CREATE DATABASE CheapoMail;

CREATE TABLE cheapomail.user
(
  id int PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(8) NOT NULL
);
ALTER TABLE cheapomail.user ADD CONSTRAINT unique_username UNIQUE (username);

CREATE TABLE cheapomail.Message
(
  id int PRIMARY KEY NOT NULL,
  body VARCHAR(2000) NOT NULL,
  subject VARCHAR(30) DEFAULT NULL ,
  user_id VARCHAR(30) NOT NULL,
  recipient_ids VARCHAR(1100) NOT NULL
);

CREATE TABLE cheapomail.Message_read
(
  id int PRIMARY KEY NOT NULL,
  message_id int NOT NULL,
  reader_id int NOT NULL,
  date date NOT NULL
);
ALTER TABLE cheapomail.Message_read ADD CONSTRAINT unique_id UNIQUE (id);

ALTER TABLE cheapomail.message
ADD FOREIGN KEY (user_id) REFERENCES 'user' (id);

ALTER TABLE cheapomail.message_read
ADD FOREIGN KEY (message_id) REFERENCES message (id);

ALTER TABLE cheapomail.message_read
ADD FOREIGN KEY (reader_id) REFERENCES 'user' (id);
