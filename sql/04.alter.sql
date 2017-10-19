ALTER TABLE "acf_bifolders" ADD COLUMN "parent_id"                                                         UUID NULL;
ALTER TABLE "acf_bifolders" ADD COLUMN "pageurl_full"                                                      TEXT NULL;
ALTER TABLE "acf_bifolders" ADD CONSTRAINT "fk_acf_bifolders_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_bifolders" ("id") ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE "acf_users" ADD COLUMN "lastvalidity"                                                         TIMESTAMP WITH TIME ZONE NULL;
ALTER TABLE "acf_online_orders" ADD COLUMN "company_id"                                                   UUID NULL;
ALTER TABLE "acf_online_orders" ADD CONSTRAINT "fk_acf_online_orders_company" FOREIGN KEY ("company_id") REFERENCES "acf_companies" ("id") ON UPDATE CASCADE ON DELETE SET NULL;
