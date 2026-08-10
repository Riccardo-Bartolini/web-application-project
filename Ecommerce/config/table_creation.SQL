
-- Creazione tabella User
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nome VARCHAR(100),
    cognome VARCHAR(100),
    venditore BOOLEAN NOT NULL DEFAULT FALSE,
    indirizzo VARCHAR(255) NOT NULL,
    citta VARCHAR(100) NOT NULL
);

-- Creazione tabella Notifiche
CREATE TABLE Notifiche (
    id INT AUTO_INCREMENT PRIMARY KEY,
    testo TEXT NOT NULL,
    data DATE NOT NULL,
    user_id INT,
    letta BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
);



-- Creazione tabella Product
CREATE TABLE Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unitCost INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    marca VARCHAR(100),
    descrizione TEXT,
    condizioni VARCHAR(50),
    imgPath1 VARCHAR(255),
    imgPath2 VARCHAR(255),
    imgPath3 VARCHAR(255),
    vendor_id INT NOT NULL,
    FOREIGN KEY (vendor_id) REFERENCES User(id) ON DELETE CASCADE
);


-- Creazione tabella Ordine
CREATE TABLE Ordine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATE NOT NULL,
    prezzoTotale INT NOT NULL,
    nome VARCHAR(100),        
    cognome VARCHAR(100),
    email VARCHAR(200),     
    indirizzo VARCHAR(255),
    citta VARCHAR(100),
    user_id INT NULL,
    codiceOrdine VARCHAR(10) UNIQUE,        
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE SET NULL
);


-- Creazione tabella ContenutoOrdine
CREATE TABLE ContenutoOrdine (
    idOrdine INT,
    idProduct INT,
    quantita INT NOT NULL,
    PRIMARY KEY (idOrdine, idProduct),
    FOREIGN KEY (idOrdine) REFERENCES Ordine(id) ON DELETE CASCADE,
    FOREIGN KEY (idProduct) REFERENCES Product(id) ON DELETE CASCADE
);

--  password inserite:
-- 'password123',
-- 'securepassword',
-- 'password456'
INSERT INTO User (email, password, nome, cognome, venditore, indirizzo, citta)
VALUES
('mario.rossi@example.com', '$2y$10$o4BZR3vuwCR20.jle/8chuBVzRB63z2hwlVe8j/KkCHInwjOHiShy', 'Mario', 'Rossi', FALSE, 'Via Roma 10', 'Milano'),
('lucia.bianchi@example.com', '$2y$10$tcjsxOGlEnJKiyBCg1rIau/akyNZUmQLMp3aHh6xNU3nck/tuAK7W', 'Lucia', 'Bianchi', TRUE, 'Viale Europa 5', 'Torino'),
('giovanni.verdi@example.com', '$2y$10$EaV9bp/u6lZ8wIXDoMKMi.apeO4e/pDz6SdJkTWcunlGdXMRnq8iG', 'Giovanni', 'Verdi', FALSE, 'Via Verdi 20', 'Bologna');


INSERT INTO Notifiche (testo, data, user_id, letta)
VALUES
('Nuova notifica per te!', '2024-12-01', 1, FALSE),
('Il tuo ordine è stato spedito.', '2024-12-02', 2, FALSE),
('Promozione speciale in corso!', '2024-12-03', 3, FALSE);


INSERT INTO Product (unitCost, name, marca, descrizione, condizioni, imgPath1, imgPath2, imgPath3, vendor_id)
VALUES
(5000, 'Orologio Lusso A1', 'Rolex', 'Orologio da polso di lusso, edizione limitata, con movimento automatico.', 'Nuovo', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 2),
(1200, 'Orologio Elegante B2', 'Omega', 'Orologio elegante con cinturino in pelle, quadrante con datario.', 'Nuovo', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 2),
(3500, 'Orologio Sportivo C3', 'Tag Heuer', 'Orologio sportivo con cronografo, resistenza all’acqua fino a 100 metri.', 'Nuovo', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 2),
(2000, 'Orologio Luxury D4', 'Patek Philippe', 'Orologio di lusso con cassa in oro e cinturino in metallo.', 'Nuovo', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 2),
(800, 'Orologio Casual E5', 'Seiko', 'Orologio casual con design minimalista e cinturino in acciaio inox.', 'Nuovo', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 'templates/public/images/img-card-example.png', 2);

INSERT INTO Ordine (data, prezzoTotale, nome, cognome, email, indirizzo, citta, user_id, codiceOrdine)
VALUES
('2024-12-01', 350, 'Mario', 'Rossi', 'mario.rossi@example.com', 'Via Roma, 10', 'Milano', 1, 'ORD001'),
('2024-12-02', 180, 'Lucia', 'Bianchi', 'lucia.bianchi@example.com', 'Viale Europa, 5', 'Torino', 2, 'ORD002'),
('2024-12-03', 120, 'Giovanni', 'Verdi', 'giovanni.verdi@example.com', 'Via Verdi, 20', 'Bologna', 3, 'ORD003');


INSERT INTO ContenutoOrdine (idOrdine, idProduct, quantita)
VALUES
(1, 1, 1),  -- Mario Rossi ha ordinato 1 Orologio Lux
(2, 2, 2),  -- Lucia Bianchi ha ordinato 2 Borse Eleganti
(3, 3, 1);  -- Giovanni Verdi ha ordinato 1 Paio di Scarpe Sportive
