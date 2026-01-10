insert into speciality (id, title)
values (1, 'Cardiology'),
       (2, 'Dermatology'),
       (3, 'Pediatrics');


insert into "user" (username, roles, password, name, dtype)
values ('dr.smith', '[ "ROLE_DOCTOR" ]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Dr John Smith', 'doctor'),
       ('dr.brown', '[ "ROLE_DOCTOR" ]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Dr Alice Brown', 'doctor'),
       ('patient.one', '[ "ROLE_PATIENT" ]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Omar Ali', 'patient'),
       ('patient.two', '[ "ROLE_PATIENT" ]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Sara Benali', 'patient'),
       ('admin', '[ "ROLE_ADMIN" ]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Admin', 'admin');


insert into doctor (id, speciality_id)
values (1, 1), -- Dr Smith → Cardiology
       (2, 2); -- Dr Brown → Dermatology


insert into patient (id, birth_date, address, gender)
values (3, '2000-05-12', 'Rabat', 'MALE'),
       (4, '1998-09-23', 'Casablanca', 'FEMALE');
