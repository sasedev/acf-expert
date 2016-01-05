INSERT INTO "acf_langs" ("locale", "status", "direction", "created_at", "updated_at") VALUES
('fr', 1, 'rtl', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01');

INSERT INTO "acf_roles" ("name", "description", "created_at", "updated_at") VALUES
('ROLE_USER', '<p>Simple User</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01'),
('ROLE_CLIENT1', '<p>Client (ACF-Cloud)</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01'),
('ROLE_CLIENT2', '<p>Client (ACF-Info)</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01'),
('ROLE_CLIENT3', '<p>Client (ACF-Payroll)</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01'),
('ROLE_ADMIN', '<p>Administrateur</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01'),
('ROLE_SUPERADMIN', '<p>SuperAdminsitrateur</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01'),
('ROLE_SUPERSUPERADMIN', '<p>SuperAdminsitrateur</p>', '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01');

INSERT INTO "acf_role_parents" ("child", "parent") VALUES
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 5),
(7, 6);

INSERT INTO "acf_users" ("username", "email", "clearpassword", "passwd", "salt", "lockout", "logins", "lastname", "firstname", "sexe", "country", "phone", "mobile", "avatar", "lang_id", "created_at", "updated_at") VALUES
('demo', 'seif.salah@gmail.com', 'demodemo', '/ugR2FiHKUyQdT1mbQRYXjbTWLbhmhUjjR8wTzX5q5Iq9Wi/5Japfw==', 'd373ec2ae8890256bb2471580087d373', 1, 0, 'Salah', 'Abdelkader Seifeddine', 3, 'CH', null, null, null, 1, '2015-05-25 08:00:00+01', '2015-05-25 08:00:00+01');


INSERT INTO "acf_users_roles" ("user_id", "role_id") VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 7);
