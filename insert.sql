insert into speciality (id, title)
values (1, 'Cardiology'),
       (2, 'Dermatology'),
       (3, 'Pediatrics');



insert into "user" (id, username, roles, password, name, dtype)
values (1, 'dr.smith', '["ROLE_DOCTOR"]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Dr John Smith', 'doctor'),
       (2, 'dr.brown', '["ROLE_DOCTOR"]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Dr Alice Brown', 'doctor'),
       (3, 'patient.one', '["ROLE_PATIENT"]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Omar Ali', 'patient'),
       (4, 'patient.two', '["ROLE_PATIENT"]', '$2y$13$An2.8XQj07A0NU1jh99LOeQa/X.JPCFI2gbFSobx9qF3kHecJWWby', 'Sara Benali', 'patient');


insert into doctor (id, speciality_id)
values (1, 1), -- Dr Smith → Cardiology
       (2, 2); -- Dr Brown → Dermatology


insert into patient (id, birth_date, address, gender)
values (3, '2000-05-12', 'Rabat', 'MALE'),
       (4, '1998-09-23', 'Casablanca', 'FEMALE');


insert into appointment (time, speciality_id, doctor_id, patient_id)
values ('2025-01-10', 1, 1, 3),
       ('2025-01-11', 2, 2, 4),
       ('2025-01-12', 1, 1, 4);
