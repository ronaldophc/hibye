SET foreign_key_checks = 0;

DROP TABLE IF EXISTS positions;

CREATE TABLE positions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS workers;

CREATE TABLE workers
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  cpf CHAR(11) UNIQUE NOT NULL,
  address VARCHAR(100),
  sex VARCHAR(15),
  daily_hours INT NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL
);

SET foreign_key_checks = 1;
