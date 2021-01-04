use ubc_test_4;

INSERT INTO users (id,nom,prenom,email,email_recup,tel,dob,password,is_driver,bio,created_at) 
VALUES
(1,'Golay','David','David_Golay@etu.u-bourgogne.fr','David@gmail.com','0610099802','1998-08-02','7110eda4d09e062aa5e4a390b0a572ac0d2c0220',1,"C'est moi David",'2020-09-21 15:25:05'),
(2,'Moniot','Lucas','Lucas_Moniot@etu.u-bourgogne.fr','Lucas@gmail.com',0782993771,'2002-07-23','7110eda4d09e062aa5e4a390b0a572ac0d2c0220',1,"C'est moi Lucas",'2020-09-21 21:03:47'),
(3,'Perzo-Joly','Alexandre','Alexandre_Perzo-Joly@etu.u-bourgogne.fr','Alexandre@gmail.com',0781381203,'2002-09-11','7110eda4d09e062aa5e4a390b0a572ac0d2c0220',0,"Toujours là pour la bonne humeur",'2019-12-25 23:00:49'),
(4,'Lorenzo','Eileen','Eileen_Lorenzo@etu.u-bourgogne.fr','Eileen@gmail.com',0641090460,'2002-08-25','7110eda4d09e062aa5e4a390b0a572ac0d2c0220',0,"Je suis sympathique",'2020-10-05 08:46:00');

INSERT INTO universite(id_place,nom_place,desc_place) 
VALUES
(1,"Campus Dijon","Campus Dijon"),
(2,"7 Boulevard Dr Petitjean Dijon","Parking IUT"),
(3,"Rue Edgar Faure Dijon","Parking général");

INSERT INTO trajet(id_trajet,id_user,datetime_trajet,partir_ub,id_ville,adresse,statut_trajet,place_dispo,rayon_detour,com,id_place) 
VALUES
(1,1,'2020-10-28 18:00',1,7189,'38 route des rosiers',1,4,10,"Partir à Beaune",1 ),  
(2,1,'2020-11-21 08:30',0,4440,'11 route des papillons',1,4,10,"Revenir de Marseille !",1),
(3,1,'2020-11-21 14:15',1,4440,'14 impasse des tulipes',1,4,10,"Partir à Marseille!",1),
(4,1,'2020-10-31 17:30',1,7503,"199bis Avenue WallStreet",1,4,5,"Partir à Vernot",3 ),
(5,1,'2019-11-02 14:00',0,4441,'Route de Marseille',1,4,5,"Revenir de Rognes",2),
(6,1,'2021-11-02 11:45',0,22745,'Avenue des tortues',0,4,5,"Revenir de Lille",3),
(7,1,'2020-12-15 17:00',0,7107,"Place du vin d'honeur",0,4,5,"Revenir de Gevrey-Chambertin ",1),
(8,1,'2020-12-05 09:00',0,7151,"Place de l'arbre",0,2,5,"Revenir de Brochon",2),
(9,1,'2020-12-17 15:00',0,27304,'Centre Ville',0,4,5,"Revenir de Strasbourg",2),
(10,1,'2021-01-06 10:00',0,30438,'5 rue des tuiles',0,1,5,"Revenir de Paris",1),
(11,2,'2018-12-30 18:00',1,7189,'38 route des rosiers',1,3,10,"Partir à Beaune",1 ),
(12,2,'2020-11-21 9:40',0,4440,'11 route des papillons',1,3,2,"Revenir de Marseille !",1),
(13,2,'2020-11-21 14:15',1,4440,'14 impasse des tulipes',1,3,2,"Partir à Marseille!",1),
(14,2,'2020-10-31 17:30',1,7503,"199bis Avenue WallStreet",1,3,3,"Partir à Vernot",3 ),
(15,2,'2019-11-02 14:00',0,4441,'19 Place de Liberté',1,3,10,"Revenir de Rognes",2),
(16,2,'2021-11-02 11:45',0,22745,'Avenue des Camarades',0,3,5,"Revenir de Lille",3),
(17,2,'2020-12-16 17:00',0,7107,"Place du vin d'honeur",0,3,5,"Revenir de Gevrey-Chambertin",1),
(18,2,'2020-12-05 09:00',0,7108,"666 Impasse du Diable",0,2,5,"Revenir de Brochon!",2),
(19,2,'2020-12-17 15:00',0,27304,'Centre Ville',0,3,10,"On revient du marché de Starbourg!",2),
(20,2,'2021-01-06 10:00',0,30438,'14 Avenue des Retraités',0,1,3,"Revenir de Paris",1),
(21,1,'2021-01-06 9:00',0,30438,'14 Avenue des Retraités',0,1,3,"Revenir de Paris ",1),
(22,1,'2021-01-06 18:00',1,30438,'14 Avenue des Retraités',0,1,3,"Partir à Paris",1);

INSERT INTO participe(id_user,id_trajet,is_accepted,annulation_passager,com_passager) 
VALUES
(2,1,0,1,"C'est cool"),
(3,1,0,0,"Merci"),
(4,1,1,0,"Enchanté"),
(2,2,1,1,"Stylé"),
(3,2,0,0,"Je suis vraiment content"),
(4,2,0,1,"Noël, plus qu'une passion, une religion"),
(2,3,1,1,"Heyyyy"),
(4,3,0,1,"Salut"),
(4,3,0,0,"Bonjour"),
(4,18,0,0,"Ca va être cool"),
(4,15,0,1,"Voili Voilou"),
(4,14,1,0,"Voilà"),
(2,8,0,1,"C'est pas moi !"),
(2,8,0,0,"C'est moi !"),
(2,9,0,1,"Je suis cool"),
(2,7,1,1,"Ca à l'air marrant"),
(1,15,0,1,"Pourquoi pas"),
(1,14,0,1,"Peut-être"),
(1,14,0,0,"En fait si"),
(1,11,1,0,"Je suis un peu bavard"),
(1,12,0,0,"Salut à toi"),
(1,13,0,0,"Je sais pas quoi dire"),
(1,20,0,0,"Je dois prendre ce trajet!"),
(3,12,0,0,"Bonsoir oui"),
(3,5,0,0,"Bonsoir non"),
(3,21,0,0,"Il est vrai"),
(3,22,0,1,"J'aime rencontrer de nouvelles personnes"),
(3,22,0,0,"J'adore parler"),
(3,15,0,1,"J'ai pas de voiture");


INSERT INTO vehicule (id_vehicule,id_user,place,marque,model,commentaire) 
VALUES
(1,1,5,"Renault","Clio 4","Voiture propre d'interieur"),
(2,2,6,"Fiat","Multipla","Rien à signaler");







