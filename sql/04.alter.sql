ALTER TABLE "acf_bifolders" ADD COLUMN "parent_id"                                                         UUID NULL;
ALTER TABLE "acf_bifolders" ADD COLUMN "pageurl_full"                                                      TEXT NULL;
ALTER TABLE "acf_bifolders" ADD CONSTRAINT "fk_acf_bifolders_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_bifolders" ("id") ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE "acf_users" ADD COLUMN "lastvalidity"                                                         TIMESTAMP WITH TIME ZONE NULL;
ALTER TABLE "acf_online_orders" ADD COLUMN "company_id"                                                   UUID NULL;
ALTER TABLE "acf_online_orders" ADD CONSTRAINT "fk_acf_online_orders_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE "acf_companies" ADD COLUMN "monthdocslimit"                                                  INT8 NOT NULL DEFAULT 100;
ALTER TABLE "acf_companies" ADD COLUMN "curmonth"                                                  INT4 NOT NULL DEFAULT 1;
ALTER TABLE "acf_companies" ADD COLUMN "curmonthdocs"                                                  INT8 NOT NULL DEFAULT 0;
ALTER TABLE "ao_subcategs" ADD COLUMN "priority"                                                  INT8 NOT NULL DEFAULT 100;

ALTER TABLE "ao_advertisements" RENAME TO "ao_callfortenders";
ALTER TABLE "ao_callfortenders" RENAME CONSTRAINT "pk_ao_advertisements" TO "pk_ao_callfortenders";
ALTER TABLE "ao_callfortenders" RENAME CONSTRAINT "fk_ao_subcategs_categ" TO "fk_ao_callfortenders_categ";
ALTER TABLE "ao_callfortenders" DROP COLUMN "grp";
ALTER TABLE "ao_callfortenders" ALTER COLUMN "dtpub" DROP NOT NULL;
ALTER TABLE "ao_callfortenders" ALTER COLUMN "dtend" DROP NOT NULL;
ALTER TABLE "ao_callfortenders" ALTER COLUMN "dtend" DROP NOT NULL;
