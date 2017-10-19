CREATE TABLE "acf_autoincs" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"                                                              TEXT NOT NULL,
	"val"                                                               INT8 NOT NULL DEFAULT 1,
	"cnt"                                                               INT8 NOT NULL DEFAULT 0,
	"description"                                                       TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_autoincs" PRIMARY KEY ("id")
);

CREATE OR REPLACE FUNCTION autoinc_val() RETURNS trigger AS $autoinc_val$
	BEGIN
		NEW.val := OLD.val + 1;
		RETURN NEW;
	END;
$autoinc_val$ LANGUAGE plpgsql;

CREATE TRIGGER "acf_autoincs_nextval"
	BEFORE UPDATE ON "acf_autoincs"
	FOR EACH ROW
	EXECUTE PROCEDURE autoinc_val();

CREATE TABLE "acf_constant_strs" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"                                                              TEXT NOT NULL,
	"val"                                                               TEXT NOT NULL,
	"description"                                                       TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_constant_strs" PRIMARY KEY ("id")
);

CREATE TABLE "acf_constant_ints" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"                                                              TEXT NOT NULL,
	"val"                                                               INT8 NOT NULL,
	"description"                                                       TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_constant_ints" PRIMARY KEY ("id")
);

CREATE TABLE "acf_constant_floats" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"                                                              TEXT NOT NULL,
	"val"                                                               FLOAT8 NOT NULL,
	"description"                                                       TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_constant_floats" PRIMARY KEY ("id")
);

CREATE TABLE "acf_langs" (
	"id"                                                                SERIAL NOT NULL,
	"locale"                                                            TEXT     NOT NULL,
	"status"                                                            INT4            NOT NULL DEFAULT 0,
	"direction"                                                         TEXT            NOT NULL DEFAULT 'ltr',
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_lang" PRIMARY KEY ("id")
);

CREATE TABLE "acf_roles" (
	"id"                                                                SERIAL8 NOT NULL,
	"name"                                                              TEXT NOT NULL,
	"description"                                                       TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_roles" PRIMARY KEY ("id")
);

CREATE TABLE "acf_role_parents" (
	"child"                                                             INT8 NOT NULL,
	"parent"                                                            INT8 NOT NULL,
	CONSTRAINT "pk_acf_role_parents" PRIMARY KEY ("child", "parent"),
	CONSTRAINT "fk_acf_role_parents_child" FOREIGN KEY ("child") REFERENCES "acf_roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_role_parents_parend" FOREIGN KEY ("parent") REFERENCES "acf_roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_users" (
	"id"                                                                SERIAL8 NOT NULL,
	"username"                                                          TEXT NOT NULL,
	"email"                                                             TEXT NULL,
	"clearpassword"                                                     TEXT NULL,
	"passwd"                                                            TEXT NULL,
	"salt"                                                              TEXT NULL,
	"recoverycode"                                                      TEXT NULL,
	"recoveryexpiration"                                                TIMESTAMP WITH TIME ZONE NULL,
	"lockout"                                                           INT8 NOT NULL DEFAULT 1,
    "lastvalidity"                                                      TIMESTAMP WITH TIME ZONE NULL,
	"logins"                                                            INT8 NOT NULL DEFAULT 0,
	"lastlogin"                                                         TIMESTAMP WITH TIME ZONE NULL,
	"lastactivity"                                                      TIMESTAMP WITH TIME ZONE NULL,
	"lastname"                                                          TEXT NULL,
	"firstname"                                                         TEXT NULL,
	"sexe"                                                              INT8 NULL,
	"birthday"                                                          DATE NULL,
	"strnum"                                                            TEXT NULL,
	"address"                                                           TEXT NULL,
	"address2"                                                          TEXT NULL,
	"town"                                                              TEXT NULL,
	"zipcode"                                                           TEXT NULL,
	"country"                                                           TEXT NULL,
	"phone"                                                             TEXT NULL,
	"mobile"                                                            TEXT NULL,
	"avatar"                                                            TEXT NULL,
	"lang_id"                                                           INT4 NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"cin"                                                               TEXT NULL,
	"passport"                                                          TEXT NULL,
	CONSTRAINT "pk_acf_users" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_users_lang" FOREIGN KEY ("lang_id") REFERENCES "acf_langs" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_users_roles" (
	"user_id"                                                           INT8 NOT NULL,
	"role_id"                                                           INT8 NOT NULL,
	CONSTRAINT "pk_acf_users_roles" PRIMARY KEY ("user_id", "role_id"),
	CONSTRAINT "fk_acf_users_roles_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_users_roles_role" FOREIGN KEY ("role_id") REFERENCES "acf_roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE "acf_traces" (
	"id"                                                                SERIAL8 NOT NULL,
	"msg"                                                               TEXT NULL,
	"action_type"                                                       INT8 NOT NULL DEFAULT 0,
	"action_entity"                                                     TEXT NOT NULL,
	"action_id"                                                         TEXT NULL,
	"user_id"                                                           TEXT NULL,
	"user_fullname"                                                     TEXT NULL,
	"user_type"                                                         INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"company_id"                                                        UUID NULL,
	"action_entity2"                                                    TEXT NULL,
	"action_id2"                                                        TEXT NULL,
	"action_entity3"                                                    TEXT NULL,
	"action_id3"                                                        TEXT NULL,
	"action_entity4"                                                    TEXT NULL,
	"action_id4"                                                        TEXT NULL,
	CONSTRAINT "pk_acf_traces" PRIMARY KEY ("id")
);

CREATE TABLE "acf_jobs" (
	"id"                                                                SERIAL8 NOT NULL,
	"label"                                                             TEXT NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_jobs" PRIMARY KEY ("id")
);

CREATE TABLE "acf_cmp_types" (
	"id"                                                                SERIAL NOT NULL,
	"label"                                                             TEXT NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_cmp_types" PRIMARY KEY ("id")
);

CREATE TABLE "acf_cmp_sectors" (
	"id"                                                                SERIAL8 NOT NULL,
	"label"                                                             TEXT NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_cmp_sectors" PRIMARY KEY ("id")
);

CREATE TABLE "acf_companies" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"corporate_name"                                                    TEXT NOT NULL,
	"type_id"                                                           INT4 NULL,
	"fisc"                                                              TEXT NULL,
	"cnss"                                                              TEXT NULL,
	"commercial_register"                                               TEXT NULL,
	"customs_code"                                                      TEXT NULL,
	"strnum"                                                            TEXT NULL,
	"address"                                                           TEXT NULL,
	"address2"                                                          TEXT NULL,
	"town"                                                              TEXT NULL,
	"zipcode"                                                           TEXT NULL,
	"country"                                                           TEXT NULL,
	"phone"                                                             TEXT NULL,
	"mobile"                                                            TEXT NULL,
	"fax"                                                               TEXT NULL,
	"email"                                                             TEXT NULL,
	"others"                                                            TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"cin"                                                               TEXT NULL,
	"passport"                                                          TEXT NULL,
	"physicaltype"                                                      INT4 NOT NULL DEFAULT 0,
	"actionvn"                                                          FLOAT8 NOT NULL DEFAULT 0,
	"tribunal"                                                          TEXT NULL,
	"bureaurc"                                                          TEXT NULL,
	"bureaucnss"                                                        TEXT NULL,
	"ref"                                                               TEXT NULL,
	CONSTRAINT "pk_acf_companies" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_companies_type" FOREIGN KEY ("type_id") REFERENCES "acf_cmp_types" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_stocks" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"year"                                                              INT4 NOT NULL,
	"val"                                                               FLOAT8 NOT NULL DEFAULT 0,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_stocks" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_stocks_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_sectors" (
	"sector_id"                                                         INT8 NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	CONSTRAINT "pk_acf_company_sectors" PRIMARY KEY ("sector_id", "company_id"),
	CONSTRAINT "fk_acf_company_sectors_sector" FOREIGN KEY ("sector_id") REFERENCES "acf_cmp_sectors" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_sectors_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_users" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"user_id"                                                           INT8 NOT NULL,
	"can_edit_companyinfos"                                             INT4 NOT NULL DEFAULT 1,
	"can_add_addresses"                                                 INT4 NOT NULL DEFAULT 1,
	"can_edit_addresses"                                                INT4 NOT NULL DEFAULT 1,
	"can_del_addresses"                                                 INT4 NOT NULL DEFAULT 1,
	"can_add_phones"                                                 INT4 NOT NULL DEFAULT 1,
	"can_edit_phones"                                                INT4 NOT NULL DEFAULT 1,
	"can_del_phones"                                                 INT4 NOT NULL DEFAULT 1,
	"can_add_frames"                                                    INT4 NOT NULL DEFAULT 1,
	"can_edit_frames"                                                   INT4 NOT NULL DEFAULT 1,
	"can_del_frames"                                                    INT4 NOT NULL DEFAULT 1,
	"can_add_docs"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_docs"                                                     INT4 NOT NULL DEFAULT 1,
	"can_del_docs"                                                      INT4 NOT NULL DEFAULT 1,
	"can_add_suppliers"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_suppliers"                                                     INT4 NOT NULL DEFAULT 1,
	"can_del_suppliers"                                                      INT4 NOT NULL DEFAULT 1,
	"can_add_customers"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_customers"                                                     INT4 NOT NULL DEFAULT 1,
	"can_del_customers"                                                      INT4 NOT NULL DEFAULT 1,
	"can_add_sales"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_sales"                                                     INT4 NOT NULL DEFAULT 1,
	"can_del_sales"                                                      INT4 NOT NULL DEFAULT 1,
	"can_add_buys"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_buys"                                                     INT4 NOT NULL DEFAULT 1,
	"can_del_buys"                                                      INT4 NOT NULL DEFAULT 1,
	"can_add_dgcomptables"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_dgcomptables"                                                     INT4 NOT NULL DEFAULT 1,
	"can_add_dgbanks"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_dgbanks"                                                     INT4 NOT NULL DEFAULT 1,
	"can_add_dgjuridics"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_dgjuridics"                                                     INT4 NOT NULL DEFAULT 1,
	"can_add_dgfiscals"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_dgfiscals"                                                     INT4 NOT NULL DEFAULT 1,
	"can_add_dgpersos"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_dgpersos"                                                     INT4 NOT NULL DEFAULT 1,
	"can_add_dgsysts"                                                      INT4 NOT NULL DEFAULT 1,
	"can_edit_dgsysts"                                                     INT4 NOT NULL DEFAULT 1,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_users" PRIMARY KEY ("id"),
	CONSTRAINT "uk_acf_company_users" UNIQUE ("user_id", "company_id"),
	CONSTRAINT "fk_acf_company_users_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_users_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE "acf_company_admins" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"user_id"                                                           INT8 NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_admins" PRIMARY KEY ("id"),
	CONSTRAINT "uk_acf_company_admins" UNIQUE ("user_id", "company_id"),
	CONSTRAINT "fk_acf_company_admins_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_admins_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE "acf_company_frames" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"job_id"                                                            INT8 NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	"lastname"                                                          TEXT NULL,
	"firstname"                                                         TEXT NULL,
	"sexe"                                                              INT8 NULL,
	"strnum"                                                            TEXT NULL,
	"address"                                                           TEXT NULL,
	"address2"                                                          TEXT NULL,
	"town"                                                              TEXT NULL,
	"zipcode"                                                           TEXT NULL,
	"country"                                                           TEXT NULL,
	"phone"                                                             TEXT NULL,
	"mobile"                                                            TEXT NULL,
	"email"                                                             TEXT NULL,
	"others"                                                            TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"cin"                                                               TEXT NULL,
	"passport"                                                          TEXT NULL,
	"fax"                                                               TEXT NULL,
	CONSTRAINT "pk_acf_company_frames" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_frames_user" FOREIGN KEY ("job_id") REFERENCES "acf_jobs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_frames_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_addresses" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NULL,
	"strnum"                                                            TEXT NULL,
	"address"                                                           TEXT NULL,
	"address2"                                                          TEXT NULL,
	"town"                                                              TEXT NULL,
	"zipcode"                                                           TEXT NULL,
	"country"                                                           TEXT NULL,
	"phone"                                                             TEXT NULL,
	"mobile"                                                            TEXT NULL,
	"fax"                                                               TEXT NULL,
	"email"                                                             TEXT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_addresses" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_addresses_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_phones" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NULL,
	"phone"                                                             TEXT NOT NULL,
	"contact"                                                           TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_phones" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_phones_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_labels" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"                                                              TEXT NOT NULL,
	"abreviation"                                                       TEXT NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_labels" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_labels_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_shareholders" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"name"                                                              TEXT NOT NULL,
	"trades"                                                            INT8 NOT NULL DEFAULT 1,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"cin"                                                               TEXT NULL,
	"quality"                                                           TEXT NULL,
	"address"                                                           TEXT NULL,
	CONSTRAINT "pk_acf_company_shareholders" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_shareholders_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_pilots" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"mission"                                                           TEXT NULL,
	"recfin"                                                            TEXT NULL,
	"nom_cac"                                                           TEXT NULL,
	"duree_mandat"                                                      TEXT NULL,
	"num_mandat"                                                        TEXT NULL,
	"rapport_cac"                                                       TEXT NULL,
	"decl_empl"                                                         TEXT NULL,
	"is_dur"                                                            TEXT NULL,
	"pv_ca"                                                             TEXT NULL,
	"pv_age"                                                            TEXT NULL,
	"livres_cotes"                                                      TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"pinance"                                                           TEXT NULL,
	"expirationance"                                                    TEXT NULL,
	"mpimpots"                                                          TEXT NULL,
	"idcnss"                                                            TEXT NULL,
	"mdpcnss"                                                           TEXT NULL,
	"ref"                                                               TEXT NULL,
	"nat_mission"                                                       TEXT NULL,
	"prestataire"                                                       TEXT NULL,
	"rapport_gerance"                                                   TEXT NULL,
	"pv_ago"                                                            TEXT NULL,
	"hon_theo_ann"                                                      FLOAT8 NULL,
	"mode_fact"                                                         TEXT NULL,
	"non_fact_m"                                                        FLOAT8 NULL,
	"nom_fact_d"                                                        TEXT NULL,
	"nom_enc_m"                                                         FLOAT8 NULL,
	"nom_enc_d"                                                         TEXT NULL,
	"comment_quit"                                                      TEXT NULL,
	"mq_quit_impots"                                                    TEXT NULL,
	"mq_quit_cnss"                                                      TEXT NULL,
	"commentaires"                                                      TEXT NULL,
	CONSTRAINT "pk_acf_company_pilots" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_pilots_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_relations" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"numb"                                                              INT8 NULL,
	"fisc"                                                              TEXT NULL,
	"commercial_register"                                               TEXT NULL,
	"strnum"                                                            TEXT NULL,
	"address"                                                           TEXT NULL,
	"address2"                                                          TEXT NULL,
	"town"                                                              TEXT NULL,
	"zipcode"                                                           TEXT NULL,
	"country"                                                           TEXT NULL,
	"phone"                                                             TEXT NULL,
	"mobile"                                                            TEXT NULL,
	"email"                                                             TEXT NULL,
	"others"                                                            TEXT NULL,
	"relationtype"                                                      TEXT NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"cin"                                                               TEXT NULL,
	"passport"                                                          TEXT NULL,
	"physicaltype"                                                      INT4 NOT NULL DEFAULT 0,
	"fax"                                                               TEXT NULL,
	CONSTRAINT "pk_acf_company_relations" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_relations_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_relation_sectors" (
	"sector_id"                                                         INT8 NOT NULL,
	"relation_id"                                                       UUID NOT NULL,
	CONSTRAINT "pk_acf_relation_sectors" PRIMARY KEY ("sector_id", "relation_id"),
	CONSTRAINT "fk_acf_relation_sectors_sector" FOREIGN KEY ("sector_id") REFERENCES "acf_cmp_sectors" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_relation_sectors_relation" FOREIGN KEY ("relation_id") REFERENCES "acf_company_relations" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_accounts" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"numb"                                                              INT8 NULL,
	"agency"                                                            TEXT NULL,
	"rib"                                                               TEXT NULL,
	"others"                                                            TEXT NULL,
	"accounttype"                                                       TEXT NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"contact"                                                           TEXT NULL,
	"tel"                                                               TEXT NULL,
	"fax"                                                               TEXT NULL,
	"email"                                                             TEXT NULL,
	CONSTRAINT "pk_acf_company_accounts" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_accounts_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_withholdings" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL DEFAULT 0,
	"numb"                                                              INT8 NULL,
	"value"                                                             FLOAT8 NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_withholdings" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_withholdings_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_natures" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"color"                                                             TEXT NOT NULL DEFAULT '97BBCD',
	CONSTRAINT "pk_acf_company_natures" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_natures_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_mbalances" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"mbalancetype"                                                      TEXT NOT NULL,
	"ref"                                                               TEXT NOT NULL,
	"cnt"                                                               INT8 NOT NULL DEFAULT 1,
	"year"                                                              INT4 NOT NULL DEFAULT 2000,
	"month"                                                             INT4 NOT NULL DEFAULT 1,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_mbalances" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_mbalances_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_transactions" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"numb"                                                              INT8 NULL,
	"label"                                                             TEXT NOT NULL,
	"bill"                                                              TEXT NULL,
	"dtactivation"                                                      DATE NULL,
	"dtpayment"                                                         DATE NULL,
	"vat"                                                               FLOAT8 NOT NULL DEFAULT 0,
	"stamp"                                                             FLOAT8 NOT NULL DEFAULT 0,
	"balance_ttc"                                                       FLOAT8 NOT NULL DEFAULT 0,
	"balance_net"                                                       FLOAT8 NOT NULL DEFAULT 0,
	"transactiontype"                                                   TEXT NOT NULL,
	"payment_type"                                                      INT4 NOT NULL DEFAULT 1,
	"transaction_status"                                                INT4 NOT NULL DEFAULT 1,
	"others"                                                            TEXT NULL,
	"mbalance_id"                                                       UUID NOT NULL,
	"relation_id"                                                       UUID NOT NULL,
	"withholding_id"                                                    UUID NOT NULL,
	"account_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"vatinfo"                                                           INT8 NOT NULL DEFAULT 0,
	"regime"                                                            INT8 NOT NULL DEFAULT 0,
	"devise"                                                            TEXT NOT NULL DEFAULT 'TND',
	"conversionrate"                                                    FLOAT8 NOT NULL DEFAULT 1,
	"vatd"                                                              FLOAT8 NOT NULL DEFAULT 0,
	"stampd"                                                            FLOAT8 NOT NULL DEFAULT 0,
	"balance_ttcd"                                                      FLOAT8 NOT NULL DEFAULT 0,
	"balance_netd"                                                      FLOAT8 NOT NULL DEFAULT 0,
	"nature_id"                                                         UUID NULL,
	"validated"                                                         INT4 NOT NULL DEFAULT 1,
	CONSTRAINT "pk_acf_transactions" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_transactions_mbalance" FOREIGN KEY ("mbalance_id") REFERENCES "acf_company_mbalances" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_transactions_relation" FOREIGN KEY ("relation_id") REFERENCES "acf_company_relations" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_transactions_withholding" FOREIGN KEY ("withholding_id") REFERENCES "acf_company_withholdings" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_transactions_nature" FOREIGN KEY ("nature_id") REFERENCES "acf_company_natures" ("id") ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "fk_acf_transactions_account" FOREIGN KEY ("account_id") REFERENCES "acf_company_accounts" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE "acf_transaction_vats" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"vatinfo"                                                           INT8 NOT NULL DEFAULT 0,
	"vat"                                                               FLOAT8 NOT NULL DEFAULT 0,
	"balance_ttc"                                                       FLOAT8 NOT NULL DEFAULT 0,
	"balance_net"                                                       FLOAT8 NOT NULL DEFAULT 0,
	"transaction_id"                                                    UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_transaction_vats" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_transaction_vats_transaction" FOREIGN KEY ("transaction_id") REFERENCES "acf_transactions" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_docs" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"filename"                                                          TEXT NOT NULL,
	"filesize"                                                          INT8 NOT NULL DEFAULT 0,
	"filemimetype"                                                      TEXT NOT NULL,
	"fileoname"                                                         TEXT NOT NULL,
	"filemd5"                                                           TEXT NOT NULL,
	"filedesc"                                                          TEXT NULL,
	"filedls"                                                           INT8 NOT NULL DEFAULT 0,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_company_docs" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docs_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_company_docgroups" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"parent_id"                                                         UUID NULL,
	"pageurl_full"                                                      TEXT NULL,
	CONSTRAINT "pk_acf_company_docgroups" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgroups_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgroups_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgroups" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_docgroupcomptables" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"parent_id"                                                         UUID NULL,
	"pageurl_full"                                                      TEXT NULL,
	CONSTRAINT "pk_acf_company_docgroupcomptables" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgroupcomptables_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgroupcomptables_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgroupcomptables" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_docgroupfiscals" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"parent_id"                                                         UUID NULL,
	"pageurl_full"                                                      TEXT NULL,
	CONSTRAINT "pk_acf_company_docgroupfiscals" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgroupfiscals_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgroupfiscals_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgroupfiscals" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_docgrouppersos" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"parent_id"                                                         UUID NULL,
	"pageurl_full"                                                      TEXT NULL,
	CONSTRAINT "pk_acf_company_docgrouppersos" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgrouppersos_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgrouppersos_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgrouppersos" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_docgroupsysts" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"parent_id"                                                         UUID NULL,
	"pageurl_full"                                                      TEXT NULL,
	CONSTRAINT "pk_acf_company_docgroupsysts" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgroupsysts_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgroupsysts_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgroupsysts" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_docgroupbanks" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"pageurl_full"                                                      TEXT NULL,
	"parent_id"                                                         UUID NULL,
	CONSTRAINT "pk_acf_company_docgroupbanks" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgroupbanks_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgroupbanks_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgroupbanks" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_company_docgroupaudits" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"label"                                                             TEXT NOT NULL,
	"others"                                                            TEXT NULL,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"pageurl_full"                                                      TEXT NULL,
	"parent_id"                                                         UUID NULL,
	CONSTRAINT "pk_acf_company_docgroupaudits" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_company_docgroupaudits_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_company_docgroupaudits_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_company_docgroupaudits" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_group_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_group_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_group_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_group_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgroups" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_groupcomptable_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_groupcomptable_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_groupcomptable_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_groupcomptable_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgroupcomptables" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_groupfiscal_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_groupfiscal_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_groupfiscal_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_groupfiscal_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgroupfiscals" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_groupperso_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_groupperso_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_groupperso_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_groupperso_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgrouppersos" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_groupsyst_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_groupsyst_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_groupsyst_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_groupsyst_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgroupsysts" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_groupbank_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_groupbank_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_groupbank_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_groupbank_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgroupbanks" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_groupaudit_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"group_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_groupaudit_docs" PRIMARY KEY ("doc_id", "group_id"),
	CONSTRAINT "fk_acf_groupaudit_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_groupaudit_docs_group" FOREIGN KEY ("group_id") REFERENCES "acf_company_docgroupaudits" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_account_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"account_id"                                                        UUID NOT NULL,
	CONSTRAINT "pk_acf_account_docs" PRIMARY KEY ("doc_id", "account_id"),
	CONSTRAINT "fk_acf_account_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_account_docs_account" FOREIGN KEY ("account_id") REFERENCES "acf_company_accounts" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_mbalance_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"mbalance_id"                                                       UUID NOT NULL,
	CONSTRAINT "pk_acf_mbalance_docs" PRIMARY KEY ("doc_id", "mbalance_id"),
	CONSTRAINT "fk_acf_mbalance_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_mbalance_docs_mbalance" FOREIGN KEY ("mbalance_id") REFERENCES "acf_company_mbalances" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_transaction_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"transaction_id"                                                    UUID NOT NULL,
	CONSTRAINT "pk_acf_transaction_docs" PRIMARY KEY ("doc_id", "transaction_id"),
	CONSTRAINT "fk_acf_transaction_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_transaction_docs_transaction" FOREIGN KEY ("transaction_id") REFERENCES "acf_transactions" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_relation_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"relation_id"                                                       UUID NOT NULL,
	CONSTRAINT "pk_acf_relation_docs" PRIMARY KEY ("doc_id", "relation_id"),
	CONSTRAINT "fk_acf_relation_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_relation_docs_relation" FOREIGN KEY ("relation_id") REFERENCES "acf_company_relations" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE "acf_agenda_events" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"user_id"                                                           INT8 NOT NULL,
	"evt_start"                                                         TIMESTAMP WITH TIME ZONE NOT NULL,
	"evt_end"                                                           TIMESTAMP WITH TIME ZONE NOT NULL,
	"evt_title"                                                         TEXT NULL,
	"evt_comments"                                                      TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_agenda_events" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_agenda_events_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE "acf_agenda_shares" (
	"user_id"                                                           INT8 NOT NULL,
	"evt_id"                                                            UUID NOT NULL,
	CONSTRAINT "pk_acf_agenda_shares" PRIMARY KEY ("user_id", "evt_id"),
	CONSTRAINT "fk_acf_agenda_shares_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_agenda_shares_event" FOREIGN KEY ("evt_id") REFERENCES "acf_agenda_events" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE "acf_notifs" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"user_id"                                                           INT8 NOT NULL,
	"notif_title"                                                       TEXT NULL,
	"notif_comments"                                                    TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_notifs" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_notifs_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_notif_shares" (
	"user_id"                                                           INT8 NOT NULL,
	"notif_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_notif_shares" PRIMARY KEY ("user_id", "notif_id"),
	CONSTRAINT "fk_acf_notif_shares_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_notif_shares_notif" FOREIGN KEY ("notif_id") REFERENCES "acf_notifs" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_feedreads" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"url"                                                               TEXT NOT NULL,
	"description"                                                       TEXT NULL,
	"nbrdays"                                                           INT8 NOT NULL DEFAULT 7,
	"nbritems"                                                          INT8 NOT NULL DEFAULT 3,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_feedreads" PRIMARY KEY ("id")
);

CREATE TABLE "acf_goodlinks" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"title"                                                             TEXT NOT NULL,
	"url"                                                               TEXT NOT NULL,
	"nbrclicks"                                                         INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_goodlinks" PRIMARY KEY ("id")
);

CREATE TABLE "acf_goodfiles" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"title"                                                             TEXT NOT NULL,
	"filename"                                                          TEXT NOT NULL,
	"filesize"                                                          INT8 NOT NULL DEFAULT 0,
	"filemimetype"                                                      TEXT NOT NULL,
	"fileoname"                                                         TEXT NOT NULL,
	"filemd5"                                                           TEXT NOT NULL,
	"filedesc"                                                          TEXT NULL,
	"filedls"                                                           INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_goodfiles" PRIMARY KEY ("id")
);


CREATE TABLE "acf_bis" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"bi_num"                                                            INT8 NOT NULL,
	"bi_description"                                                    TEXT NULL,
	"nbrclicks"                                                         INT8 NOT NULL DEFAULT 0,
	"dtstart"                                                           DATE NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_bis" PRIMARY KEY ("id"),
	CONSTRAINT "u_acf_bis" UNIQUE ("bi_num")
);

CREATE TABLE "acf_bi_titles" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"bt_content"                                                        TEXT NOT NULL,
	"bi_id"                                                             UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_bi_titles" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_bi_titles_bi" FOREIGN KEY ("bi_id") REFERENCES "acf_bis" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE "acf_bi_contents" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"bc_title"                                                          TEXT NOT NULL,
	"bc_content"                                                        TEXT NULL,
	"bc_theme"                                                          TEXT NULL,
	"bc_jort"                                                           TEXT NULL,
	"bc_txtnum"                                                         TEXT NULL,
	"bc_arttxt"                                                         TEXT NULL,
	"bc_dttxt"                                                          TEXT NULL,
	"bc_artcode"                                                        TEXT NULL,
	"bc_stetype"                                                        TEXT NULL,
	"bc_dtapp"                                                          TEXT NULL,
	"bt_id"                                                             UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_bi_contents" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_bi_contents_bt" FOREIGN KEY ("bt_id") REFERENCES "acf_bi_titles" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_bifolders" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"title"                                                             TEXT NOT NULL,
	"pageurl_full"                                                      TEXT NULL,
	"parent_id"                                                         UUID NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_bifolders" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_bifolders_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_bifolders" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_bifiles" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"title"                                                             TEXT NOT NULL,
	"filename"                                                          TEXT NOT NULL,
	"filesize"                                                          INT8 NOT NULL DEFAULT 0,
	"filemimetype"                                                      TEXT NOT NULL,
	"fileoname"                                                         TEXT NOT NULL,
	"filemd5"                                                           TEXT NOT NULL,
	"filedesc"                                                          TEXT NULL,
	"filedls"                                                           INT8 NOT NULL DEFAULT 0,
	"bif_id"                                                            UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_bifiles" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_bifolders_bif" FOREIGN KEY ("bif_id") REFERENCES "acf_bifolders" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_orders" (
    "id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
    "ref"                                                               TEXT NOT NULL,
    "user_id"                                                           INT8 NOT NULL,
    "description"                                                       TEXT NULL,
    "val"                                                               INT8 NOT NULL,
    "status"                                                            INT4 NOT NULL,
    "auth"                                                              TEXT NULL,
    "session_id"                                                        TEXT NULL,
    "ip_addr"                                                           TEXT NULL,
    "created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
    "updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
    CONSTRAINT "pk_acf_orders" PRIMARY KEY ("id"),
    CONSTRAINT "uk_acf_orders" UNIQUE ("ref"),
    CONSTRAINT "fk_acf_orders_user" FOREIGN KEY ("user_id") REFERENCES "acf_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_mpayes" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"ref"                                                               TEXT NOT NULL,
	"year"                                                              INT4 NOT NULL DEFAULT 2000,
	"month"                                                             INT4 NOT NULL DEFAULT 1,
	"company_id"                                                        UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_mpayes" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_mpayes_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_msalaries" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"ref"                                                               TEXT NOT NULL,
	"nom"                                                               TEXT NOT NULL,
	"prenom"                                                            TEXT NOT NULL,
	"active"                                                            TEXT NULL,
	"fonction"                                                          TEXT NULL,
	"regime"                                                          TEXT NULL,
	"dtstartcontrat"                                                    TEXT NULL,
	"dtendcontrat"                                                      TEXT NULL,
	"departement"                                                       TEXT NULL,
	"categorie"                                                         TEXT NULL,
	"echelon"                                                           TEXT NULL,
	"cin"                                                               TEXT NULL,
	"cnss"                                                              TEXT NULL,
	"birthday"                                                          TEXT NULL,
	"adresse"                                                           TEXT NULL,
	"tel"                                                               TEXT NULL,
	"mail"                                                              TEXT NULL,
	"banque"                                                            TEXT NULL,
	"rib"                                                               TEXT NULL,
	"chefdefamille"                                                     TEXT NULL,
	"situationfamiliale"                                                TEXT NULL,
	"enfanthandicap"                                                    TEXT NULL,
	"enfantsansbourse"                                                  TEXT NULL,
	"nbrjwork"                                                          TEXT NULL,
	"nbrjabsence"                                                       TEXT NULL,
	"nbrjconge"                                                         TEXT NULL,
	"nbrh075sup"                                                        TEXT NULL,
	"nbrh100sup"                                                        TEXT NULL,
	"nbrjsup"                                                           TEXT NULL,
	"remboursement"                                                     TEXT NULL,
	"achatste"                                                          TEXT NULL,
	"avancesalaire"                                                     TEXT NULL,
	"salairebrut"                                                       TEXT NULL,
	"salairenet"                                                        TEXT NULL,
	"avantagenature"                                                    TEXT NULL,
	"ticketresto"                                                       TEXT NULL,
	"ticketcadeau"                                                      TEXT NULL,
	"assurancevie"                                                      TEXT NULL,
	"comptecea"                                                         TEXT NULL,
	"remarques"                                                         TEXT NULL,
	"mpaye_id"                                                          UUID NOT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_msalaries" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_msalaries_mpaye" FOREIGN KEY ("mpaye_id") REFERENCES "acf_mpayes" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_mpaye_docs" (
	"doc_id"                                                            UUID NOT NULL,
	"mpaye_id"                                                          UUID NOT NULL,
	CONSTRAINT "pk_acf_mpaye_docs" PRIMARY KEY ("doc_id", "mpaye_id"),
	CONSTRAINT "fk_acf_mpaye_docs_doc" FOREIGN KEY ("doc_id") REFERENCES "acf_company_docs" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_mpaye_docs_mpaye" FOREIGN KEY ("mpaye_id") REFERENCES "acf_mpayes" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_online_products" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"prd_title"                                                         TEXT,
	"prd_description"                                                   TEXT,
	"prd_lockout"                                                       INT8 NOT NULL DEFAULT 1,
	"prd_label"                                                         TEXT NOT NULL,
	"prd_price_ht"                                                      FLOAT8 NOT NULL DEFAULT 0,
	"prd_vat"                                                           FLOAT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_products" PRIMARY KEY ("id")
);

CREATE TABLE "acf_online_taxes" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"tx_actif"                                                          INT8 NOT NULL DEFAULT 1,
	"tx_label"                                                          TEXT NOT NULL,
	"tx_val"                                                            FLOAT8 NOT NULL DEFAULT 0,
	"tx_type"                                                           INT8 NOT NULL DEFAULT 1,
	"tx_priority"                                                       INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_taxes" PRIMARY KEY ("id")
);

CREATE TABLE "acf_online_orders" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"ref"                                                               TEXT NOT NULL,
	"user_id"                                                           INT8 NOT NULL,
	"auth"                                                              TEXT NULL,
	"session_id"                                                        TEXT NULL,
	"ip_addr"                                                           TEXT NULL,
	"val"                                                               FLOAT8 NOT NULL DEFAULT 0,
	"orderto"                                                           TEXT NULL,
	"payment_type"                                                      INT8 NOT NULL DEFAULT 1,
	"payment_status"                                                    INT8 NOT NULL DEFAULT 1,
	"autorenew"                                                         INT8 NOT NULL DEFAULT 1,
    "acf_online_orders"                                                 UUID NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_orders" PRIMARY KEY ("id"),
    CONSTRAINT "fk_acf_online_orders_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_online_invoices" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"ord_id"                                                            UUID NOT NULL,
	"company_id"                                                        UUID NULL,
	"ref"                                                               TEXT NOT NULL,
	"user_id"                                                           INT8 NOT NULL,
	"val"                                                               FLOAT8 NOT NULL DEFAULT 0,
	"orderto"                                                           TEXT NULL,
	"payment_type"                                                      INT8 NOT NULL DEFAULT 1,
	"payment_status"                                                    INT8 NOT NULL DEFAULT 1,
	"autorenew"                                                         INT8 NOT NULL DEFAULT 1,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_invoices" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_online_invoices_order" FOREIGN KEY ("ord_id") REFERENCES "acf_online_orders" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_online_invoices_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_online_order_elements" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"ord_id"                                                            UUID NOT NULL,
	"prd_id"                                                            UUID NULL,
	"prd_label"                                                         TEXT NOT NULL,
	"prd_price_ht"                                                      FLOAT8 NOT NULL DEFAULT 0,
	"prd_vat"                                                           FLOAT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_order_elements" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_online_order_elements_order" FOREIGN KEY ("ord_id") REFERENCES "acf_online_orders" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_acf_online_order_elements_product" FOREIGN KEY ("prd_id") REFERENCES "acf_online_products" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_online_order_taxes" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"ord_id"                                                            UUID NOT NULL,
	"tx_label"                                                          TEXT NOT NULL,
	"tx_val"                                                            FLOAT8 NOT NULL DEFAULT 0,
	"tx_type"                                                           INT8 NOT NULL DEFAULT 1,
	"tx_priority"                                                       INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_order_taxes" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_online_order_taxes_order" FOREIGN KEY ("ord_id") REFERENCES "acf_online_orders" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_online_invoice_elements" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"inv_id"                                                            UUID NOT NULL,
	"prd_label"                                                         TEXT NOT NULL,
	"prd_price_ht"                                                      FLOAT8 NOT NULL DEFAULT 0,
	"prd_vat"                                                           FLOAT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_invoice_elements" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_online_invoice_elements_invoice" FOREIGN KEY ("inv_id") REFERENCES "acf_online_invoices" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_online_invoice_taxes" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"inv_id"                                                            UUID NOT NULL,
	"tx_label"                                                          TEXT NOT NULL,
	"tx_val"                                                            FLOAT8 NOT NULL DEFAULT 0,
	"tx_type"                                                           INT8 NOT NULL DEFAULT 1,
	"tx_priority"                                                       INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_invoice_taxes" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_online_invoice_taxes_invoice" FOREIGN KEY ("inv_id") REFERENCES "acf_online_invoices" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "acf_online_invoice_docs" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"inv_id"                                                            UUID NOT NULL,
	"filename"                                                          TEXT NOT NULL,
	"filesize"                                                          INT8 NOT NULL DEFAULT 0,
	"filemimetype"                                                      TEXT NOT NULL,
	"fileoname"                                                         TEXT NOT NULL,
	"filemd5"                                                           TEXT NOT NULL,
	"visible"                                                           INT8 NOT NULL DEFAULT 1,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_acf_online_invoice_docs" PRIMARY KEY ("id"),
	CONSTRAINT "fk_acf_online_invoice_docs_invoice" FOREIGN KEY ("inv_id") REFERENCES "acf_online_invoices" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE TABLE "acf_liassefolders" (
    "id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
    "title"                                                             TEXT NOT NULL,
    "company_id"                                                        UUID NOT NULL,
    "created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
    "updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
    "pageurl_full"                                                      TEXT NULL,
    "parent_id"                                                         UUID NULL,
    CONSTRAINT "pk_acf_liassefolders" PRIMARY KEY ("id"),
    CONSTRAINT "fk_acf_liassefolders_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT "fk_acf_liassefolders_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_liassefolders" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "acf_liassefiles" (
    "id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
    "title"                                                             TEXT NOT NULL,
    "filename"                                                          TEXT NOT NULL,
    "filesize"                                                          INT8 NOT NULL DEFAULT 0,
    "filemimetype"                                                      TEXT NOT NULL,
    "fileoname"                                                         TEXT NOT NULL,
    "filemd5"                                                           TEXT NOT NULL,
    "filedesc"                                                          TEXT NULL,
    "filedls"                                                           INT8 NOT NULL DEFAULT 0,
    "liassef_id"                                                            UUID NOT NULL,
    "created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
    "updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
    CONSTRAINT "pk_acf_liassefiles" PRIMARY KEY ("id"),
    CONSTRAINT "fk_acf_liassefolders_liassef" FOREIGN KEY ("liassef_id") REFERENCES "acf_liassefolders" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);


