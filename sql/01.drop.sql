--DROP TRIGGER IF EXISTS "acf_autoincs_nextval" ON  IF EXISTS "acf_autoincs"; -- automatic drop with table "acf_autoincs"
DROP TABLE IF EXISTS "acf_bi_contents";
DROP TABLE IF EXISTS "acf_bi_titles";
DROP TABLE IF EXISTS "acf_bis";
DROP TABLE IF EXISTS "acf_goodlinks";
DROP TABLE IF EXISTS "acf_goodfiles";
DROP TABLE IF EXISTS "acf_feedreads";
DROP TABLE IF EXISTS "acf_transaction_docs";
DROP TABLE IF EXISTS "acf_mbalance_docs";
DROP TABLE IF EXISTS "acf_relation_docs";
DROP TABLE IF EXISTS "acf_account_docs";
DROP TABLE IF EXISTS "acf_groupsyst_docs";
DROP TABLE IF EXISTS "acf_groupperso_docs";
DROP TABLE IF EXISTS "acf_groupfiscal_docs";
DROP TABLE IF EXISTS "acf_groupcomptable_docs";
DROP TABLE IF EXISTS "acf_group_docs";
DROP TABLE IF EXISTS "acf_groupaudit_docs";
DROP TABLE IF EXISTS "acf_groupbank_docs";
DROP TABLE IF EXISTS "acf_company_docgroupsysts";
DROP TABLE IF EXISTS "acf_company_docgrouppersos";
DROP TABLE IF EXISTS "acf_company_docgroupfiscals";
DROP TABLE IF EXISTS "acf_company_docgroups";
DROP TABLE IF EXISTS "acf_company_docgroupcomptables";
DROP TABLE IF EXISTS "acf_company_docgroupbanks";
DROP TABLE IF EXISTS "acf_company_docgroupaudits";
DROP TABLE IF EXISTS "acf_company_docs";
DROP TABLE IF EXISTS "acf_transaction_vats";
DROP TABLE IF EXISTS "acf_transactions";
DROP TABLE IF EXISTS "acf_company_mbalances";
DROP TABLE IF EXISTS "acf_company_natures";
DROP TABLE IF EXISTS "acf_company_withholdings";
DROP TABLE IF EXISTS "acf_company_accounts";
DROP TABLE IF EXISTS "acf_relation_sectors";
DROP TABLE IF EXISTS "acf_company_relations";
DROP TABLE IF EXISTS "acf_company_labels";
DROP TABLE IF EXISTS "acf_company_phones";
DROP TABLE IF EXISTS "acf_company_addresses";
DROP TABLE IF EXISTS "acf_company_shareholders";
DROP TABLE IF EXISTS "acf_company_pilots";
DROP TABLE IF EXISTS "acf_company_frames";
DROP TABLE IF EXISTS "acf_company_users";
DROP TABLE IF EXISTS "acf_company_admins";
DROP TABLE IF EXISTS "acf_company_sectors";
DROP TABLE IF EXISTS "acf_companies";
DROP TABLE IF EXISTS "acf_cmp_sectors";
DROP TABLE IF EXISTS "acf_cmp_types";
DROP TABLE IF EXISTS "acf_jobs";
DROP TABLE IF EXISTS "acf_agenda_shares";
DROP TABLE IF EXISTS "acf_agenda_events";
DROP TABLE IF EXISTS "acf_notif_shares";
DROP TABLE IF EXISTS "acf_notifs";
DROP TABLE IF EXISTS "acf_traces";
DROP TABLE IF EXISTS "acf_users_roles";
DROP TABLE IF EXISTS "acf_users";
DROP TABLE IF EXISTS "acf_role_parents";
DROP TABLE IF EXISTS "acf_roles";
DROP TABLE IF EXISTS "acf_langs";
DROP TABLE IF EXISTS "acf_constant_floats";
DROP TABLE IF EXISTS "acf_constant_ints";
DROP TABLE IF EXISTS "acf_constant_strs";
DROP TABLE IF EXISTS "acf_autoincs";
DROP FUNCTION IF EXISTS autoinc_val();

