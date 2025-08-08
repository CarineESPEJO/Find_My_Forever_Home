DROP DATABASE IF EXISTS FMDH_DB;
CREATE DATABASE FMDH_DB;
USE FMDH_DB;

CREATE TABLE user (
	id INT PRIMARY KEY AUTO_INCREMENT,
	email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL UNIQUE, 
    role ENUM('admin', 'agent', 'user') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL
);

CREATE TABLE propertyType (
	id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL
    ); 


CREATE TABLE transactionType (
	id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL
);

DROP TABLE IF EXISTS listing;
CREATE TABLE listing (
	id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price INT NOT NULL,
    city VARCHAR(150) NOT NULL,
	image_URL VARCHAR(255) NULL,
	property_type_id INT NOT NULL,
    transaction_type_id INT NOT NULL,
    user_id INT NOT NULL,
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL,
    
    CONSTRAINT fk_list_property_type_id FOREIGN KEY (property_type_id) REFERENCES propertyType(id) ,
    CONSTRAINT fk_list_transaction_type_id FOREIGN KEY (transaction_type_id) REFERENCES transactionType(id),
    CONSTRAINT fk_list_user_id FOREIGN KEY (user_id) REFERENCES user(id)
);

DROP TABLE IF EXISTS favorite;
CREATE TABLE favorite (
    user_id INT NOT NULL,
    listing_id INT NOT NULL,

    CONSTRAINT pk_favorite PRIMARY KEY (user_id, listing_id),
    CONSTRAINT fk_fav_user_id FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    CONSTRAINT fk_fav_listing_id FOREIGN KEY (listing_id) REFERENCES listing(id) ON DELETE CASCADE
);


INSERT INTO user (email, password, role, updated_at) VALUES
('admin@mail.fr', 'azerty', 'admin', NOW()),
('carl@mail.fr', 'carl', 'admin',NOW()),
('theo@mail.fr', 'theo', 'agent', NOW()),
('vivi@mail.fr', 'vivi', 'agent', NOW()),
('max@mail.fr', 'max', 'user', NOW()),
('leo@mail.fr', 'leo', 'user', NOW()),
('cabi@mail.fr', 'cabi', 'user', NOW());

INSERT INTO propertyType (name, updated_at) VALUES
('House', NOW()),
('Apartment', NOW());

INSERT INTO transactionType (name, updated_at) VALUES
('Rent', NOW()),
('Sale', NOW());

INSERT INTO listing (title, description, price, city, image_URL, property_type_id, transaction_type_id, user_id, updated_at) VALUES
('Magnifique appartement contemporain avec vue imprenable sur la mer et terrasse panoramique de 150m² orientée plein sud',
    'Ce superbe appartement de 5 pièces situé au dernier étage d\'une résidence de standing offre une vue mer exceptionnelle, des prestations haut de gamme, une cuisine ouverte entièrement équipée, 3 chambres spacieuses, 2 salles de bains modernes, une cave, et un double garage. Proche de toutes commodités, écoles, commerces, et transports.',
    999999,
    'saint raphaël les bains sur mer en provence',
    '/assets/images/annonces/file_00000000000000.00000001.jpg', 2, 2, 1, NOW()),
('Maison de campagne', 'Charmante maison de campagne offrant tranquilité et espace, idéale pour une escapade familiale', 2000, 'lyon',
'/assets/images/annonces/file_00000000000000.00000002.jpg', 
 1, 1, 4, NOW()),
('Penthouse luxueux', 'Penthouse exclusif avec vue imprenable sur la mer, doté d\'aménagements de luxe et d\'une salle de spa intégrée.', 3000000, 'nice',
'/assets/images/annonces/file_00000000000000.00000003.jpg',
 1, 2, 2, NOW()),
('Chalet en montagne', 'Beau chalet traditionnel en bois, niché dans les montagnes, parfait pour les amateurs de ski et de fortes sensations.', 750000, 'charmonix',
'/assets/images/annonces/file_00000000000000.00000004.jpg',
1, 2, 3, NOW()),
('Appartement à double étages','Spacieux appartement sur deux niveaux avec mezzanine, lumineux et idéalement situé en centre-ville.', 1200000, 'paris',
'/assets/images/annonces/file_00000000000000.00000005.jpg',
2, 2, 2, NOW()),
('Appartement rustique','Appartement chaleureux au style rustique avec poutres apparentes, parfait pour un séjour cosy à la campagne.', 850, 'Toulouse',
'/assets/images/annonces/file_00000000000000.00000006.jpg',
2, 1, 4, NOW()),
('Appartement T3 industriel', 'Appartement T3 moderne style industriel avec balcon et cuisine équipée, proche des commodités et transports.', 280000, 'Marseille',
'/assets/images/annonces/file_00000000000000.00000007.jpg',
2, 2, 3, NOW());

INSERT INTO listing (title, description, price, city, image_URL, property_type_id, transaction_type_id, user_id, updated_at) VALUES
('Villa moderne avec piscine', 'Villa contemporaine avec piscine, jardin paysager et grande terrasse.', 1500000, 'Cannes', '/assets/images/annonces/file_00000000000000.00000008.jpg', 1, 2, 1, NOW()),
('Appartement cosy en centre-ville', 'Appartement lumineux avec balcon, proche commerces et transports.', 450000, 'Lyon', '/assets/images/annonces/file_00000000000000.00000009.jpg', 2, 2, 2, NOW()),
('Maison familiale avec grand jardin', 'Maison spacieuse avec 4 chambres, jardin et garage double.', 700000, 'Bordeaux', '/assets/images/annonces/file_00000000000000.00000010.jpg', 1, 2, 3, NOW()),
('Studio étudiant', 'Studio fonctionnel proche université et commerces.', 120000, 'Toulouse', '/assets/images/annonces/file_00000000000000.00000011.jpg', 2, 1, 4, NOW()),
('Appartement neuf avec terrasse', 'Appartement neuf avec grande terrasse, prestations haut de gamme.', 600000, 'Nice', '/assets/images/annonces/file_00000000000000.00000012.jpg', 2, 2, 1, NOW()),
('Chalet de montagne avec vue', 'Chalet charmant avec vue panoramique sur les Alpes.', 850000, 'Chamonix', '/assets/images/annonces/file_00000000000000.00000013.jpg', 1, 2, 2, NOW()),
('Loft industriel design', 'Loft spacieux avec éléments industriels et grandes baies vitrées.', 550000, 'Marseille', '/assets/images/annonces/file_00000000000000.00000014.jpg', 2, 2, 3, NOW()),
('Maison de village rénovée', 'Maison ancienne rénovée avec goût au cœur du village.', 350000, 'Saint-Paul-de-Vence', '/assets/images/annonces/file_00000000000000.00000015.jpg', 1, 2, 4, NOW()),
('Appartement avec vue sur parc', 'Appartement calme et lumineux donnant sur un grand parc.', 400000, 'Paris', '/assets/images/annonces/file_00000000000000.00000016.jpg', 2, 2, 1, NOW()),
('Villa provençale avec jardin', 'Belle villa traditionnelle avec jardin méditerranéen.', 1300000, 'Avignon', '/assets/images/annonces/file_00000000000000.00000017.jpg', 1, 2, 2, NOW()),
('Appartement en duplex', 'Duplex moderne avec mezzanine et grandes fenêtres.', 480000, 'Lille', '/assets/images/annonces/file_00000000000000.00000018.jpg', 2, 2, 3, NOW()),
('Maison avec piscine chauffée', 'Maison familiale avec piscine chauffée et pool house.', 950000, 'Nîmes', '/assets/images/annonces/file_00000000000000.00000019.jpg', 1, 2, 4, NOW()),
('Petit appartement pour investisseur', 'Appartement de 2 pièces à rénover, idéal investissement.', 160000, 'Montpellier', '/assets/images/annonces/file_00000000000000.00000020.jpg', 2, 1, 1, NOW()),
('Villa contemporaine design', 'Villa neuve avec architecture contemporaine et piscine.', 2100000, 'Saint-Tropez', '/assets/images/annonces/file_00000000000000.00000021.jpg', 1, 2, 2, NOW()),
('Appartement avec balcon terrasse', 'Appartement confortable avec balcon et vue dégagée.', 520000, 'Nantes', '/assets/images/annonces/file_00000000000000.00000022.jpg', 2, 2, 3, NOW()),
('Maison ancienne avec cachet', 'Maison en pierre avec poutres apparentes et jardin.', 780000, 'Dordogne', '/assets/images/annonces/file_00000000000000.00000023.jpg', 1, 2, 4, NOW()),
('Appartement lumineux proche gare', 'Appartement lumineux et calme proche de la gare.', 430000, 'Strasbourg', '/assets/images/annonces/file_00000000000000.00000024.jpg', 2, 2, 1, NOW()),
('Chalet avec spa et sauna', 'Chalet haut de gamme avec spa privé et sauna.', 1100000, 'Les Arcs', '/assets/images/annonces/file_00000000000000.00000025.jpg', 1, 2, 2, NOW()),
('Appartement dans résidence sécurisée', 'Appartement récent dans résidence avec piscine.', 490000, 'Biarritz', '/assets/images/annonces/file_00000000000000.00000026.jpg', 2, 2, 3, NOW()),
('Maison écologique en bois', 'Maison écologique en bois avec panneaux solaires.', 890000, 'Annecy', '/assets/images/annonces/file_00000000000000.00000027.jpg', 1, 2, 4, NOW()),
('Appartement avec parking privatif', 'Appartement avec place de parking et ascenseur.', 460000, 'Toulon', '/assets/images/annonces/file_00000000000000.00000028.jpg', 2, 2, 1, NOW()),
('Maison de charme avec vue mer', 'Maison de charme avec vue panoramique sur la Méditerranée.', 1250000, 'Hyères', '/assets/images/annonces/file_00000000000000.00000029.jpg', 1, 2, 2, NOW()),
('Appartement cosy avec jardin', 'Appartement en rez-de-jardin avec terrasse et jardin.', 550000, 'Grenoble', '/assets/images/annonces/file_00000000000000.00000030.jpg', 2, 2, 3, NOW()),
('Villa avec terrain de tennis', 'Villa spacieuse avec terrain de tennis privé.', 1950000, 'Cannes', '/assets/images/annonces/file_00000000000000.00000031.jpg', 1, 2, 4, NOW()),
('Appartement ancien rénové', 'Appartement ancien rénové avec parquet et cheminée.', 475000, 'Bordeaux', '/assets/images/annonces/file_00000000000000.00000032.jpg', 2, 2, 1, NOW()),
('Maison plain-pied avec garage', 'Maison de plain-pied avec grand garage et jardin.', 680000, 'Tours', '/assets/images/annonces/file_00000000000000.00000033.jpg', 1, 2, 2, NOW()),
('Appartement neuf en centre-ville', 'Appartement neuf avec cuisine équipée et balcon.', 530000, 'Nantes', '/assets/images/annonces/file_00000000000000.00000034.jpg', 2, 2, 3, NOW()),
('Chalet traditionnel rénové', 'Chalet en bois rénové avec chauffage au sol.', 770000, 'Megève', '/assets/images/annonces/file_00000000000000.00000035.jpg', 1, 2, 4, NOW()),
('Appartement avec vue sur la mer', 'Appartement avec terrasse et vue imprenable sur la mer.', 600000, 'Nice', '/assets/images/annonces/file_00000000000000.00000036.jpg', 2, 2, 1, NOW()),
('Maison de ville avec cour intérieure', 'Maison de ville avec charmante cour intérieure.', 720000, 'Lyon', '/assets/images/annonces/file_00000000000000.00000037.jpg', 1, 2, 2, NOW()),
('Appartement avec mezzanine', 'Appartement moderne avec mezzanine et grandes fenêtres.', 510000, 'Strasbourg', '/assets/images/annonces/file_00000000000000.00000038.jpg', 2, 2, 3, NOW()),
('Villa avec grande terrasse', 'Villa avec grande terrasse et jardin arboré.', 1400000, 'Montpellier', '/assets/images/annonces/file_00000000000000.00000039.jpg', 1, 2, 4, NOW()),
('Appartement de standing', 'Appartement de standing avec prestations haut de gamme.', 670000, 'Paris', '/assets/images/annonces/file_00000000000000.00000040.jpg', 2, 2, 1, NOW()),
('Maison avec vue montagne', 'Maison traditionnelle avec vue sur les montagnes.', 800000, 'Grenoble', '/assets/images/annonces/file_00000000000000.00000041.jpg', 1, 2, 2, NOW()),
('Appartement lumineux et moderne', 'Appartement moderne avec grande baie vitrée et balcon.', 480000, 'Lille', '/assets/images/annonces/file_00000000000000.00000042.jpg', 2, 2, 3, NOW()),
('Maison de caractère', 'Maison ancienne avec beaucoup de charme et jardin.', 850000, 'Dijon', '/assets/images/annonces/file_00000000000000.00000043.jpg', 1, 2, 4, NOW());

INSERT INTO favorite (user_id, listing_id) VALUES 
(1,3), (1,7), (1,1), (1,5),
(2,6), (2,2), (2,4),
(3,1), (3,4), (3,3), (3,5), (3,7),
(4,2), (4,6), (4,3),
(5,5), (5,1), (5,7), (5,6),
(6,4), (6,2),
(7,7), (7,3), (7,2), (7,1), (7,6), (7,5);

DROP USER IF EXISTS 'admin'@'%';
CREATE USER 'admin'@'%' IDENTIFIED BY 'mdp';
GRANT SELECT, INSERT, UPDATE, DELETE ON FMDH_DB.* TO 'admin'@'%';
FLUSH PRIVILEGES;


SELECT image_URL, title, price, protyp.name AS property_type, tratyp.name AS transaction_type, city, description, lis.created_at AS created_at,lis.updated_at AS updated_at
FROM listing AS lis
JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
WHERE protyp.name = 'House'
ORDER BY lis.id;

SELECT  * FROM listing;
