create DATABASE livrodb; 
USE livrodb;

CREATE TABLE livro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    autor VARCHAR(150) NOT NULL,
);  