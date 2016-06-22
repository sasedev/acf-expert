ALTER TABLE "acf_bifolders" ADD COLUMN "parent_id"                                                         UUID NULL;
ALTER TABLE "acf_bifolders" ADD COLUMN "pageurl_full"                                                      TEXT NULL;
ALTER TABLE "acf_bifolders" ADD CONSTRAINT "fk_acf_bifolders_parent" FOREIGN KEY ("parent_id") REFERENCES "acf_bifolders" ("id") ON UPDATE CASCADE ON DELETE SET NULL;
