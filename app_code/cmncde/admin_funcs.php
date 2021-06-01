<?php
$allMdlslovID = getLovID("All Enabled Modules");
$dfltPrvldgs = array(
    /* 0 */
    "View System Administration", "View Users & their Roles",
    /* 2 */ "View Roles & their Priviledges", "View Registered Modules & their Priviledges",
    /* 4 */ "View Security Policies", "View Server Settings", "View User Logins",
    /* 7 */ "View Audit Trail Tables", "Add New Users & their Roles", "Edit Users & their Roles",
    /* 10 */ "Add New Roles & their Priviledges", "Edit Roles & their Priviledges",
    /* 12 */ "Add New Security Policies", "Edit Security Policies", "Add New Server Settings",
    /* 15 */ "Edit Server Settings", "Set manual password for users",
    /* 17 */ "Send System Generated Passwords to User Mails",
    /* 18 */ "View SQL", "View Record History", "Add/Edit Extra Info Labels", "Delete Extra Info Labels",
    /* 22 */ "Add Notices", "Edit Notices", "Delete Notices", "View Notices Admin"
);

$sysLovs = array(
    /* 0 */
    "Benefits Types", "Relationship Types"
    /* 2 */, "Person Types-Further Details", "Countries", "Currencies", "Organisation Types"
    /* 6 */, "Divisions or Group Types", "Person Type Change Reasons", "Person Types"
    /* 9 */, "Qualification Types", "National ID Types", "Pay Frequencies",
    /* 12 */ "Benefits & Dues/Contributions Value Types", "Extra Information Labels",
    /* 14 */ "Divisions Images Directory", "Organization Images Directory", "Person Images Directory"
    /* 17 */, "Organisations", "Divisions/Groups", "Jobs", "Chart of Accounts",
    /* 21 */ "Transaction Accounts", "Parent Accounts", "Active Users", "Person Titles",
    /* 25 */ "Gender", "Marital Status", "Nationalities", "Active Persons", "Sites/Locations",
    /* 30 */ "Grades", "Positions", "Asset Accounts", "Expense Accounts", "Revenue Accounts",
    /* 35 */ "Liability Accounts", "Equity Accounts", "Pay Items", "Pay Item Values",
    /* 39 */ "Working Hours", "Gathering Types", "Organisational Pay Scale",
    /* 42 */ "Transactions Date Limit 1", "Transactions Date Limit 2",
    /* 44 */ "Budget Accounts", "Banks", "Bank Branches", "Bank Account Types", "Balance Items",
    /* 49 */ "Non-Balance Items", "Person Sets for Payments", "Item Sets for Payments",
    /* 52 */ "Audit Logs Directory", /* 53 */ "Reports Directory", "System Modules",
    /* 55 */ "LOV Names", "User Roles", "Pay Item Classifications", "System Priviledges",
    /* 59 */ "Payment Means", "Allowed IP Address for Request Listener",
    /* 61 */ "CV Courses", "Schools/Academic Institutions", "Other Locations",
    /* 64 */ "Jobs/Professions/Occupations", "Certificate Names", "Languages",
    /* 67 */ "Hobbies", "Interests", "Conduct", "Attitudes",
    /* 71 */ "Companies/Work Places", "Customized Module Names", "Allowed Person Types for Roles",
    /* 74 */ "Document Signatory Columns", "Attachment Document Categories",
    /* 76 */ "Types of Incorporation", "List of Professional Services", "Grade Names", "Schools/Organisations/Institutions",
    /* 80 */ "Account Classifications", "Employer's File No.", "Person's Email Addresses",
    /* 83 */ "SMS API Parameters", "Universal Resource Locators (URLs)", "Asset Register",
    /* 86 */ "Audit Trail Trackable Actions", "Site Types", "Vault Item States", "All Enabled Modules",
    /* 90 */ "All Other General Setups", "All Person IDs",
    /* 92 */ "Asset and Expenditure Accounts", "Revenue and Liability Accounts",
    /* 94 */ "All Other Hospitality Setups", "Default Mail Recipients"
);

$sysLovsDesc = array(
    /* 0 */
    "Benefits Types", "Relationship Types"
    /* 2 */, "Further Details about the available person types", "Countries", "Currencies",
    /* 5 */ "Organisation Types", "Divisions or Group Types", "Person Type Change Reasons", "Person Types"
    /* 9 */, "Qualification Types", "National ID Types", "Pay Frequencies",
    /* 12 */ "Benefits & Dues/Contributions Value Types", "Extra Information Labels",
    /* 14 */ "Directory for keeping images from the div_groups_table",
    /* 15 */ "Directory for keeping images coming from the org_details_table",
    /* 16 */ "Directory for Storing Person's Images",
    /* 17 */ "List of all organizations stored in the system",
    /* 18 */ "List of all divisions/groups stored in the system",
    /* 19 */ "List of all Jobs stored in the system",
    /* 20 */ "List of all Accounts stored in the system",
    /* 21 */ "List of all accounts transactions can be posted into",
    /* 22 */ "List of all Parent Accounts in the system",
    /* 23 */ "List of all users in the system",
    /* 24 */ "Name Titles of Organization Persons", "Gender",
    /* 26 */ "Marital Status", "Nationalities", "Active Persons",
    /* 29 */ "List of all Sites/Locations", "List of all Grades",
    /* 31 */ "List of all Positions", "Asset Accounts", "Expense Accounts",
    /* 34 */ "Revenue Accounts", "Liability Accounts", "Equity Accounts",
    /* 37 */ "Pay Items", "Pay Item Values", "Working Hours", "Gathering Types",
    /* 41 */ "Organisational Pay Scale", "Transactions Date Limit 1",
    /* 43 */ "Transactions Date Limit 2", "Budget Accounts", "Banks",
    /* 46 */ "Bank Branches", "Bank Account Types", "Balance Items",
    /* 49 */ "Non-Balance Items", "Person Sets for Payments",
    /* 51 */ "Item Sets for Payments",
    /* 52 */ "Audit Logs Directory", "Reports Directory", "System Modules", "LOV Names", "User Roles",
    /* 57 */ "Pay Item Classifications", "System Priviledges", "Various Payment Means", "Allowed IP Address for Request Listener",
    /* 61 */ "CV Courses", "Schools/Academic Institutions", "Other Locations",
    /* 64 */ "Jobs/Professions/Occupations", "Certificate Names", "Languages",
    /* 67 */ "Hobbies", "Interests", "Conduct", "Attitudes",
    /* 71 */ "Companies/Work Places", "Customized Module Names", "Allowed Person Types for Roles",
    /* 74 */ "Document Signatory Columns", "Attachment Document Categories",
    /* 76 */ "Types of Incorporation", "List of Professional Services", "Grade Names", "Schools/Organisations/Institutions",
    /* 80 */ "Account Classifications", "Employer's File No.", "Person's Email Addresses",
    /* 83 */ "SMS API Parameters", "Universal Resource Locators (URLs)", "Asset Register",
    /* 86 */ "Audit Trail Trackable Actions", "Site Types", "Vault Item States",
    /* 89 */ "All Enabled Modules", "All Other General Setups", "All Person IDs",
    /* 92 */ "Asset and Expenditure Accounts", "Revenue and Liability Accounts",
    /* 94 */ "All Other Hospitality Setups", "Default Mail Recipients"
);
$sysLovsDynQrys = array(
    /* 0 */
    "", ""
    /* 2 */, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
    /* 17 */ "select distinct trim(to_char(org_id,'999999999999999999999999999999')) a, org_name b, '' c from org.org_details order by 2",
    /* 18 */ "select distinct ''||div_id a, div_code_name b, '' c, org_id d, gst.get_pssbl_val(div_typ_id) e from org.org_divs_groups order by 2",
    /* 19 */ "select distinct trim(to_char(job_id,'999999999999999999999999999999')) a, job_code_name b, '' c, org_id d from org.org_jobs order by 2",
    /* 20 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0 order by accnt_num",
    /* 21 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, (CASE WHEN prnt_accnt_id>0 THEN accnt_num || '.' || accnt_name || ' ('|| accb.get_accnt_num(prnt_accnt_id)||'.'||accb.get_accnt_name(prnt_accnt_id)|| ')' WHEN control_account_id>0 THEN accnt_num || '.' || accnt_name || ' ('|| accb.get_accnt_num(control_account_id)||'.'||accb.get_accnt_name(control_account_id)|| ')' ELSE accnt_num || '.' || accnt_name END) b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (is_prnt_accnt = '0' and is_enabled = '1' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 22 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_type e, accnt_num f from accb.accb_chart_of_accnts where (is_prnt_accnt = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 23 */ "select distinct trim(to_char(user_id,'999999999999999999999999999999')) a, user_name b, '' c FROM sec.sec_users WHERE (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')) order by 1",
    "", "", "",
    /* 27 */ "",
    /* 28 */ "SELECT distinct local_id_no a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, org_id d FROM prs.prsn_names_nos a order by local_id_no DESC",
    /* 29 */ "select distinct trim(to_char(location_id,'999999999999999999999999999999')) a, REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'') b, '' c, org_id d from org.org_sites_locations order by 2",
    /* 30 */ "select distinct trim(to_char(grade_id,'999999999999999999999999999999')) a, grade_code_name b, '' c, org_id d from org.org_grades order by 2",
    /* 31 */ "select distinct trim(to_char(position_id,'999999999999999999999999999999')) a, position_code_name b, '' c, org_id d from org.org_positions order by 2",
    /* 32 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'A' and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 33 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EX' and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 34 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'R' and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 35 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'L' and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 36 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EQ' and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 37 */ "select distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_code_name b, '' c, org_id d from org.org_pay_items order by 2",
    /* 38 */ "select distinct trim(to_char(pssbl_value_id,'999999999999999999999999999999')) a, pssbl_value_code_name b, '' c, item_id d from org.org_pay_items_values order by 2",
    /* 39 */ "select distinct trim(to_char(work_hours_id,'999999999999999999999999999999')) a, work_hours_name b, '' c, org_id d from org.org_wrkn_hrs order by 2",
    /* 40 */ "select distinct trim(to_char(gthrng_typ_id,'999999999999999999999999999999')) a, gthrng_typ_name b, '' c, org_id d from org.org_gthrng_types order by 2",
    /* 41 */ "", "", "",
    /* 44 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0 and is_prnt_accnt = '0' and is_enabled = '1' and has_sub_ledgers = '0') order by accnt_num",
    /* 45 */ "", "", "",
    /* 48 */ "select distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_code_name b, '' c, org_id d from org.org_pay_items where item_maj_type = 'Balance Item' order by item_code_name",
    /* 49 */ "select distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_code_name b, '' c, org_id d, pay_run_priority e from org.org_pay_items where item_maj_type = 'Pay Value Item' order by pay_run_priority",
    /* 50 */ "select distinct trim(to_char(prsn_set_hdr_id,'999999999999999999999999999999')) a, prsn_set_hdr_name b, '' c, org_id d from pay.pay_prsn_sets_hdr order by prsn_set_hdr_name",
    /* 51 */ "select distinct trim(to_char(hdr_id,'999999999999999999999999999999')) a, itm_set_name b, '' c, org_id d from pay.pay_itm_sets_hdr order by itm_set_name"
    /* 52 */, "", "", "select distinct trim(to_char(module_id,'999999999999999999999999999999')) a, module_name b, '' c from sec.sec_modules order by module_name"
    /* 55 */, "select distinct trim(to_char(value_list_id,'999999999999999999999999999999')) a, value_list_name b, '' c from gst.gen_stp_lov_names order by value_list_name"
    /* 56 */, "select distinct trim(to_char(role_id,'999999999999999999999999999999')) a, role_name b, '' c from sec.sec_roles order by role_name",
    /* 57 */ "",
    /* 58 */ "select distinct trim(to_char(prvldg_id,'999999999999999999999999999999')) a, prvldg_name || ' (' || sec.get_module_nm(module_id) || ')' b, '' c, prvldg_id d from sec.sec_prvldgs order by prvldg_id",
    /* 59 */ "", "",
    /* 61 */ "", "", "",
    /* 64 */ "", "", "",
    /* 67 */ "", "", "", "",
    /* 71 */ "", "", "", "", "", "", "",
    /* 78 */ "select distinct grade_code_name a, grade_code_name b, '' c, org_id d from org.org_grades order by 1",
    /* 79 */ "",
    /* 80 */ "", "",
    /* 82 */ "SELECT distinct REPLACE(email,',',';') a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names || ' ('||local_id_no||')') b, '' c, org_id d, local_id_no e FROM prs.prsn_names_nos a order by local_id_no",
    /* 83 */ "", "",
    /* 85 */ "Select '' || asset_id a, asset_code_name || ':' || asset_desc || ':' || asset_classification || ':' ||asset_category || ':' || tag_number b, '' c, org_id d from accb.accb_fa_assets_rgstr order by 2",
    /* 86 */ "", "", "", "", "",
    /* 91 */ "SELECT distinct ''||person_id a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names || ' (' || local_id_no || ')') b, '' c, org_id d, local_id_no e FROM prs.prsn_names_nos a order by local_id_no DESC",
    /* 92 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type IN ('A','EX') and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 93 */ "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type IN ('R','L') and is_prnt_accnt = '0' and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
    /* 94 */ "", ""
);

$pssblVals = array(
    "0", "Loans", "Money amounts granted to staff to be paid later", "0", "Allowances", "Money amounts granted to staff", "0", "Leave", "Vacation Days allowed for employees", "1", "Father", "Biological Male Parent", "1", "Mother", "Biological Female Parent", "1", "Spouse", "Husband or Wife", "1", "Ex-Spouse", "Former Husband or wife", "1", "Son", "Biological Male Child", "1", "Daughter", "Biological Female Child", "1", "Uncle", "Uncle", "1", "Aunt", "Aunt", "1", "Nephew", "Nephew", "1", "Niece", "Niece", "1", "In-Law", "In-Law", "1", "Cousin", "Cousin", "1", "Friend", "Friend", "1", "Guardian", "Guardian", "1", "Grand-Father", "Grand-Father", "1", "Grand-Mother", "Grand-Mother", "1", "Step-Father", "Step-Father", "1", "Step-Mother", "Step-Mother", "1", "Step-Son", "Step-Son", "1", "Step-Daughter", "Step-Daughter", "2", "Permanent-Full Time", "Full Time permanent staff", "2", "Permanent-Part Time", "Part Time permanent staff", "2", "Contract-Full Time", "Full Time contract staff", "2", "Contract-Part Time", "Part Time contract staff", "3", "Ghana", "GH", "3", "South Africa", "SA", "3", "United States of America", "USA", "3", "United Kingdom", "UK", "4", "GHS", "Ghana Cedis ₵", "4", "JPY", "Japanese Yen ¥", "4", "USD", "US Dollars $", "4", "GBP", "British Pound £", "5", "School", "Place of tution and learning", "5", "Hotel", "Place where rooms are hired out to the public", "5", "Church", "Place of Worship", "5", "NGO", "Non-Governmental Organization", "5", "Company", "Company", "5", "Super Market", "Super Market", "5", "Mini Mart", "Mini Mart", "5", "Shop/Store", "Shop/Store", "5", "Boutique", "Boutique", "6", "Office", "Major Division under Department", "6", "Unit", "Division under Office", "6", "Department", "Major Division in an Organization", "6", "Wing", "Typically in churchs", "6", "Club", "Association", "6", "Association", "Welfare Group", "6", "Religious Denomination", "Religious group", "6", "Team", "Group for competitions", "6", "Shareholders", "Group for Shareholders", "6", "Board of Directors", "Group for Board of Directors", "6", "Pay/Remuneration", "Group for Workers' Salaries/Wages", "6", "Top Management", "Group for Top Management", "6", "Access Control Group", "Access Control Group", "6", "Class", "Class", "6", "Course Group", "Course Group", "6", "Academic Level", "Academic Level", "7", "New Shareholder", "New Shareholder", "7", "Starting Director/Shareholder", "Starting Director/Shareholder", "7", "New Recruitment", "New staff", "7", "Re-Employment", "Old staff coming back", "7", "New Enrolment", "New Member", "7", "Re-Enrolment", "Old Member coming back", "7", "End of Contract", "Contract has ended duely", "7", "Appointment as Board Member", "Appointment as Board Member", "7", "Termination of Appointment", "Appointment Terminated", "7", "Dismissal", "Sacked", "7", "Compulsory Retirement", "Reached age Limit", "7", "Voluntary Retirement", "Decided to retire early", "7", "Retirement on Medical Grounds", "Retiring due to Ailment", "7", "Change of Membership Terms", "Change of Membership Terms", "7", "Change of Employement Terms", "Change of Employement Terms", "8", "Shareholder", "Owner of Shares in the Company", "8", "Board Member", "Member of Board of Directors", "8", "Contact Person", "Relative or Friend", "8", "Ex-Contact Person", "Former Relative or Friend", "8", "Customer", "Client", "8", "Ex-Customer", "Former Client", "8", "Supplier", "Supplier of goods and services", "8", "Ex-Supplier", "Former Supplier of goods and services", "8", "Ex-Customer", "Former Client", "8", "Student", "Currently a Student", "8", "Old Student", "Former Student", "8", "Employee", "Currently a worker", "8", "Ex-Employee", "Former Worker", "8", "Member", "Currently a Member of the group", "8", "Ex-Member", "A Former Member of the group", "9", "1st Degree", "First Degree University", "9", "2nd Degree", "Second Degree University", "9", "Form 5", "Senior Secondary School Cert.(O-Level)", "9", "Sixth Form", "Senior Secondary School Cert.(A-Level)", "9", "Senior High", "Senior High School Cert.(WASSCE)", "9", "Junior High", "Junior High School Cert.(BECE)", "9", "Phd", "Doctor of Philosophy", "10", "NHIS ID", "Health Insurance", "10", "Voter's ID", "Voter's ID Card", "10", "Driving License", "Driver's License", "10", "Passport", "Passport", "10", "SSNIT", "SSNIT", "11", "fixed", "for payments at end of contracts", "11", "hourly", "hourly", "11", "daily", "daily", "11", "weekly", "weekly", "11", "fortnightly", "fortnightly", "11", "semi-month", "semi-month", "11", "month", "month", "11", "yearly", "yearly", "11", "quaterly", "quaterly", "12", "Money", "Money", "12", "Items", "Items", "12", "Service", "Service", "12", "Working Days", "Working Days", "13", "Motto", "Motto of a Group/Division", "13", "Mission", "Mission of a Group/Division", "13", "Vision", "Vision of a Group/Division", "24", " ", " ", "24", "Mr.", "Mr.", "24", "Mrs.", "Mrs.", "24", "Master", "Master", "24", "Ms.", "Ms.", "24", "Miss.", "Miss.", "24", "Dr.", "Dr.", "24", "Prof.", "Prof.", "25", "Male", "Male", "25", "Female", "Female", "25", "Not Applicable", "Not Applicable", "26", "Single", "Single", "26", "Married", "Married", "26", "Divorced", "Divorced", "26", "Separated", "Separated", "26", "Widow", "Widow", "26", "Widower", "Widower", "27", "Ghanaian", "Ghanaian", "27", "American", "American", "27", "British", "British", "27", "Togolese", "Togolese", "41", "2400", "9999.Rhomicom Basic Worker Grade.P1", "41", "3000", "9999.Rhomicom Basic Worker Grade.P2", "42", "01-JAN-1900 00:00:00", "01-JAN-1900", "43", "31-DEC-4000 23:59:59", "31-DEC-4000", "45", "Bank of Ghana", "Bank of Ghana", "45", "Barclays Bank", "Barclays Bank", "45", "Standard Chartered Bank", "Standard Chartered Bank", "45", "Ghana Commercial Bank", "Ghana Commercial Bank", "45", "Prudential Bank", "Prudential Bank", "46", "Accra Branch", "Accra Branch", "46", "Makola Branch", "Makola Branch", "46", "Ring Road Branch", "Ring Road Branch", "46", "Kaneshie Branch", "Kaneshie Branch", "46", "KNUST Branch", "KNUST Branch", "47", "Current Account", "Kaneshie Branch", "47", "Savings Account", "KNUST Branch", "57", "Payslip Item", "Payslip Items-Items that appear on Payslip after during payroll run", "57", "Payroll Item", "Payroll Items-Items Run during payroll run but don't appear on Payslip", "57", "Bill Item", "Bill Items Eg. School Fees Bill Items", "57", "Balance Item", "Balance Items Eg. TAKE HOME PAY", "59", "Cash Cheque", "Cash Cheque", "59", "Clearing Cheque", "Clearing Cheque", "59", "Payment Order", "Payment Order", "59", "Visa Card", "Visa Card", "59", "Mastercard", "Mastercard", "59", "Ezwich", "Ezwich", "59", "Visa Ghana", "Visa Ghana", "59", "Paypal", "Paypal", "59", "Mobile Money", "Mobile Money",
    "59", "Supplier Cheque", "Supplier Cheque",
    "59", "Supplier Cash", "Supplier Cash",
    "59", "Customer Cheque", "Customer Cheque",
    "59", "Customer Cash", "Customer Cash",
    "59", "Supplier Prepayment Application", "Supplier Prepayment Application",
    "59", "Customer Prepayment Application", "Customer Prepayment Application",
    "60", "192.168.0.100", "192.168.0.100",
    "60", "localhost", "localhost",
    "61", "Bsc. Computer Science", "Computer Science Degree",
    "61", "Bsc. Computer Engineering", "Computer Engineering Degree",
    "61", "B.E.C.E", "B.E.C.E",
    "61", "W.A.S.S.C.E", "W.A.S.S.C.E",
    "61", "S.S.C.E", "S.S.C.E",
    "61", "A-Level", "A-Level",
    "61", "O-Level", "O-Level",
    "62", "Kwame Nkrumah University of Science and Technology", "Tertiary",
    "62", "University of Ghana-Legon", "Tertiary",
    "62", "Prempeh College", "Secondary",
    "63", "Accra-Ghana", "Accra-Ghana",
    "63", "Kumasi-Ghana", "Kumasi-Ghana",
    "64", "Engineer", "Engineer",
    "64", "IT Technician", "IT Technician",
    "65", "B.E.C.E", "B.E.C.E",
    "65", "W.A.S.S.C.E", "W.A.S.S.C.E",
    "65", "S.S.C.E", "S.S.C.E",
    "65", "A-Level", "A-Level",
    "65", "O-Level", "O-Level",
    "65", "Bsc.", "Bachelor of Science",
    "65", "Msc.", "Master of Science",
    "65", "PhD", "Doctor of Philosophy",
    "66", "Twi", "Twi",
    "66", "English", "English",
    "67", "Playing Soccer", "Playing Soccer",
    "67", "Playing Lead Guitar", "Playing Lead Guitar",
    "68", "Singing", "Singing",
    "68", "Reading", "Reading",
    "69", "Calm", "Calm",
    "69", "Respectful", "Respectful",
    "70", "Hardworking", "Hardworking",
    "70", "Serious", "Serious",
    "71", "Rhomicom Systems Tech. Ltd.", "Rhomicom Systems Tech. Ltd.",
    "72", "Basic Person Data", "Personnel/ Membership Data",
    "72", "Basic Person Data", "STAFF DATA",
    "72", "Internal Payments", "Personnel/ Membership Payments",
    "72", "Internal Payments", "STAFF PAYROLL",
    "72", "Learning/Performance Management", "Academics Management System",
    "72", "Hospitality Management", "Hospitality Management",
    "72", "Events and Attendance", "Events and Attendance",
    "72", "Sales and Inventory", "Sales and Inventory",
    "72", "Sales and Inventory", "Inventory",
    "72", "Project Management", "Projects Management",
    "73", "Basic Person Data Administrator", "'All'",
    "73", "Personnel Data Administrator", "'Employee','Staff'",
    "74", "Invoices Signatories", "                    Prepared By                    Authorized By                    Approved By",
    "74", "PO Signatories", "                    Prepared By                    Authorized By                    Approved By",
    "74", "Receipt Signatories", "                    Received By                    Inspected By                    Approved By",
    "74", "Receipt Return Signatories", "                    Returned By                    Authorized By                    Approved By",
    "74", "Payroll Signatories", "                    Prepared By                    Authorized By                    Approved By",
    "75", "Curriculum Vitae", "Curriculum Vitae",
    "75", "Membership Forms", "Membership Forms",
    "75", "Career Report", "Career Report",
    "75", "Other Information", "Other Information",
    "76", "Public Company Ltd", "Public Company Ltd",
    "76", "Private Company Ltd", "Private Company Ltd",
    "76", "Closed Corporation", "Closed Corporation",
    "76", "Joint Venture", "Joint Venture",
    "76", "Consortium", "Consortium",
    "76", "Partnership", "Partnership",
    "76", "Sole Proprietor", "Sole Proprietor",
    "76", "Foreign Company", "Foreign Company",
    "76", "Government/Parastatals", "Government/Parastatals",
    "76", "Trust", "Trust",
    "77", "Architecture", "Architecture",
    "77", "Surveying", "Surveying",
    "77", "Project Management", "Project Management",
    "77", "Planning", "Planning",
    "77", "Engineering", "Engineering",
    "79", "Kwame Nkrumah University of Science and Technology", "School",
    "79", "University of Ghana-Legon", "School",
    "79", "Prempeh College", "School",
    "79", "Rhomicom Systems Tech. Ltd.", "Company",
    "80", "Petty Cash", "Petty Cash",
    "80", "Cash Balance", "Cash Balance",
    "80", "Bank Balance", "Bank Balance",
    "80", "Cash and Cash Equivalents", "Cash and Cash Equivalents",
    "80", "Operating Activities.Sale of Goods", "Operating Activities.Sale of Goods",
    "80", "Operating Activities.Sale of Services", "Operating Activities.Sale of Services",
    "80", "Operating Activities.Other Income Sources", "Operating Activities.Other Income Sources",
    "80", "Operating Activities.Cost of Sales", "Operating Activities.Cost of Sales",
    "80", "Operating Activities.Net Income", "Operating Activities.Net Income",
    "80", "Operating Activities.Depreciation Expense", "Operating Activities.Depreciation Expense",
    "80", "Operating Activities.Amortization Expense", "Operating Activities.Amortization Expense",
    "80", "Operating Activities.Gain on Sale of Equipment", "Operating Activities.Gain on Sale of Equipment"/* NEGATE */,
    "80", "Operating Activities.Loss on Sale of Equipment", "Operating Activities.Loss on Sale of Equipment",
    "80", "Operating Activities.Other Non-Cash Expense", "Operating Activities.Other Non-Cash Expense",
    "80", "Operating Activities.Accounts Receivable", "Operating Activities.Accounts Receivable"/* NEGATE */,
    "80", "Operating Activities.Bad Debt Expense", "Operating Activities.Bad Debt Expense"/* NEGATE */,
    "80", "Operating Activities.Prepaid Expenses", "Operating Activities.Prepaid Expenses"/* NEGATE */,
    "80", "Operating Activities.Inventory", "Operating Activities.Inventory"/* NEGATE */,
    "80", "Operating Activities.Accounts Payable", "Operating Activities.Accounts Payable",
    "80", "Operating Activities.Accrued Expenses", "Operating Activities.Accrued Expenses",
    "80", "Operating Activities.Taxes Payable", "Operating Activities.Taxes Payable",
    "80", "Operating Activities.Operating Expense", "Operating Activities.Operating Expense",
    "80", "Operating Activities.General and Administrative Expense", "Operating Activities.General and Administrative Expense",
    "80", "Investing Activities.Asset Sales/Purchases", "Investing Activities.Asset Sales/Purchases"/* NEGATE */,
    "80", "Investing Activities.Equipment Sales/Purchases", "Investing Activities.Equipment Sales/Purchases"/* NEGATE */,
    "80", "Financing Activities.Capital/Stock", "Financing Activities.Capital/Stock",
    "80", "Financing Activities.Long Term Debts", "Financing Activities.Long Term Debts",
    "80", "Financing Activities.Short Term Debts", "Financing Activities.Short Term Debts",
    "80", "Financing Activities.Equity Securities", "Financing Activities.Equity Securities",
    "80", "Financing Activities.Dividends Declared", "Financing Activities.Dividends Declared"/* NEGATE */,
    "80", "", "",
    "81", "TIN", "LEE 12345",
    "83", "url", "http://txtconnect.co/api/send/",
    "83", "token", "123456789",
    "83", "msg", "{:msg}",
    "83", "from", "Rhomicom",
    "83", "to", "{:to}",
    "84", "QR Code Validation URL", "https://www.rhomicomgh.com/index.php?id=",
    "86", "INSERT STATEMENTS", "INSERT STATEMENTS",
    "86", "UPDATE STATEMENTS", "UPDATE STATEMENTS",
    "86", "DELETE STATEMENTS", "DELETE STATEMENTS",
    "86", "INFO ON DATA VIEWED", "INFO ON DATA VIEWED",
    "87", "Branch", "Branch",
    "87", "Agency", "Agency",
    "88", "Examined-Fit", "Examined-Fit",
    "88", "Examined-Unfit", "Examined-Unfit",
    "88", "Issuable", "Issuable",
    "88", "Mint", "Mint",
    "88", "Stack(Non-Mint)", "Stack(Non-Mint)",
    "88", "Unexamined", "Unexamined",
    "89", "Accounting", "Accounting",
    "89", "Basic Person Data", "Basic Person Data",
    "89", "Internal Payments", "Internal Payments",
    "89", "Stores And Inventory Manager", "Stores And Inventory Manager",
    "89", "Events And Attendance", "Events And Attendance",
    "89", "Hospitality Management", "Hospitality Management",
    "89", "Visits and Appointments", "Visits and Appointments",
    "89", "Projects Management", "Projects Management",
    "89", "Learning/Performance Management", "Learning/Performance Management",
    "89", "Generic Module", "Generic Module",
    "89", "General Setup", "General Setup",
    "89", "Organization Setup", "Organisation Setup",
    "89", "Reports And Processes", "Reports And Processes",
    "89", "System Administration", "System Administration",
    "90", "Allow Multiple Same User Logons", "No",
    "90", "Allow Workflow Emails", "No",
    "90", "Show Home Page Slider", "No",
    "90", "Show Home Page Slider", "Yes",
    "90", "Allow Payroll to be Auto-Accounted", "Yes",
    "90", "FTP Base DB Folder", "/opt/apache/adbs",
    "90", "Allow User Account Self-Registration", "No",
    "90", "Use Default Additional Person Data", "Yes",
    "90", "Use Customized Additional Person Data", "No",
    "90", "Configured System Type 1", "Enterprise Resource Planning",
    "90", "Configured System Type 2", "Accounting System",
    "90", "Configured System Type 3", "Banking and Microfinance Application",
    "90", "Configured System Type 4", "School Management System",
    "90", "Configured System Type 5", "Church Management System",
    "90", "Configured System Type 6", "Hotel Management System",
    "90", "Configured System Type 7", "Hospital Management System",
    "90", "Configured System Type 8", "Point of Sale Application",
    "90", "Configured System Type 9", "Association Management System",
    "90", "Configured System Type 10", "NGO Management System",
    "90", "Application Instance SHORT CODE", "RHOMICOM_MAIN_ERP_APP_1",
    "90", "Rho Email/SMS API Base URL", "http://rho-nginx:8080/",
    "94", "Default Facility Type", "Room/Hall",
    "94", "Default Rental Calc Method", "Days",
    "94", "Default Check-In Calc Method", "Nights",
    "95", $admin_email, $admin_email
);

$lvid = getLovID("Security Keys");
$apKey = getEnbldPssblValDesc(
    "AppKey",
    $lvid
);
if ($apKey != "" && $lvid > 0) {
    $smplTokenWord = $apKey;
} else if ($lvid <= 0) {
    $apKey = "ROMeRRTRREMhbnsdGeneral KeyZzfor Rhomi|com Systems "
        . "Tech. !Ltd Enterpise/Organization @763542ERPorbjkSOFTWARE"
        . "asdbhi68103weuikTESTfjnsdfRSTLU../";
    $smplTokenWord = $apKey;
    createLovNm("Security Keys", "Security Keys", false, "", "SYS", true);
    $lvid = getLovID("Security Keys");
    if ($lvid > 0) {
        createPssblValsForLov($lvid, "AppKey", $apKey, true, get_all_OrgIDs());
    }
}

function getUserPswd($username)
{
    $sqlStr = "select usr_password from sec.sec_users where lower(user_name) = lower('" .
        loc_db_escape_string($username) . "')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function storeOldPassword($usrid, $pswd)
{
    global $smplTokenWord;
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_users_old_pswds(user_id, old_password, date_added) 
            VALUES (" . $usrid . ", md5('" . loc_db_escape_string(encrypt($pswd, $smplTokenWord)) .
        "'), '" . $dateStr . "')";
    executeSQLNoParams($sqlStr);
}

function isLoginInfoCorrct($usrname, $pswd)
{
    global $smplTokenWord;
    $sqlStr = "SELECT user_id FROM sec.sec_users WHERE ((lower(user_name) = lower('" .
        loc_db_escape_string($usrname) .
        "')) AND (usr_password = md5('" . loc_db_escape_string(encrypt($pswd, $smplTokenWord)) .
        "')) AND (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') AND " .
        "to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))";
    //var_dump($sqlStr);
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isPswdInRcntHstry($pswd, $usrid)
{
    // Checks whether the new password is in the past disllowed number of passwords
    global $smplTokenWord;
    $sqlStr = "SELECT a.old_pswd_id FROM 
    (SELECT old_pswd_id, old_password FROM sec.sec_users_old_pswds WHERE(user_id = " .
        $usrid . ") ORDER BY old_pswd_id DESC limit " . get_CurPlcy_DsllwdPswdCnt() .
        ") a WHERE(a.old_password = md5('" . loc_db_escape_string(encrypt($pswd, $smplTokenWord)) . "'))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function checkNCreateUser($UNM, &$dsply)
{
    global $usrID;
    ////Using Loc ID No.
    //Check if username doesn't exist in sec but exists in prsn_names_nos
    //Get Person ID if true and 
    //Create this User Person
    $usr_Nm = $UNM;
    $nwPrsnID = getNewUserPrsnID($UNM);
    $oldUsrID = getUserID($usr_Nm);
    if ($nwPrsnID > 0) {
        $affctd = 0;
        $affctd1 = 0;
        //$prn_Nm = getPrsnFullNm($nwPrsnID);
        $prn_Loc_ID = $UNM;
        $start_date = date('d-M-Y H:i:s');
        $end_date = '31-Dec-4000 23:59:59';
        $datestr = getDB_Date_time();
        $slctdRoles = "-1;" . getRoleID('Self-Service (Standard)') . ";" . $start_date . ";" . $end_date . "|";
        //exit();
        if ($usr_Nm == "" || $prn_Loc_ID == "" || $start_date == "" || $end_date == "") {
            $dsply = "Please fill all required fields!";
            return FALSE;
        } else if ($oldUsrID > 0) {
            $dsply = "New User Name($usr_Nm) is already in Use!";
            return FALSE;
        } else {
            $start_date = cnvrtDMYTmToYMDTm($start_date);
            $end_date = cnvrtDMYTmToYMDTm($end_date);
            $prsID = $nwPrsnID;
            $insSQL = "INSERT INTO sec.sec_users(
            user_name, usr_password, person_id, is_suspended, is_pswd_temp, 
            failed_login_atmpts, last_login_atmpt_time, last_pswd_chng_time, 
            valid_start_date, valid_end_date, created_by, creation_date, 
            last_update_by, last_update_date) 
            VALUES('" . loc_db_escape_string($usr_Nm) . "', '" . loc_db_escape_string($usr_Nm) .
                "', $prsID, FALSE, TRUE, 0, '$datestr', '$datestr', '" . $start_date . "', '" . $end_date . "', 
                $usrID, '$datestr', $usrID, '" . $datestr . "')";
            $affctd = execUpdtInsSQL($insSQL);
        }

        if ($slctdRoles != "") {
            $usr_IDNo = getUserID($usr_Nm);
            $arry1 = explode('|', $slctdRoles);
            for ($i = 0; $i < count($arry1); $i++) {
                $arry2 = explode(';', $arry1[$i]);
                //var_dump($arry2);
                if ($arry2[0] == "") {
                    continue;
                }
                $usrRoleID = $arry2[0];
                $roleID = $arry2[1];
                $usrHasRole = FALSE;
                if ($roleID > 0 && $usr_IDNo > 0) {
                    $usrHasRole = doesUsrIDHvThsRoleID($usr_IDNo, $roleID);
                    $roleStrDte = cnvrtDMYTmToYMDTm($arry2[2]);
                    $roleEndDate = cnvrtDMYTmToYMDTm($arry2[3]);
                    if ($usrRoleID > 0) {
                        $updtSQL = "UPDATE sec.sec_users_n_roles
   SET role_id=$roleID, valid_start_date='$roleStrDte', valid_end_date='$roleEndDate', 
       last_update_by=$usrID, last_update_date='$datestr'
 WHERE dflt_row_id=$usrRoleID and user_id = $usr_IDNo";
                        $affctd1 += execUpdtInsSQL($updtSQL);
                    } else if ($usrHasRole == FALSE) {
                        $insSQL = "INSERT INTO sec.sec_users_n_roles(
            user_id, role_id, valid_start_date, valid_end_date, created_by, 
            creation_date, last_update_by, last_update_date)
    VALUES ($usr_IDNo, $roleID, '$roleStrDte', '$roleEndDate', 
            $usrID, '" . $datestr . "', $usrID, '" . $datestr . "');";
                        $affctd1 += execUpdtInsSQL($insSQL);
                    }
                }
            }
        }
        if ($affctd > 0) {
            $dsply .= "Successfully saved records of " . $usr_Nm;
            $dsply .= "<br/>$affctd1 User's Role(s) Assigned!<br/>";
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        if ($oldUsrID > 0) {
        } else {
            $dsply .= "<span style=\"color:red;\">User Name or ID No. does not exist!<br/></span>";
        }
        return FALSE;
    }
}

function checkNCreateUserUsgMail($UNM, &$dsply)
{
    global $usrID;
    ////Using Loc ID No.
    //Check if username doesn't exist in sec but exists in prsn_names_nos
    //Get Person ID if true and 
    //Create this User Person

    $usr_Nm = $UNM;
    $nwPrsnID = getNewUserPrsnIDUseMail($UNM);
    $oldUsrID = getUserID($usr_Nm);
    if ($nwPrsnID > 0) {
        $affctd = 0;
        $affctd1 = 0;
        $start_date = date('d-M-Y H:i:s');
        $end_date = '31-Dec-4000 23:59:59';
        $datestr = getDB_Date_time();
        $slctdRoles = "-1;" . getRoleID('Self-Service (Standard)') . ";" . $start_date . ";" . $end_date . "|";
        //exit();
        if ($usr_Nm == "" || $start_date == "" || $end_date == "") {
            $dsply = "Please fill all required fields!";
            return FALSE;
        } else if ($oldUsrID > 0) {
            $dsply = "New User Name($usr_Nm) is already in Use!";
            return FALSE;
        } else {
            $start_date = cnvrtDMYTmToYMDTm($start_date);
            $end_date = cnvrtDMYTmToYMDTm($end_date);
            $prsID = $nwPrsnID;
            $insSQL = "INSERT INTO sec.sec_users(
            user_name, usr_password, person_id, is_suspended, is_pswd_temp, 
            failed_login_atmpts, last_login_atmpt_time, last_pswd_chng_time, 
            valid_start_date, valid_end_date, created_by, creation_date, 
            last_update_by, last_update_date) 
            VALUES('" . loc_db_escape_string($usr_Nm) . "', '" . loc_db_escape_string($usr_Nm) .
                "', $prsID, FALSE, TRUE, 0, '$datestr', '$datestr', '" . $start_date . "', '" . $end_date . "', 
                $usrID, '$datestr', $usrID, '" . $datestr . "')";
            $affctd = execUpdtInsSQL($insSQL);
        }

        if ($slctdRoles != "") {
            $usr_IDNo = getUserID($usr_Nm);
            $arry1 = explode('|', $slctdRoles);
            for ($i = 0; $i < count($arry1); $i++) {
                $arry2 = explode(';', $arry1[$i]);
                //var_dump($arry2);
                if ($arry2[0] == "") {
                    continue;
                }
                $usrRoleID = $arry2[0];
                $roleID = $arry2[1];
                $usrHasRole = FALSE;
                if ($roleID > 0 && $usr_IDNo > 0) {
                    $usrHasRole = doesUsrIDHvThsRoleID($usr_IDNo, $roleID);
                    $roleStrDte = cnvrtDMYTmToYMDTm($arry2[2]);
                    $roleEndDate = cnvrtDMYTmToYMDTm($arry2[3]);
                    if ($usrRoleID > 0) {
                        $updtSQL = "UPDATE sec.sec_users_n_roles
   SET role_id=$roleID, valid_start_date='$roleStrDte', valid_end_date='$roleEndDate', 
       last_update_by=$usrID, last_update_date='$datestr'
 WHERE dflt_row_id=$usrRoleID and user_id = $usr_IDNo";
                        $affctd1 += execUpdtInsSQL($updtSQL);
                    } else if ($usrHasRole == FALSE) {
                        $insSQL = "INSERT INTO sec.sec_users_n_roles(
            user_id, role_id, valid_start_date, valid_end_date, created_by, 
            creation_date, last_update_by, last_update_date)
    VALUES ($usr_IDNo, $roleID, '$roleStrDte', '$roleEndDate', 
            $usrID, '" . $datestr . "', $usrID, '" . $datestr . "');";
                        $affctd1 += execUpdtInsSQL($insSQL);
                    }
                }
            }
        }
        if ($affctd > 0) {
            $dsply .= "Successfully saved records of " . $usr_Nm;
            $dsply .= "<br/>$affctd1 User's Role(s) Assigned!<br/>";
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        if ($oldUsrID > 0) {
        } else {
            $dsply .= "<span style=\"color:red;\">User Name or ID No. does not exist!<br/></span>";
        }
        return FALSE;
    }
}

function sendEmailVerifyLink($UNM)
{
    //echo "Inside sendPswdResetLink!$UNM";
    global $app_name;
    global $app_url;
    global $admin_email;
    global $smplTokenWord1;

    if ($UNM == "") {
        echo "Please select a User First!";
        return "";
    }
    $errMsg = "";
    $errMsg .= "<div style=\"background-color:#e3e3e3;border: 1px solid #999;padding:10px;\" class=\"rho-postcontent rho-postcontent-0 clearfix\"> ";
    $inUsrID = getUserID($UNM);
    if ($inUsrID > 0) {
        $numChars = rand(10, 25);
        $numChars1 = rand(10, 25);
        $nwTxt = getRandomTxt($numChars);
        $nwTxt1 = getRandomTxt($numChars1);
        $rqstDate = getDB_Date_time();
        $expDate = getDB_Date_TmIntvlAdd('1 year');
        $encrptdUrl = encrypt1("" . $nwTxt . "|$numChars|$rqstDate|verifyyouremail|$expDate|$UNM|$numChars1|" . $nwTxt1 . "", $smplTokenWord1);
        //execUpdtInsSQL("", "Password Reset Link");
        //storeOldPassword($inUsrID, getUserPswd($UNM));
        //changeUserPswd($inUsrID, $nwPswd, 'TRUE', 1);
        /* "<br/>"
          . $app_url . "?g=" . $encrptdUrl . */
        $prsnID = getUserPrsnID($UNM);
        $to = getPrsnEmail($prsnID);
        $nameto = getPrsnFullNm($prsnID);
        $subject = $app_name . " EMAIL VERIFICATION";
        $message = "<p style=\"font-family: Calibri;font-size:18px;\">Hello $nameto <br/><br/>"
            . "You have successfully registered your account with the Username: $UNM" .
            "<br/>If this request was made by you then Please <br/><a href=\""
            . $app_url . "self/?vrfy=" . $encrptdUrl . "\">Click on this link to VERIFY your email address!</a>"
            . "<br/><br/>"
            . "If on the other hand you didn't request for this, please ignore this message!<br/><br/>"
            . "Thank you!</p>";
        $isSent = sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", $admin_email, "");
        if ($isSent === TRUE) {
            $errMsg .= "<span style=\"color:green;\">Email Sent Successfully! Please Check your Registered E-mail for Further Instructions!</span>";
        } else {
            $errMsg .= "<span style=\"color:red;\">$errMsg</span>";
        }
    } else {
        $errMsg .= "<span style=\"color:red;\">Please enter a Valid User Name or ID No.!</span>";
    }
    $errMsg .= "</div>";
    return $errMsg;
}

function changeUserPswd($usr_id, $pswd, $isTmp, $isAuto = 0)
{
    global $smplTokenWord;
    if ($usr_id <= 0) {
        $usrID = $_SESSION['USRID'];
    } else {
        $usrID = $usr_id;
    }
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE sec.sec_users SET usr_password = md5('" . loc_db_escape_string(encrypt($pswd, $smplTokenWord)) .
        "'), last_pswd_chng_time = '" . $dateStr . "', is_pswd_temp = $isTmp, last_update_by = " .
        $usrID . ", last_update_date = '" . $dateStr . "', failed_login_atmpts=0 WHERE (user_id = '" . $usr_id . "')";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_affected_rows($result) > 0) {
        if ($isAuto == 1) {
            echo "<strong>Automatic Password Successfully Generated!</strong><br/><br/>";
        } else {
            if (is_User_SelfOnly($usr_id) === true) {
                echo "<strong>Password Successfully Changed! <br/>"
                    . "Click <a href=\"javascript: window.location='self/index.php';\">"
                    . "here to return to HOME PAGE!</a></strong><br/><br/>";
            } else {
                echo "<strong>Password Successfully Changed! <br/>"
                    . "Click <a href=\"javascript: window.location='index.php';\">"
                    . "here to return to HOME PAGE!</a></strong><br/><br/>";
            }
        }
    }
}

function is_User_SelfOnly($usrID)
{
    $sqlStr = "Select count(z.dflt_row_id) from sec.sec_users_n_roles z "
        . "LEFT OUTER JOIN sec.sec_roles b ON (z.role_id = b.role_id) where z.user_id= " . $usrID .
        "and (to_char(now(), 'YYYY-MM-DD HH24:MI:SS') between z.valid_start_date and z.valid_end_date)
AND (to_char(now(), 'YYYY-MM-DD HH24:MI:SS') between b.valid_start_date and b.valid_end_date)";
    //echo $sqlStr;
    $hvRole = doesUserHaveThisRole(getUserName($usrID), "Self-Service (Standard)");
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return ((((int) $row[0]) == 1) && $hvRole);
    }
    return false;
}

function get_CurPlcy_SessnTime()
{
    $sqlStr = "SELECT session_timeout FROM 
    sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 300;
}

function get_CurPlcy_Mx_Dsply_Recs()
{
    $sqlStr = "SELECT max_no_recs_to_dsply FROM 
    sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 30;
}

function get_CurPlcy_Mx_Fld_lgns()
{
    $sqlStr = "SELECT max_failed_lgn_attmpts FROM 
    sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 1000000;
}

function get_CurPlcy_Pwd_Exp_Days()
{
    $sqlStr = "SELECT pswd_expiry_days FROM 
    sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 1000000;
}

function get_CurPlcy_Auto_Unlck_tme()
{
    $sqlStr = "SELECT auto_unlocking_time_mins FROM 
       sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 0;
}

function get_CurPlcy_DsllwdPswdCnt()
{
    $sqlStr = "SELECT old_pswd_cnt_to_disallow FROM 
   sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 10;
}

function get_CurPlcy_Min_Pwd_Len()
{
    $sqlStr = "SELECT pswd_min_length FROM 
   sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 1;
}

function get_CurPlcy_Mx_Pwd_Len()
{
    $sqlStr = "SELECT pswd_max_length FROM 
   sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return 25;
}

function get_CrPlc_Rqrmt_Cmbntn()
{
    $sqlStr = "SELECT pswd_reqrmnt_combntns FROM 
   sec.sec_security_policies WHERE is_default = 't'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return "$row[0]";
    }
    return "NONE";
}

function doesPswdCmplxtyMeetPlcy(
    $pswd,
    $uname,
    &$msgStr
) {
    //Checks Whether a password meets the current password complexity policy
    $rqrmnts_met = 0;
    $minPwdLen = get_CurPlcy_Min_Pwd_Len();
    $maxPwdLen = get_CurPlcy_Mx_Pwd_Len();

    if (strlen($pswd) < $minPwdLen || strlen($pswd) > $maxPwdLen) {
        $msgStr = "Length of Password must be between $minPwdLen and $maxPwdLen!";
        return false;
    }
    if (allowUnameInPswd() == false) {
        if (strpos(strtolower($pswd), strtolower($uname)) === true) {
            $msgStr = "Password must not contain user name!";
            return false;
        }
    }
    $allwRpeatng = allowRepeatngChars();
    $pwd_arry = str_split($pswd);
    $seenCaps = false;
    $seenSmall = false;
    $seenDigit = false;
    $seenWild = false;
    $msgStr = "";
    $isSmallReq = isSmallLtrRequired();
    $isCapsReq = isCapsRequired();
    $isDigitReq = isDigitRequired();
    $isWildReq = isWildCharRequired();
    $cmbntnSet = get_CrPlc_Rqrmt_Cmbntn();

    for ($i = 0; $i < count($pwd_arry); $i++) {
        if ($allwRpeatng === false && $i > 0) {
            if ($pwd_arry[$i] === $pwd_arry[$i - 1]) {
                $msgStr = "Password must not contain Repeating Characters!";
                return false;
            }
        }
        if (ctype_alpha($pwd_arry[$i])) {
            if (ctype_lower($pwd_arry[$i]) && $isSmallReq === true && $seenSmall === false) {
                $rqrmnts_met += 1;
                $seenSmall = true;
                continue;
            }
            if (ctype_upper($pwd_arry[$i]) && $isCapsReq === true && $seenCaps === false) {
                $rqrmnts_met += 1;
                $seenCaps = true;
                continue;
            }
        } else if (ctype_digit($pwd_arry[$i]) && $isDigitReq === true && $seenDigit === false) {
            $rqrmnts_met += 1;
            $seenDigit = true;
            continue;
        } else if (ctype_alnum($pwd_arry[$i]) === false && $isWildReq == true && $seenWild == false) {
            $rqrmnts_met += 1;
            $seenWild = true;
            continue;
        }
    }
    if ($cmbntnSet === "NONE" || $cmbntnSet === "") {
        return true;
    } else if ($cmbntnSet === "ALL 4" && $rqrmnts_met >= 4) {
        return true;
    } else if ($cmbntnSet === "ANY 3" && $rqrmnts_met >= 3) {
        return true;
    } else if ($cmbntnSet === "ANY 2" && $rqrmnts_met >= 2) {
        return true;
    } else if ($cmbntnSet === "ANY 1" && $rqrmnts_met >= 1) {
        return true;
    } else {
        $msgStr = "Password must contain " . $cmbntnSet . " of the ff:";
        if ($isCapsReq) {
            $msgStr .= "<br/>Block Letters!";
        }
        if ($isSmallReq) {
            $msgStr .= "<br/>Small Letters!";
        }
        if ($isDigitReq) {
            $msgStr .= "<br/>Numbers/Digits!";
        }
        if ($isWildReq) {
            $msgStr .= "<br/>Wild Characters (e.g. @, #)!";
        }
        return false;
    }
}

function isCapsRequired()
{
    //Checks Whether caps is required in a password


    $sqlStr = "SELECT pswd_require_caps FROM sec.sec_security_policies 
       WHERE is_default = 't' and pswd_require_caps='t'";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isSmallLtrRequired()
{
    //Checks Whether small letter is required in a password
    $sqlStr = "SELECT pswd_require_small FROM sec.sec_security_policies 
       WHERE is_default = 't' and pswd_require_small='t'";


    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isDigitRequired()
{
    //Checks Whether Digit is required in a password
    $sqlStr = "SELECT pswd_require_dgt FROM sec.sec_security_policies 
       WHERE is_default = 't' and pswd_require_dgt='t'";


    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function isWildCharRequired()
{
    //Checks Whether Wild Character is required in a password
    $sqlStr = "SELECT pswd_require_wild FROM sec.sec_security_policies 
       WHERE is_default = 't' and pswd_require_wild='t'";


    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function allowUnameInPswd()
{
    //Checks Whether User name is allowed in a password
    $sqlStr = "SELECT allow_usrname_in_pswds FROM sec.sec_security_policies 
       WHERE is_default = 't' and allow_usrname_in_pswds='t'";


    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function allowRepeatngChars()
{
    //Checks Whether Repeating Characters are allowed in a password
    $sqlStr = "SELECT allow_repeating_chars FROM sec.sec_security_policies 
       WHERE is_default = 't' and allow_repeating_chars='t'";


    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function doesPwdHvRptngChars($pwd)
{
    for (
        $i = 0;
        $i < strlen($pwd);
        $i++
    ) {
        if ($i > 0) {
            if (substr($pwd, $i, 1) === substr($pwd, ($i - 1), 1)) {
                return true;
            }
        }
    }
    return false;
}

function doesUsrIDHvThsRoleID($user_ID, $role_ID)
{
    $sqlStr = "SELECT user_id FROM sec.sec_users_n_roles WHERE ((user_id = $user_ID) 
        AND (role_id = $role_ID))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function doesUsrIDHvThsRoleIDNow($user_ID, $role_ID)
{
    $sqlStr = "SELECT user_id FROM sec.sec_users_n_roles WHERE ((user_id = $user_ID) 
        AND (role_id = $role_ID) and (now() between to_timestamp(valid_start_date,'YYYY-MM-DD HH24:MI:SS') "
        . "and to_timestamp(valid_end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function selfAssignSSRoles($uID)
{
    $userID = $_SESSION['USRID'];
    $start_date = date('d-M-Y H:i:s');
    $end_date = '31-Dec-4000 23:59:59'; //date('d-M-Y H:i:s', strtotime('31-Dec-4000 23:59:59'));
    $datestr = getDB_Date_time();
    $slctdRoles = "-1;" . getRoleID('Self-Service (Standard)') . ";" . $start_date . ";" . $end_date . "|";
    if ($slctdRoles != "") {
        $arry1 = explode('|', $slctdRoles);
        for ($i = 0; $i < count($arry1); $i++) {
            $arry2 = explode(';', $arry1[$i]);
            if ($arry2[0] == "") {
                continue;
            }
            $usrRoleID = $arry2[0];
            $roleID = $arry2[1];
            $usrHasRole = FALSE;

            if ($roleID > 0 && $uID > 0) {
                $usrHasRole = doesUsrIDHvThsRoleIDNow($uID, $roleID);
                $roleStrDte = cnvrtDMYTmToYMDTm($arry2[2]);
                $roleEndDate = cnvrtDMYTmToYMDTm($arry2[3]);
                if ($usrRoleID > 0) {
                } else if ($usrHasRole == FALSE) {
                    $insSQL = "INSERT INTO sec.sec_users_n_roles(
            user_id, role_id, valid_start_date, valid_end_date, created_by, 
            creation_date, last_update_by, last_update_date)
    VALUES ($uID, $roleID, '$roleStrDte', '$roleEndDate', 
            $userID, '" . $datestr . "', $userID, '" . $datestr . "')";
                    execUpdtInsSQL($insSQL);
                }
            }
        }
    }
}

function registerThsModule($ModuleName, $ModuleDesc, $ModuleAdtTbl)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_modules(module_name, module_desc, " .
        "date_added, audit_trail_tbl_name) VALUES ('" .
        loc_db_escape_string($ModuleName) . "', '" . loc_db_escape_string($ModuleDesc) .
        "', '" . $dateStr . "', '" . loc_db_escape_string($ModuleAdtTbl) . "')";
    $result = execUpdtInsSQL($sqlStr);
}

function registerThsModulesSubgroups($sub_grp_nm, $mn_table_nm, $rw_pk_nm, $mdlID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_module_sub_groups (sub_group_name, main_table_name, " .
        "row_pk_col_name, module_id, date_added) VALUES ('" .
        loc_db_escape_string($sub_grp_nm) . "', '" .
        loc_db_escape_string($mn_table_nm) . "', '" .
        loc_db_escape_string($rw_pk_nm) . "', " .
        $mdlID .
        ", '" . $dateStr . "')";
    $result = execUpdtInsSQL($sqlStr);
}

function createSampleRole($roleNm)
{
    $uID = -1;
    global $usrID;
    if ($usrID <= 0) {
        $uID = getUserID("admin");
    } else {
        $uID = $usrID;
    }

    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_roles(role_name, valid_start_date, valid_end_date, created_by, " .
        "creation_date, last_update_by, last_update_date) VALUES ('" . loc_db_escape_string($roleNm) . "', '" .
        $dateStr . "', '4000-12-31 00:00:00', " . $uID . ", '" . $dateStr . "', " . $uID . ", '" . $dateStr . "')";
    $result = execUpdtInsSQL($sqlStr);
}

function createPrvldg($prvlg_nm, $ModuleName)
{
    $sqlStr = "INSERT INTO sec.sec_prvldgs(prvldg_name, module_id) VALUES ('" .
        loc_db_escape_string($prvlg_nm) . "', " . getModuleID($ModuleName) . ")";
    $result = execUpdtInsSQL($sqlStr);
}

function asgnPrvlgToSmplRole($prvldg_id, $roleNm)
{
    $uID = -1;
    global $usrID;
    if ($usrID <= 0) {
        $uID = getUserID("admin");
    } else {
        $uID = $usrID;
    }
    $dateStr = getDB_Date_time();
    if ($prvldg_id > 0) {
        $sqlStr = "INSERT INTO sec.sec_roles_n_prvldgs(role_id, prvldg_id, 
        valid_start_date, valid_end_date, created_by, " .
            "creation_date, last_update_by, last_update_date) VALUES (" .
            getRoleID($roleNm) . ", " . $prvldg_id . ", '" .
            $dateStr . "', '4000-12-31 00:00:00', " . $uID . ", '" .
            $dateStr . "', " . $uID . ", '" . $dateStr . "')";
        $result = execUpdtInsSQL($sqlStr);
    }
}

function hasRoleEvrHdThsPrvlg($inp_role_id, $inp_prvldg_id)
{
    //Checks whether a given role 'system administrator' has a given priviledge
    $sqlStr = "SELECT role_id FROM sec.sec_roles_n_prvldgs WHERE ((prvldg_id = " .
        $inp_prvldg_id . ") AND (role_id = " . $inp_role_id .
        "))";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function getMdlGrpID($sub_grp_name, $ModuleName)
{
    $sqlStr = "SELECT table_id from sec.sec_module_sub_groups where (sub_group_name = '" .
        loc_db_escape_string($sub_grp_name) . "' AND module_id = " .
        getModuleID($ModuleName) . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getMdlGrpTblID(
    $sub_grp_name,
    $mdlID
) {
    $sqlStr = "SELECT table_id from sec.sec_module_sub_groups where (sub_group_name = '" .
        loc_db_escape_string($sub_grp_name) . "' AND module_id = " .
        $mdlID . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function checkNAssignReqrmnts(
    $ModuleName,
    $ModuleDesc,
    $ModuleAdtTbl,
    $SampleRole,
    $DefaultPrvldgs,
    $SubGrpNames,
    $MainTableNames,
    $KeyColumnNames
) {
    global $allMdlslovID;
    if ($allMdlslovID <= 0) {
        $allMdlslovID = getLovID("All Enabled Modules");
    }
    createSysLovsPssblVals1($ModuleName, $allMdlslovID);
    if (getEnbldPssblValID($ModuleName, $allMdlslovID) <= 0) {
        echo "<p style=\"font-size:12px;\">Skipped Module load for <b>$ModuleName ($SampleRole)!</b></p>";
        return;
    }
    if (getModuleID($ModuleName) == -1) {
        registerThsModule($ModuleName, $ModuleDesc, $ModuleAdtTbl);
    }
    $roleID = getRoleID($SampleRole);
    if ($roleID == -1) {
        createSampleRole($SampleRole);
        $roleID = getRoleID($SampleRole);
    }
    checkNCreatePrvldgs($DefaultPrvldgs, $ModuleName, $SampleRole);
    if ($SubGrpNames != null && $SubGrpNames != "") {
        checkNCreateSubGroups($ModuleName, $SubGrpNames, $MainTableNames, $KeyColumnNames);
    }
    $msg = "";
    $uID = getUserID("admin");
    if (doesUsrIDHvThsRoleID($uID, $roleID) == false) {
        asgnRoleToUser($uID, $roleID);
    }

    if ($ModuleName != "System Administration") {
        echo "<p style=\"font-size:12px;\">$msg Completed Module load for <b>$ModuleName ($SampleRole)!</b></p>";
    }
}

function asgnRoleToUser($uID, $roleID)
{
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO sec.sec_users_n_roles (user_id, role_id, valid_start_date, valid_end_date, created_by, 
creation_date, last_update_by, last_update_date) VALUES (" . $uID . ", " .
        $roleID . ", '" . $dateStr .
        "', '4000-12-31 00:00:00', " . $uID . ", '" . $dateStr . "', " . $uID . ", '" . $dateStr . "')";
    executeSQLNoParams($sqlStr);
}

function checkNCreatePrvldgs($brgtPrvldgs, $ModuleName, $roleNm)
{
    for ($i = 0; $i < count($brgtPrvldgs); $i++) {
        if (getPrvldgID($brgtPrvldgs[$i], $ModuleName) == -1) {
            createPrvldg($brgtPrvldgs[$i], $ModuleName);
        }
        if (hasRoleEvrHdThsPrvlg(getRoleID($roleNm), getPrvldgID($brgtPrvldgs[$i], $ModuleName)) == false) {
            asgnPrvlgToSmplRole(getPrvldgID($brgtPrvldgs[$i], $ModuleName), $roleNm);
        }
    }
}

function checkNCreateSubGroups($ModuleName, $brgtGrps, $brgtTbls, $brgtKeyCols)
{
    $mdlID = getModuleID($ModuleName);
    for ($i = 0; $i < count($brgtGrps); $i++) {
        if (getMdlGrpID($brgtGrps[$i], $ModuleName) == -1) {
            registerThsModulesSubgroups($brgtGrps[$i], $brgtTbls[$i], $brgtKeyCols[$i], $mdlID);
        } else {
        }
    }
}

$allMdlslovID = -1;

function loadMdlsNthrRolesNLovs()
{
    //ini_set('max_execution_time', 0); 
    global $sysLovs;
    global $sysLovsDesc;
    global $sysLovsDynQrys;
    global $pssblVals;
    global $allMdlslovID;
    $allMdlslovID = getLovID("All Enabled Modules");
    loadSysAdminMdl();
    loadGenStpMdl();
    loadGenericMdl();
    loadOrgStpMdl();
    loadAccntngMdl();
    loadIntPymntsMdl();
    loadPersonMdl();
    loadRptMdl();
    loadHospMdl();
    loadWkflMdl();
    //loadAlrtMdl();
    loadInvMdl();
    loadAcaMdl();
    loadAttnMdl();
    loadHotlMdl();
    loadMcfMdl();
    loadVMSMdl();
    loadAgntMdl();
    loadSelfMdl();
    //loadPSBMdl();
    loadProjsMdl();
    loadEvoteMdl();
    loadELearnMdl();
    loadHelpDskMdl();
    loadAstTrckrMdl();
    loadCcsMdl(); //Catholic Church Sacrament
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);

    $updtSQL = "UPDATE prs.prsn_names_nos 
        SET first_name='SYSTEM'
        WHERE local_id_no = 'RHO0002012';

  DELETE FROM sec.sec_audit_trail_tbls_to_enbl 
  WHERE module_id NOT IN (SELECT a.module_id
  FROM sec.sec_modules a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c
  WHERE b.value_list_id=c.value_list_id
  and c.value_list_name='All Enabled Modules'
  and b.pssbl_value=a.module_name
  and b.is_enabled='1');

   DELETE FROM sec.sec_module_sub_groups 
  WHERE module_id NOT IN (SELECT a.module_id
  FROM sec.sec_modules a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c
  WHERE b.value_list_id=c.value_list_id
  and c.value_list_name='All Enabled Modules'
  and b.pssbl_value=a.module_name
  and b.is_enabled='1');
  
   DELETE FROM sec.sec_prvldgs 
  WHERE module_id NOT IN (SELECT a.module_id
  FROM sec.sec_modules a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c
  WHERE b.value_list_id=c.value_list_id
  and c.value_list_name='All Enabled Modules'
  and b.pssbl_value=a.module_name
  and b.is_enabled='1');

  DELETE FROM sec.sec_roles_n_prvldgs WHERE prvldg_id NOT IN (SELECT prvldg_id FROM sec.sec_prvldgs);

  DELETE FROM sec.sec_roles a WHERE (SELECT count(1) FROM sec.sec_roles_n_prvldgs b WHERE a.role_id = b.role_id)<=0;

  DELETE FROM sec.sec_users_n_roles WHERE role_id NOT IN (SELECT role_id FROM sec.sec_roles);

  DELETE FROM wkf.wkf_apps
  WHERE source_module NOT IN (SELECT a.module_name
  FROM sec.sec_modules a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c
  WHERE b.value_list_id=c.value_list_id
  and c.value_list_name='All Enabled Modules'
  and b.pssbl_value=a.module_name
  and b.is_enabled='1');

  DELETE FROM wkf.wkf_actual_msgs_hdr WHERE app_id NOT IN (SELECT app_id
  FROM wkf.wkf_apps);

  DELETE FROM wkf.wkf_apps_actions WHERE app_id NOT IN (SELECT app_id
  FROM wkf.wkf_apps);
  
  DELETE FROM wkf.wkf_apps_n_hrchies WHERE app_id NOT IN (SELECT app_id
  FROM wkf.wkf_apps);
  
  DELETE FROM wkf.wkf_actual_msgs_routng WHERE msg_id NOT IN (SELECT msg_id FROM wkf.wkf_actual_msgs_hdr);
  
  DELETE FROM sec.sec_modules 
  WHERE module_id NOT IN (SELECT a.module_id
  FROM sec.sec_modules a, gst.gen_stp_lov_values b, gst.gen_stp_lov_names c
  WHERE b.value_list_id=c.value_list_id
  and c.value_list_name='All Enabled Modules'
  and b.pssbl_value=a.module_name
  and b.is_enabled='1');";
    executeSQLNoParams($updtSQL);

    echo "<p style=\"color:green;\">Click <a href=\"index.php\">here to RESTART Application!</a></p>";
}

function loadInvMdl()
{
    $DefaultPrvldgs = array(
        "View Inventory Manager",
        /* 1 */ "View Item List", "View Product Categories", "View Stores/Warehouses"
        /* 4 */, "View Receipts", "View Receipt Returns", "View Item Type Templates",
        /* 7 */ "View Item Balances",
        /* 8 */ "Add Items", "Update Items",
        /* 10 */ "Add Item Stores", "Update Item Stores", "Delete Item Stores",
        /* 13 */ "Add Product Category", "Update Product Category",
        /* 15 */ "Add Stores", "Update Stores",
        /* 17 */ "Add Store Users", "Update Store Users", "Delete Store Users",
        /* 20 */ "Add Store Shelves", "Delete Store Shelves",
        /* 22 */ "Add Receipt", "Delete Receipt",
        /* 24 */ "Add Receipt Return", "Delete Receipt Return",
        /* 26 */ "Add Item Template", "Update Item Template",
        /* 28 */ "Add Template Stores", "Update Template Stores",
        /* 30 */ "View GL Interface",
        /* 31 */ "View SQL", "View Record History", "Send To GL Interface Table",
        /* 34 */ "View Purchases", "View Sales/Item Issues", "View Sales Returns",
        /* 37 */ "View Payments Received",
        /* 38 */ "View Purchase Requisitions", "Add Purchase Requisitions", "Edit Purchase Requisitions", "Delete Purchase Requisitions",
        /* 42 */ "View Purchase Orders", "Add Purchase Orders", "Edit Purchase Orders", "Delete Purchase Orders",
        /* 46 */ "View Pro-Forma Invoices", "Add Pro-Forma Invoices", "Edit Pro-Forma Invoices", "Delete Pro-Forma Invoices",
        /* 50 */ "View Sales Orders", "Add Sales Orders", "Edit Sales Orders", "Delete Sales Orders",
        /* 54 */ "View Sales Invoices", "Add Sales Invoices", "Edit Sales Invoices", "Delete Sales Invoices",
        /* 58 */ "View Internal Item Requests", "Add Internal Item Requests", "Edit Internal Item Requests", "Delete Internal Item Requests",
        /* 62 */ "View Item Issues-Unbilled", "Add Item Issues-Unbilled", "Edit Item Issues-Unbilled", "Delete Item Issues-Unbilled",
        /* 66 */ "View Sales Returns", "Add Sales Return", "Edit Sales Return", "Delete Sales Return",
        /* 70 */ "Send GL Interface Records to GL", "Cancel Documents", "View only Self-Created Documents",
        /* 73 */ "View UOM", "Add UOM", "Edit UOM", "Delete UOM", "Make Payments", "Delete Product Category",
        /* 79 */ "View UOM Conversion", "Add UOM Conversion", "Edit UOM Conversion", "Delete UOM Conversion",
        /* 83 */ "View Drug Interactions", "Add Drug Interactions", "Edit Drug Interactions", "Delete Drug Interactions",
        /* 87 */ "Edit Receipt", "Edit Returns", "Edit Store Transfers", "Edit Adjustments",
        /* 91 */ "Clear Stock Balance", "Do Quick Receipt",
        /* 93 */ "View Item Production", "Add Item Production", "Edit Item Production", "Delete Item Production",
        /* 97 */ "Setup Production Processes", "Apply Adhoc Discounts",
        /* 99 */ "View Production Runs", "Add Production Runs", "Edit Production Runs", "Delete Production Runs",
        /* 103 */ "Can Edit Unit Price", "View only Branch-Related Documents", "Can View Cost Price"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Stores And Inventory Manager";
    $myDesc = "This module helps you to manage your organization's Inventory System!";
    $audit_tbl_name = "inv.inv_audit_trail_tbl";
    $smplRoleName = "Stores And Inventory Manager Administrator";
    createInvntryRqrdLOVs();
    createSCMRqrdLOVs();
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function createInvntryRqrdLOVs()
{
    $sysLovs = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Customer/Supplier Sites", "Users' Sales Stores", "Approved Pro-Forma Invoices",
        "Approved Sales Orders", "Approved Internal Item Requests",
        "Customers", "Approved Sales Invoices/Item Issues", "Customer Names for Reports",
        /* 19 */ "Supplier Names for Reports", "Allow Dues on Invoices", "All Business/Trade Partners",
        /* 22 */ "Production Process Runs", "Production Process Run Stages", "Production Process Classifications",
        /* 25 */ "Default POS Paper Size", "Default Document Notes", "Document Custom Print Process Names",
        /* 28 */ "All Sales Documents", "Production Cost Explanations",
        /* 30 */ "All Receivables Documents", "All Payables Documents", "Allow Inventory to be Costed", "Sample Sales Narrations",
        /* 34 */ "YMP Item Payment Plans", "YMP Payment Plans", "YMP Payment No Options", "YMP Marketers", "YMP Payment Options",
        /* 39 */ "YMP Payment Plan Amount Type", "Sales Stores"
    );
    $sysLovsDesc = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Customer/Supplier Sites", "Users' Sales Stores", "Approved Pro-Forma Invoices",
        "Approved Sales Orders", "Approved Internal Item Requests",
        "Customers", "Approved Sales Invoices/Item Issues", "Customer Names for Reports",
        /* 19 */ "Supplier Names for Reports", "Allow Dues on Invoices", "All Business/Trade Partners",
        "Production Process Runs",
        "Production Process Run Stages", "Production Process Classifications",
        "Default POS Paper Size", "Default Document Notes",
        "Document Custom Print Process Names",
        /* 28 */ "All Sales Documents", "Production Cost Explanations",
        /* 30 */ "All Receivables Documents", "All Payables Documents", "Allow Inventory to be Costed", "Sample Sales Narrations",
        /* 34 */ "YMP Item Payment Plans", "YMP Payment Plans", "YMP Payment No Options", "YMP Marketers", "YMP Payment Options",
        /* 39 */ "YMP Payment Plan Amount Type", "Sales Stores"
    );
    $sysLovsDynQrys = array(
        "", "",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EX' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'R' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "", "",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d, is_parent e from scm.scm_tax_codes where (itm_type = 'Tax' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d, is_parent e from scm.scm_tax_codes where (itm_type = 'Discount' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d, is_parent e from scm.scm_tax_codes where (itm_type = 'Extra Charge' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(y.prchs_doc_hdr_id,'999999999999999999999999999999')) a, y.purchase_doc_num b, '' c, y.org_id d, y.prchs_doc_hdr_id g " .
            "from scm.scm_prchs_docs_hdr y, scm.scm_prchs_docs_det z " .
            "where (y.purchase_doc_type = 'Purchase Requisition' " .
            "and y.approval_status = 'Approved' " .
            "and z.prchs_doc_hdr_id = y.prchs_doc_hdr_id and (z.quantity - z.rqstd_qty_ordrd)>0) order by y.prchs_doc_hdr_id DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Supplier%' and is_enabled='1') order by 2",
        "select distinct trim(to_char(cust_sup_site_id,'999999999999999999999999999999')) a, site_name b, '' c, cust_supplier_id d from scm.scm_cstmr_suplr_sites where (is_enabled='1') order by 2",
        "select distinct trim(to_char(y.subinv_id,'999999999999999999999999999999')) a, y.subinv_name b, '' c, y.org_id d, trim(to_char(z.user_id,'999999999999999999999999999999')) e from inv.inv_itm_subinventories y, inv.inv_user_subinventories z where y.subinv_id=z.subinv_id and y.allow_sales = '1' order by 2",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Pro-Forma Invoice' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Sales Order' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Internal Item Request' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Customer%' and is_enabled='1') order by 2",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where ((y.invc_type = 'Item Issue-Unbilled' or y.invc_type = 'Sales Invoice') " .
            "and (y.approval_status = 'Approved') " .
            "and (z.invc_hdr_id = y.invc_hdr_id) and ((z.doc_qty - z.qty_trnsctd_in_dest_doc)>0)) order by y.invc_hdr_id DESC",
        "select distinct cust_sup_name a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Customer%' and is_enabled='1') order by 2",
        "select distinct cust_sup_name a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Supplier%' and is_enabled='1') order by 2",
        "",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d, lnkd_prsn_id e, cust_or_sup f from scm.scm_cstmr_suplr where (is_enabled='1') order by 2",
        "select distinct '' || y.process_run_id a, y.batch_code_num b, '' c, z.org_id d, y.process_def_id e from scm.scm_process_run y, scm.scm_process_definition z where (z.process_def_id = y.process_def_id) order by 2",
        "select distinct z.stage_code_name a, z.stage_code_desc b, '' c, -1 d, y.process_run_id e from scm.scm_process_run_stages y, scm.scm_process_def_stages z where (z.stage_id = y.def_stage_id and y.process_run_id>0) order by 2",
        "", "", "", "",
        "select distinct ''||y.invc_hdr_id a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y " .
            "where (1=1) order by y.invc_hdr_id DESC", "",
        "select distinct ''||y.rcvbls_invc_hdr_id a, y.rcvbls_invc_number b, '' c, y.org_id d, y.rcvbls_invc_hdr_id g " .
            "from accb.accb_rcvbls_invc_hdr y " .
            "where (1=1) order by y.rcvbls_invc_hdr_id DESC",
        "select distinct ''||y.pybls_invc_hdr_id a, y.pybls_invc_number b, '' c, y.org_id d, y.pybls_invc_hdr_id g " .
            "from accb.accb_pybls_invc_hdr y " .
            "where (1=1) order by y.pybls_invc_hdr_id DESC", "", "",
        /* 34 */ "SELECT distinct trim(to_char(x.itm_pymnt_plan_id,'999999999999999999999999999999')) a, trim(plan_name) b, '' c, 
        org_id d, item_id e, no_of_pymnts f FROM inv.inv_itm_payment_plans x  order by b DESC",
        /* 35 */ "SELECT distinct trim(to_char(x.itm_pymnt_plan_id,'999999999999999999999999999999')) a, trim(plan_name) b, '' c, 
        org_id d, item_id e FROM inv.inv_itm_payment_plans x  order by b DESC",
        /* 36 */ "SELECT distinct trim(to_char(x.no_of_pymnts,'999999999999999999999999999999')) a, trim(x.no_of_pymnts||' Month(s)') b, '' c,  org_id d FROM inv.inv_itm_payment_plans x   order by b DESC",
        /* 37 */ "SELECT distinct trim(to_char(x.person_id,'999999999999999999999999999999')) a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, 
        org_id d FROM prs.prsn_names_nos x   order by b DESC", "", "",
        /* 40 */ "select distinct trim(to_char(subinv_id,'999999999999999999999999999999')) a, subinv_name b, '' c, org_id d from inv.inv_itm_subinventories where (enabled_flag = '1' AND allow_sales = '1') order by subinv_name"
    );
    $pssblVals = array(
        "4", "Retail Customer", "Retail Customer", "4", "Wholesale customer", "Wholesale customer",
        "4", "Individual", "Individual Person", "4", "Organisation", "Company/Organisation",
        "5", "Service Provider", "Service Provider", "5", "Goods Provider", "Goods Provider",
        "5", "Service and Goods Provider", "Service and Goods Provider", "5", "Consultant", "Consultant", "5", "Training Provider", "Training Provider", "20", "NO", "Allow Internal Payments on Invoices", "24", "Category 1", "Category 1 Production Process", "24", "Category 2", "Category 2 Production Process", "24", "Category 3", "Category 3 Production Process", "25", "80mm", "Large Width POS Paper", "25", "58mm", "Small Width POS Paper", "26", "Sales Invoice", "", "26", "Sales Invoice - Dues", "", "26", "Receivables Invoice", "", "26", "Internal Item Request", "", "26", "Item Issues", "", "26", "Payables Invoice", "", "26", "Restaurant Invoice", "", "26", "Check-Ins Invoice", "", "26", "Appointments Invoice", "", "26", "Events Invoice", "", "27", "Sales Invoice", "Sales Invoice", "27", "Sales Invoice - Dues", "Sales Invoice - No Qty & Unit Price", "27", "Receivables Invoice", "Receivables Invoice", "27", "Item Issues", "Item Issues-Unbilled", "27", "Internal Item Request", "Item Issues-Unbilled", "27", "Payables Invoice", "Payables Invoice", "27", "Petty Cash Voucher", "Petty Cash Voucher", "27", "Restaurant Invoice", "Sales Invoice", "27", "Check-Ins Invoice", "Sales Invoice", "27", "Appointments Invoice", "Sales Invoice", "27", "Events Invoice", "Sales Invoice", "27", "Pay Slip", "Customized Pay Slip (Sample 1)", "29", "Labour Costs", "Labour Costs", "29", "Rental Costs", "Rental Costs", "29", "Utility Costs", "Utility Costs", "32", "YES", "Allow Inventory to be Costed", "33", "Lay Away", "Lay Away", "33", "Outright Purchase", "Outright Purchase", "33", "Hire Purchase", "Hire Purchase", "38", "Cash", "Cash", "38", "Cheque", "Cheque", "38", "Post-Dated Cheque", "Post-Dated Cheque", "39", "Absolute", "Absolute", "39", "Percentage", "Percentage"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function createSCMRqrdLOVs()
{
    global $orgID;
    $sysLovs = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Customer/Supplier Sites", "Users' Sales Stores", "Approved Pro-Forma Invoices",
        "Approved Sales Orders", "Approved Internal Item Requests",
        "Customers", "Approved Sales Invoices/Item Issues", "Customer Names for Reports",
        "Supplier Names for Reports", "Non-WHT Tax Codes", "WHT Tax Codes", "Approved Item Receipts",
        "Approved Purchase Orders", "Sales Invoice Classifications", "All Other Sales/Inventory Setups"
    );
    $sysLovsDesc = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Customer/Supplier Sites", "Users' Sales Stores", "Approved Pro-Forma Invoices",
        "Approved Sales Orders", "Approved Internal Item Requests",
        "Customers", "Approved Sales Invoices/Item Issues", "Customer Names for Reports",
        "Supplier Names for Reports", "Non-WHT Tax Codes", "WHT Tax Codes", "Approved Item Receipts",
        "Approved Purchase Orders", "Sales Invoice Classifications", "All Other Sales/Inventory Setups"
    );
    $sysLovsDynQrys = array(
        "", "",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EX' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1') order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'R' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1') order by accnt_num",
        "", "",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Tax' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Discount' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Extra Charge' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(y.prchs_doc_hdr_id,'999999999999999999999999999999')) a, y.purchase_doc_num b, '' c, y.org_id d, y.prchs_doc_hdr_id g " .
            "from scm.scm_prchs_docs_hdr y, scm.scm_prchs_docs_det z " .
            "where (y.purchase_doc_type = 'Purchase Requisition' " .
            "and y.approval_status = 'Approved' " .
            "and z.prchs_doc_hdr_id = y.prchs_doc_hdr_id and (z.quantity - z.rqstd_qty_ordrd)>0) order by y.prchs_doc_hdr_id DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup = 'Supplier') order by 2",
        "select distinct trim(to_char(cust_sup_site_id,'999999999999999999999999999999')) a, site_name b, '' c, cust_supplier_id d from scm.scm_cstmr_suplr_sites order by 2",
        "select distinct trim(to_char(y.subinv_id,'999999999999999999999999999999')) a, y.subinv_name b, '' c, y.org_id d, trim(to_char(z.user_id,'999999999999999999999999999999')) e from inv.inv_itm_subinventories y, inv.inv_user_subinventories z where y.subinv_id=z.subinv_id and y.allow_sales = '1' order by 2",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Pro-Forma Invoice' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Sales Order' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Internal Item Request' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Customer%') order by 2",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where ((y.invc_type = 'Item Issue-Unbilled' or y.invc_type = 'Sales Invoice') " .
            "and (y.approval_status = 'Approved') " .
            "and (z.invc_hdr_id = y.invc_hdr_id) and ((z.doc_qty - z.qty_trnsctd_in_dest_doc)>0)) order by y.invc_hdr_id DESC",
        "select distinct cust_sup_name a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Customer%') order by 2",
        "select distinct cust_sup_name a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Supplier%') order by 2",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Tax' and is_enabled = '1' and is_withldng_tax='0') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Tax' and is_enabled = '1' and is_withldng_tax='1') order by code_name",
        "SELECT DISTINCT '' || y.rcpt_id a, coalesce(y.rcpt_number, '' || y.rcpt_id) ||' ['|| y.description || ']' b, '' c, y.org_id d, y.rcpt_id g FROM inv.inv_consgmt_rcpt_hdr y, inv.inv_consgmt_rcpt_det z WHERE ((y.approval_status = 'Received') AND (z.rcpt_id = y.rcpt_id) AND ((z.quantity_rcvd - coalesce(z.qty_rtrnd, 0)) > 0)) ORDER BY y.rcpt_id DESC",
        "select distinct ''||y.prchs_doc_hdr_id a, y.purchase_doc_num ||' ['|| y.comments_desc || ']' b, '' c, y.org_id d, y.prchs_doc_hdr_id g " .
            "from scm.scm_prchs_docs_hdr y, scm.scm_prchs_docs_det z " .
            "where (y.purchase_doc_type = 'Purchase Order' " .
            "and y.approval_status = 'Approved' " .
            "and z.prchs_doc_hdr_id = y.prchs_doc_hdr_id and (z.quantity - z.qty_rcvd)>0) order by y.prchs_doc_hdr_id DESC", "", ""
    );
    $pssblVals = array(
        "4", "Retail Customer", "Retail Customer", "4", "Wholesale customer", "Wholesale customer",
        "4", "Individual", "Individual Person", "4", "Organisation", "Company/Organisation",
        "5", "Service Provider", "Service Provider", "5", "Goods Provider", "Goods Provider",
        "5", "Service and Goods Provider", "Service and Goods Provider", "5", "Consultant", "Consultant", "5", "Training Provider", "Training Provider",
        "24", "Standard", "Standard", "24", "Layaway", "Layaway",
        "24", "Consumer Finance", "Consumer Finance",
        "24", "Hire Purchase", "Hire Purchase",
        "25", "Html Invoice Print File Name", "htm_rpts/invoice_rpt.php",
        "25", "Html POS Receipt File Name", "htm_rpts/pos_rpt.php"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);

    $sysLovs1 = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Supplier Sites",
        "Shelves", "Categories", "Stores", "Item Templates", "Purchase Orders", "Items Stores",
        "Consignment Conditions",
        "Receipt Return Reasons", "Unit Of Measures", "Store Shelves", "Inventory Items"
    );
    $sysLovsDesc1 = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Supplier Sites",
        "Shelves", "Categories", "Stores", "Item Templates", "Purchase Orders", "Items Stores",
        "Consignment Conditions",
        "Receipt Return Reasons", "Unit Of Measures", "Store Shelves", "Inventory Items"
    );
    $sysLovsDynQrys1 = array(
        "", "",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EX' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1') order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'R' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1') order by accnt_num",
        "", "",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Tax' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Discount' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d from scm.scm_tax_codes where (itm_type = 'Extra Charge' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(prchs_doc_hdr_id,'999999999999999999999999999999')) a, purchase_doc_num b, '' c, org_id d from scm.scm_prchs_docs_hdr where (purchase_doc_type = 'Purchase Requisition' and approval_status = 'Approved') order by purchase_doc_num DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Supplier%') order by 2",
        "select distinct trim(to_char(cust_sup_site_id,'999999999999999999999999999999')) a, site_name b, '' c, cust_supplier_id d from scm.scm_cstmr_suplr_sites order by 2",
        "",
        "select distinct trim(to_char(cat_id,'999999999999999999999999999999')) a, cat_name b, '' c, org_id d from inv.inv_product_categories where (enabled_flag = '1') order by cat_name",
        "select distinct trim(to_char(subinv_id,'999999999999999999999999999999')) a, subinv_name b, '' c, org_id d from inv.inv_itm_subinventories where (enabled_flag = '1') order by subinv_name",
        "select distinct trim(to_char(item_type_id,'999999999999999999999999999999')) a, item_type_name b, '' c, org_id d from inv.inv_itm_type_templates where (is_tmplt_enabled_flag = '1') order by item_type_name",
        "select distinct trim(to_char(prchs_doc_hdr_id,'999999999999999999999999999999')) a, purchase_doc_num b, '' c, org_id d from scm.scm_prchs_docs_hdr where approval_status = 'Approved' order by purchase_doc_num",
        "select distinct trim(to_char(y.subinv_id,'999999999999999999999999999999')) a, y.subinv_name b, '' c, y.org_id d, trim(to_char(z.itm_id,'999999999999999999999999999999')) e from inv.inv_itm_subinventories y, inv.inv_stock z " .
            " where y.subinv_id = z.subinv_id and to_date(z.start_date,'YYYY-MM-DD') <= now()::Date and (to_date(z.end_date,'YYYY-MM-DD') >= now()::Date or end_date = '')  order by 2",
        "", "",
        "select distinct trim(to_char(uom_id,'999999999999999999999999999999')) a, uom_name b, '' c, org_id d from inv.unit_of_measure where (enabled_flag = '1') order by uom_name",
        "select distinct trim(to_char(y.shelf_id,'999999999999999999999999999999')) a, (SELECT pssbl_value ||' ('||pssbl_value_desc||') ' FROM gst.gen_stp_lov_values " .
            "WHERE pssbl_value_id = y.shelf_id) b, '' c, store_id d from inv.inv_shelf y order by 1",
        "SELECT distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_desc || '(' || item_code || ')' b, '' c, org_id d FROM inv.inv_itm_list order by 2"
    );

    $pssblVals1 = array(
        "4", "Retail Customer", "Retail Customer", "4", "Wholesale customer", "Wholesale customer",
        "4", "Individual", "Individual Person", "4", "Organisation", "Company/Organisation",
        "5", "Service Provider", "Service Provider", "5", "Goods Provider", "Goods Provider",
        "5", "Service and Goods Provider", "Service and Goods Provider", "5", "Consultant", "Consultant", "5", "Training Provider", "Training Provider",
        "12", "Shelf 1A", "First Floor shelf A", "12", "Shelf 1B", "First Floor shelf B",
        "12", "Shelf 1C", "First Floor shelf C", "12", "Shelf 2A", "Second Floor shelf A",
        "12", "Shelf 2B", "Second Floor shelf B", "12", "Shelf 2C", "Second Floor shelf C",
        "12", "Shelf 3A", "Third Floor shelf A", "12", "Shelf 3B", "Third Floor shelf B", "12", "Shelf 3C", "Third Floor shelf C", "18", "Excellent", "In Execellent Condition", "18", "Very Good", "In Very Good Condition", "18", "Good", "In Good Condition", "18", "Bad", "In Poor Condition", "18", "Defective", "Defective", "19", "Expired", "Expired", "19", "Defective", "Defective", "19", "Malfunctioning", "Malfunctioning", "19", "Wrong Receipt", "Wrong Receipt", "19", "Over Receipt", "Over Receipt"
    );

    createSysLovs($sysLovs1, $sysLovsDesc1, $sysLovsDynQrys1);
    createSysLovsPssblVals($pssblVals1, $sysLovs1);
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET cogs_acct_id=-1 WHERE cogs_acct_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET inv_asset_acct_id=-1 WHERE inv_asset_acct_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET sales_rev_accnt_id=-1 WHERE sales_rev_accnt_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET sales_ret_accnt_id=-1 WHERE sales_ret_accnt_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET purch_ret_accnt_id=-1 WHERE purch_ret_accnt_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET expense_accnt_id=-1 WHERE expense_accnt_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET base_uom_id=-1 WHERE base_uom_id IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET tmplt_id=-1 WHERE tmplt_id IS NULL");
    $fnccurid = getOrgFuncCurID($orgID);
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET value_price_crncy_id=" . $fnccurid . " WHERE value_price_crncy_id IS NULL or value_price_crncy_id<=0");
    execUpdtInsSQL("UPDATE inv.inv_itm_list SET auto_dflt_in_vms_trns='0' WHERE auto_dflt_in_vms_trns IS NULL");
    execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_rtns_hdr a SET approval_status='Returned' WHERE coalesce(a.approval_status, 'X') != 'Returned' AND (SELECT count(1) FROM inv.inv_consgmt_rcpt_rtns_det z WHERE z.rtns_hdr_id = a.rcpt_rtns_id and z.qty_rtnd>0) >= 1");
    execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_hdr a SET approval_status='Received' WHERE coalesce(a.approval_status, 'X') != 'Received' AND (SELECT count(1) FROM inv.inv_consgmt_rcpt_det z WHERE z.rcpt_id = a.rcpt_id and z.quantity_rcvd>0) >= 1");
    execUpdtInsSQL("SELECT setval('inv.inv_stock_rcpt_hdr_rcpt_id_seq', (SELECT rcpt_id
                                                     FROM inv.inv_consgmt_rcpt_hdr
                                                     ORDER BY rcpt_id DESC
                                                     LIMIT 1 OFFSET 0), TRUE)");

    execUpdtInsSQL("SELECT setval('inv.inv_itm_rcpt_rtns_rcpt_rtns_id_seq', (SELECT rcpt_rtns_id
                                                         FROM inv.inv_consgmt_rcpt_rtns_hdr
                                                         ORDER BY rcpt_rtns_id DESC
                                                         LIMIT 1 OFFSET 0), TRUE)");

    execUpdtInsSQL("SELECT setval('inv.inv_itm_subinv_transfer_hdr_transfer_id_seq', (SELECT transfer_hdr_id
                                                                  FROM inv.inv_stock_transfer_hdr
                                                                  ORDER BY transfer_hdr_id DESC
                                                                  LIMIT 1 OFFSET 0), TRUE)");
    execUpdtInsSQL("UPDATE scm.scm_prchs_docs_hdr SET branch_id=pasn.get_prsn_siteid(sec.get_usr_prsn_id(created_by)) WHERE branch_id<=0");
    execUpdtInsSQL("UPDATE scm.scm_sales_invc_hdr SET branch_id=pasn.get_prsn_siteid(sec.get_usr_prsn_id(created_by)) WHERE branch_id<=0");
}

function loadWkflMdl()
{
    //For Workfl0w
    $DefaultPrvldgs = array(
        "View Workflow Manager", "View Workflow Apps",
        /* 2 */ "View Workflow Hierarchies", "View Approver Groups",
        /* 4 */ "View Workflow Notifications", "View Record History", "View SQL",
        /* 7 */ "Add Workflow Apps", "Edit Workflow Apps", "Delete Workflow Apps",
        /* 10 */ "Add Workflow Hierarchies", "Edit Workflow Hierarchies", "Delete Workflow Hierarchies",
        /* 13 */ "Add Approver Groups", "Edit Approver Groups", "Delete Approver Groups",
        /* 16 */ "Administer Notifications", "Administer Workflow Setups"
    );
    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Workflow Manager";
    $myDesc = "This module helps you to configure the application's workflow system!";
    $audit_tbl_name = "wkf.wkf_audit_trail_tbl";
    $smplRoleName = "Workflow Manager Administrator";
    createWkfRqrdLOVs();
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function createWkfHrchy2($hrchyNm, $desc, $hrchyTyp, $sqlStmnt, $isEnbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();

    $insSQL = "INSERT INTO wkf.wkf_hierarchy_hdr(
             hierarchy_name, description, is_enabled, created_by, 
            creation_date, last_update_by, last_update_date, hierchy_type, 
            sql_select_stmnt) " .
        "VALUES ('" . loc_db_escape_string($hrchyNm)
        . "', '" . loc_db_escape_string($desc)
        . "', '" . loc_db_escape_string($isEnbld)
        . "'," . $usrID . ", '" . $dateStr
        . "', " . $usrID . ", '" . $dateStr
        . "', '" . loc_db_escape_string($hrchyTyp)
        . "', '" . loc_db_escape_string($sqlStmnt) . "')";

    execUpdtInsSQL($insSQL);
}

function updateWkfHrchy2($hrchyID, $hrchyNm, $desc, $hrchyTyp, $sqlStmnt, $isEnbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE wkf.wkf_hierarchy_hdr SET 
            hierarchy_name='" . loc_db_escape_string($hrchyNm)
        . "', description='" . loc_db_escape_string($desc)
        . "', last_update_by=$usrID, last_update_date='$dateStr', 
                hierchy_type='" . loc_db_escape_string($hrchyTyp)
        . "',  sql_select_stmnt='" . loc_db_escape_string($sqlStmnt) . "', 
            is_enabled='" . loc_db_escape_string($isEnbld) .
        "' WHERE hierarchy_id = " . $hrchyID;
    execUpdtInsSQL($insSQL);
}

function createWkfRqrdLOVs()
{

    $sysLovs = array("Hierarchy Names", "Workflow Apps", "Approver Groups");
    $sysLovsDesc = array("Hierarchy Names", "Workflow Apps", "Approver Groups");
    $sysLovsDynQrys = array(
        "select distinct trim(to_char(hierarchy_id,'999999999999999999999999999999')) a, hierarchy_name b, '' c from wkf.wkf_hierarchy_hdr order by hierarchy_name",
        "select distinct trim(to_char(app_id,'999999999999999999999999999999')) a, app_name b, '' c from wkf.wkf_apps order by app_name",
        "select distinct ''||apprvr_group_id a, group_name b, '' c from wkf.wkf_apprvr_groups order by group_name"
    );
    //$pssblVals = array("","","");

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    //createSysLovsPssblVals($pssblVals, $sysLovs);
    //
    //Workflow Message Types
    //1. Informational
    //2. Approval Document
    //3. Information Request
    //4. Working Document
    //5. Login App
    $appID = getAppID('Login', 'System Administration');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Login', 'System Administration', 'Login Welcome Messages');
        $appID = getAppID('Login', 'System Administration');
    } else {
        updateWkfApp($appID, 'Login', 'System Administration', 'Login Welcome Messages');
    }

    $actionNm = array("Acknowledge"); //, "Test Open", "Test Reject", "Test Re-assign", "Test Request for Information", "Test Close", "Test Respond"
    $desc = array("User acknowledges receipt of the Message"); //, "Test Action", "Test Action", "Test Action", "Test Action", "Test Action", "Test Action"
    $sqlStmnt = array("select wkf.action_sql_for_login({:routing_id},{:userID},'{:actToPrfm}');"); //, "", "", "", "", "", ""
    $exctbl = array(""); //, "", "", "", "", "", ""
    $webURL = array("grp=1&typ=10&q=act&RoutingID={:wkfRtngID}&actyp={:wkfAction}");
    $isdiag = array("1"); //, "0", "1", "1", "1", "1", "1"
    $isadmnonly = array("0");

    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //System Inbox
    $appID = getAppID('System Inbox', 'System Administration');
    if ($appID <= 0) {
        createWkfApp('System Inbox', 'System Administration', 'Internal Messages');
        $appID = getAppID('System Inbox', 'System Administration');
    } else {
        updateWkfApp($appID, 'System Inbox', 'System Administration', 'Internal Messages');
    }

    $actionNm = array("Acknowledge"); //, "Test Open", "Test Reject", "Test Re-assign", "Test Request for Information", "Test Close", "Test Respond"
    $desc = array("User acknowledges receipt of the Message"); //, "Test Action", "Test Action", "Test Action", "Test Action", "Test Action", "Test Action"
    $sqlStmnt = array("select wkf.action_sql_for_sysinbx({:routing_id},{:userID},'{:actToPrfm}');"); //, "", "", "", "", "", ""
    $exctbl = array(""); //, "", "", "", "", "", ""
    $webURL = array("grp=1&typ=10&q=act&RoutingID={:wkfRtngID}&actyp={:wkfAction}");
    $isdiag = array("1"); //, "0", "1", "1", "1", "1", "1"
    $isadmnonly = array("0");

    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Clinic App
    $appID = getAppID('Clinical Appointments', 'Clinic/Hospital');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Clinical Appointments', 'Clinic/Hospital', 'Messages related to Clinic/Hospital Appointments');
        $appID = getAppID('Clinical Appointments', 'Clinic/Hospital');
    } else {
        updateWkfApp($appID, 'Clinical Appointments', 'Clinic/Hospital', 'Messages related to Clinic/Hospital Appointments');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Close", "Respond",
        "Acknowledge"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can close a working document to indicate all work on it has been done!",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "index1.php?ajx=1&grp=14&typ=1&pg=2&q=Clinic/Hospital&vwtyp=0&actyp=0&wkfRtngID={:wkfRtngID}",
        "ajx=1&grp=14&typ=1&pg=2&q=Clinic/Hospital&vwtyp=115&actyp=0&slctdRtngID={:wkfRtngID}",
        "ajx=1&grp=14&typ=1&pg=2&q=Clinic/Hospital&vwtyp=117&actyp=0&slctdRtngID={:wkfRtngID}",
        "index.php",
        "index.php",
        "index.php"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }

    //Personal Records Change
    $appID = getAppID('Personal Records Change', 'Basic Person Data');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Personal Records Change', 'Basic Person Data', 'Messages related to Basic Person Data Change Requests');
        $appID = getAppID('Personal Records Change', 'Basic Person Data');
    } else {
        updateWkfApp($appID, 'Personal Records Change', 'Basic Person Data', 'Messages related to Basic Person Data Change Requests');
    }
    $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Personal Records Change Hierarchy");
    if ($hrchyid <= 0) {
        createWkfHrchy2("Personal Records Change Hierarchy", "Personal Records Change Hierarchy", "Manual", "", "1");
        $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Personal Records Change Hierarchy");
    }/* else {
      updateWkfHrchy2($hrchyid, "Personal Records Change Hierarchy", "Personal Records Change Hierarchy", "Manual", "", "1");
      } */
    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User approves submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=8&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=8&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=8&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=8&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=8&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=8&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Leave Requests
    $appID = getAppID('Leave Requests', 'Basic Person Data');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Leave Requests', 'Basic Person Data', 'Messages related to Basic Person Leave Requests');
        $appID = getAppID('Leave Requests', 'Basic Person Data');
    } else {
        updateWkfApp($appID, 'Leave Requests', 'Basic Person Data', 'Messages related to Basic Person Leave Requests');
    }
    $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Leave Requests Hierarchy");
    if ($hrchyid <= 0) {
        createWkfHrchy2("Leave Requests Hierarchy", "Leave Requests Hierarchy", "Manual", "", "1");
        $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Leave Requests Hierarchy");
    } /* else {
      updateWkfHrchy2($hrchyid, "Leave Requests Hierarchy", "Leave Requests Hierarchy", "Manual", "", "1");
      } */
    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User approves submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=8&typ=1&pg=4&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=8&typ=1&pg=4&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=8&typ=1&pg=4&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=8&typ=1&pg=4&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=8&typ=1&pg=4&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=8&typ=1&pg=4&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Internal Pay Payment Requests
    $appID = getAppID('Internal Pay Payment Requests', 'Internal Payments');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Internal Pay Payment Requests', 'Internal Payments', 'Messages related to Internal Payments Payment Requests');
        $appID = getAppID('Internal Pay Payment Requests', 'Internal Payments');
    } else {
        updateWkfApp($appID, 'Internal Pay Payment Requests', 'Internal Payments', 'Messages related to Internal Payments Payment Requests');
    }
    $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Internal Pay Payment Requests Hierarchy");
    if ($hrchyid <= 0) {
        createWkfHrchy2("Internal Pay Payment Requests Hierarchy", "Internal Pay Payment Requests Hierarchy", "Manual", "", "1");
        $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Internal Pay Payment Requests Hierarchy");
    }
    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User approves submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Internal Pay Loan Requests
    $appID = getAppID('Internal Pay Loan Requests', 'Internal Payments');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Internal Pay Loan Requests', 'Internal Payments', 'Messages related to Internal Payments Loan Requests');
        $appID = getAppID('Internal Pay Loan Requests', 'Internal Payments');
    } else {
        updateWkfApp($appID, 'Internal Pay Loan Requests', 'Internal Payments', 'Messages related to Internal Payments Loan Requests');
    }
    $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Internal Pay Loan Requests Hierarchy");
    if ($hrchyid <= 0) {
        createWkfHrchy2("Internal Pay Loan Requests Hierarchy", "Internal Pay Loan Requests Hierarchy", "Manual", "", "1");
        $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Internal Pay Loan Requests Hierarchy");
    }
    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User approves submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Internal Pay Settlement Requests
    $appID = getAppID('Internal Pay Settlement Requests', 'Internal Payments');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('Internal Pay Settlement Requests', 'Internal Payments', 'Messages related to Internal Payments Loan Settlement Requests');
        $appID = getAppID('Internal Pay Settlement Requests', 'Internal Payments');
    } else {
        updateWkfApp($appID, 'Internal Pay Settlement Requests', 'Internal Payments', 'Messages related to Internal Payments Loan Settlement Requests');
    }
    $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Internal Pay Settlement Requests Hierarchy");
    if ($hrchyid <= 0) {
        createWkfHrchy2("Internal Pay Settlement Requests Hierarchy", "Internal Pay Settlement Requests Hierarchy", "Manual", "", "1");
        $hrchyid = (float) getGnrlRecID2("wkf.wkf_hierarchy_hdr", "hierarchy_name", "hierarchy_id", "Internal Pay Settlement Requests Hierarchy");
    }
    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User approves submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=7&typ=1&pg=13&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Payment System Banking
    $appID = getAppID('PSB Forms Submission', 'Payment Systems Banking');
    //$prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($appID <= 0) {
        createWkfApp('PSB Forms Submission', 'Payment Systems Banking', 'Messages related to PSB Forms Submitted');
        $appID = getAppID('PSB Forms Submission', 'Payment Systems Banking');
    } else {
        updateWkfApp($appID, 'PSB Forms Submission', 'Payment Systems Banking', 'Messages related to PSB Forms Submitted');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User approves submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=20&typ=1&q=SUBMIT&RoutingID={:wkfRtngID}&actyp={:wkfAction}",
        "grp=20&typ=1&q=SUBMIT&RoutingID={:wkfRtngID}&actyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=20&typ=1&q=SUBMIT&RoutingID={:wkfRtngID}&actyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=20&typ=1&q=SUBMIT&RoutingID={:wkfRtngID}&actyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=20&typ=1&q=SUBMIT&RoutingID={:wkfRtngID}&actyp={:wkfAction}",
        "grp=20&typ=1&q=SUBMIT&RoutingID={:wkfRtngID}&actyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }

    //Vault Management System
    $appID = getAppID('Vault Transactions', 'Vault Management');
    if ($appID <= 0) {
        createWkfApp('Vault Transactions', 'Vault Management', 'Messages related to Vault Management Transactions');
        $appID = getAppID('Vault Transactions', 'Vault Management');
    } else {
        updateWkfApp($appID, 'Vault Transactions', 'Vault Management', 'Messages related to Vault Management Transactions');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Authorize"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User Approves/Authorize submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=25&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=25&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=25&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=25&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=25&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=25&typ=1&pg=2&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Banking System
    $appID = getAppID('Banking Transactions', 'Banking');
    if ($appID <= 0) {
        createWkfApp('Banking Transactions', 'Banking', 'Messages related to Banking Transactions');
        $appID = getAppID('Banking Transactions', 'Banking');
    } else {
        updateWkfApp($appID, 'Banking Transactions', 'Banking', 'Messages related to Banking Transactions');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Authorize"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User Approves/Authorize submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=17&typ=1&pg=1042&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1042&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1042&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1042&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1042&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1042&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Bulk Customer Transactions
    $appID = getAppID('Bulk/Batch Transactions', 'Banking');
    if ($appID <= 0) {
        createWkfApp('Bulk/Batch Transactions', 'Banking', 'Messages related to Banking Transactions');
        $appID = getAppID('Bulk/Batch Transactions', 'Banking');
    } else {
        updateWkfApp($appID, 'Bulk/Batch Transactions', 'Banking', 'Messages related to Banking Transactions');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Authorize"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User Approves/Authorize submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=17&typ=1&pg=1045&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1045&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1045&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1045&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1045&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1045&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Loan Applications
    $appID = getAppID('Loan Applications', 'Banking');
    if ($appID <= 0) {
        createWkfApp('Loan Applications', 'Banking', 'Messages related to Loan Applications');
        $appID = getAppID('Loan Applications', 'Banking');
    } else {
        updateWkfApp($appID, 'Loan Applications', 'Banking', 'Messages related to Loan Applications');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Approve"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User Approves/Authorize submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=17&typ=1&pg=1040&q=WORKFLOW&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1040&q=WORKFLOW&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1040&q=WORKFLOW&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1040&q=WORKFLOW&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1040&q=WORKFLOW&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1040&q=WORKFLOW&actyp=41&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Account Transfers
    $appID = getAppID('Transfer Transactions', 'Banking');
    if ($appID <= 0) {
        createWkfApp('Transfer Transactions', 'Banking', 'Messages related to Account Transfer Transactions');
        $appID = getAppID('Transfer Transactions', 'Banking');
    } else {
        updateWkfApp($appID, 'Transfer Transactions', 'Banking', 'Messages related to Account Transfer Transactions');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Authorize"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User Approves/Authorize submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=17&typ=1&pg=1043&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1043&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1043&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1043&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1043&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1043&q=FINALIZE_CORE_BNK&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
    //Loan Transactions
    $appID = getAppID('Loan Transactions', 'Banking');
    if ($appID <= 0) {
        createWkfApp('Loan Transactions', 'Banking', 'Messages related to Loan Transactions');
        $appID = getAppID('Loan Transactions', 'Banking');
    } else {
        updateWkfApp($appID, 'Loan Transactions', 'Banking', 'Messages related to Loan Transactions');
    }

    $actionNm = array(
        "Open", "Reject", "Request for Information", "Respond", "Acknowledge",
        "Authorize"
    );
    $desc = array(
        "User can Open the Working Document to work on it",
        "User can Reject i.e. Refuse to work on the Document Assigning a Reason",
        "User can Request for additional Information on the Document before working on it",
        "User can Respond to an Information Request Message",
        "User acknowledges receipt of the Message",
        "User Approves/Authorize submitted request"
    );
    $sqlStmnt = array("", "", "", "", "", "");
    $exctbl = array("", "", "", "", "", "");
    $webURL = array(
        "grp=17&typ=1&pg=1060&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1060&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1060&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1060&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}&actReason={:wkfActReason}&toPrsLocID={:wkfToPrsLocID}",
        "grp=17&typ=1&pg=1060&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}",
        "grp=17&typ=1&pg=1060&q=UPDATE&actyp=40&RoutingID={:wkfRtngID}&actiontyp={:wkfAction}"
    );
    $isdiag = array("0", "1", "1", "1", "1", "1");
    $isadmnonly = array("0", "0", "0", "0", "0", "0");
    for ($i = 0; $i < count($actionNm); $i++) {
        $appActionID = getGnrlRecIDExtr("wkf.wkf_apps_actions", "action_performed_nm", "app_id", "action_sql_id", $actionNm[$i], $appID);
        if ($appActionID <= 0) {
            createWkfAppAction($actionNm[$i], $sqlStmnt[$i], $appID, $exctbl[$i], $webURL[$i], $isdiag[$i], $desc[$i], $isadmnonly[$i]);
        } else {
            updateWkfAppAction(
                $appActionID,
                $actionNm[$i],
                $sqlStmnt[$i],
                $appID,
                $exctbl[$i],
                $webURL[$i],
                $isdiag[$i],
                $desc[$i],
                $isadmnonly[$i]
            );
        }
    }
}

function loadAlrtMdl()
{
    //For Accounting
    $DefaultPrvldgs = array(
        "View Alerts Manager", "View Alert Messages Sent",
        "View Record History", "View SQL"
    );

    $subGrpNames = ""; //, "Accounting Transactions"
    $mainTableNames = ""; //, "accb.accb_trnsctn_details"
    $keyColumnNames = ""; //, "transctn_id" 
    $myName = "Alerts Manager";
    $myDesc = "This module helps you to configure the application's Alert System!";
    $audit_tbl_name = "alrt.alrt_audit_trail_tbl";
    $smplRoleName = "Alerts Manager Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function loadHelpDskMdl()
{
    //For Accounting
    $DefaultPrvldgs = array(
        "View Help Desk",
        /* 1 */ "View Help Desk Dashboard",
        /* 2 */ "View My Request Tickets", "View All Request Tickets",
        /* 4 */ "View SQL", "View Record History",
        /* 6 */ "Add Request Tickets", "Edit Request Tickets", "Delete Request Tickets",
        /* 9 */ "Add Tickets for Others", "Edit Tickets for Others", "Delete Tickets for Others",
        /* 12 */ "Add Request Categories", "Edit Request Categories", "Delete Request Categories"
    );
    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Service Desk Manager";
    $myDesc = "This module helps you to manage your support tickets and I.T Service requests!";
    $audit_tbl_name = "hlpd.hlpd_audit_trail_tbl";
    $smplRoleName = "Service Desk Manager Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    $sysLovs2 = array("System Ticket Classifications");
    $sysLovsDesc2 = array("System Ticket Classifications");
    $sysLovsDynQrys2 = array("");
    $pssblVals2 = array(
        "0", "HARDWARE_PROBLEM", "Computer/Electronic Gadget Hardware Usage Problem",
        "0", "SOFTWARE_PROBLEM", "Software/Application Usage Problem",
        "0", "ITEM_REQUEST", "Request to be given an Item for Use",
        "0", "CHEQUE_BOOK_RQST", "REQUEST FOR NEW CHEQUE BOOKS",
        "0", "STANDING_ORDER_REQUEST", "Standing Order Requests",
        "0", "PASSWORD_PROBLEM", "User Account Password Problem",
        "0", "NETWORK_PROBLEM", "Problem with the Network Infrastructure"
    );
    createSysLovs($sysLovs2, $sysLovsDesc2, $sysLovsDynQrys2);
    createSysLovsPssblVals($pssblVals2, $sysLovs2);
}

function loadSysAdminMdl()
{
    //For Accounting
    global $sysLovs, $sysLovsDesc, $sysLovsDynQrys;

    $DefaultPrvldgs = array(
        "View System Administration", "View Users & their Roles",
        /* 2 */ "View Roles & their Priviledges", "View Registered Modules & their Priviledges",
        /* 4 */ "View Security Policies", "View Server Settings", "View User Logins",
        /* 7 */ "View Audit Trail Tables", "Add New Users & their Roles", "Edit Users & their Roles",
        /* 10 */ "Add New Roles & their Priviledges", "Edit Roles & their Priviledges",
        /* 12 */ "Add New Security Policies", "Edit Security Policies", "Add New Server Settings",
        /* 15 */ "Edit Server Settings", "Set manual password for users",
        /* 17 */ "Send System Generated Passwords to User Mails",
        /* 18 */ "View SQL", "View Record History", "Add/Edit Extra Info Labels", "Delete Extra Info Labels",
        /* 22 */ "Add Notices", "Edit Notices", "Delete Notices", "View Notices Admin"
    );

    $subGrpNames = ""; //, "Accounting Transactions"
    $mainTableNames = ""; //, "accb.accb_trnsctn_details"
    $keyColumnNames = ""; //, "transctn_id" 
    $myName = "System Administration";
    $myDesc = "This module helps you to administer all the security features of this software!";
    $audit_tbl_name = "sec.sec_audit_trail_tbl";
    $smplRoleName = "System Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    $sysLovs2 = array("Notice Classifications");
    $sysLovsDesc2 = array("Notice Classifications");
    $sysLovsDynQrys2 = array("");
    $pssblVals2 = array("0", "System Notifications", "System Notifications");
    createSysLovs($sysLovs2, $sysLovsDesc2, $sysLovsDynQrys2);
    createSysLovsPssblVals($pssblVals2, $sysLovs2);
}

function loadAccntngMdl()
{
    //For Accounting
    global $orgID;
    global $usrID;
    global $allMdlslovID;
    $DefaultPrvldgs =  array(
        "View Accounting", "View Chart of Accounts",
        /* 2 */ "View Account Transactions", "View Transactions Search",
        /* 4 */ "View/Generate Trial Balance", "View/Generate Profit & Loss Statement",
        /* 6 */ "View/Generate Balance Sheet", "View Budgets",
        /* 8 */ "View Transaction Templates", "View Record History", "View SQL",
        /* 11 */ "Add Chart of Accounts", "Edit Chart of Accounts", "Delete Chart of Accounts",
        /* 14 */ "Add Batch for Transactions", "Edit Batch for Transactions", "Void/Delete Batch for Transactions",
        /* 17 */ "Add Transactions Directly", "Edit Transactions", "Delete Transactions",
        /* 20 */ "Add Transactions Using Template", "Post Transactions",
        /* 22 */ "Add Budgets", "Edit Budgets", "Delete Budgets",
        /* 25 */ "Add Transaction Templates", "Edit Transaction Templates", "Delete Transaction Templates",
        /* 28 */ "View Only Self-Created Transaction Batches",
        /* 29 */ "View Financial Statements", "View Accounting Periods", "View Payables",
        /* 32 */ "View Receivables", "View Customers/Suppliers", "View Tax Codes",
        /* 35 */ "View Default Accounts", "View Account Reconciliation",
        /* 37 */ "Add Accounting Periods", "Edit Accounting Periods", "Delete Accounting Periods",
        /* 40 */ "View Fixed Assets", "View Payments",
        /* 42 */ "Add Payment Methods", "Edit Payment Methods", "Delete Payment Methods",
        /* 45 */ "Add Supplier Standard Payments", "Edit Supplier Standard Payments", "Delete Supplier Standard Payments",
        /* 48 */ "Add Supplier Advance Payments", "Edit Supplier Advance Payments", "Delete Supplier Advance Payments",
        /* 51 */ "Setup Exchange Rates", "Setup Document Templates", "Review/Approve Payables Documents", "Review/Approve Receivables Documents",
        /* 55 */ "Add Direct Refund from Supplier", "Edit Direct Refund from Supplier", "Delete Direct Refund from Supplier",
        /* 58 */ "Add Supplier Credit Memo (InDirect Refund)", "Edit Supplier Credit Memo (InDirect Refund)", "Delete Supplier Credit Memo (InDirect Refund)",
        /* 61 */ "Add Direct Topup for Supplier", "Edit Direct Topup for Supplier", "Delete Direct Topup for Supplier",
        /* 64 */ "Add Supplier Debit Memo (InDirect Topup)", "Edit Supplier Debit Memo (InDirect Topup)", "Delete Supplier Debit Memo (InDirect Topup)",
        /* 67 */ "Cancel Payables Documents", "Cancel Receivables Documents",
        /* 69 */ "Reject Payables Documents", "Reject Receivables Documents",
        /* 71 */ "Pay Payables Documents", "Pay Receivables Documents",
        /* 73 */ "Add Customer Standard Payments", "Edit Customer Standard Payments", "Delete Customer Standard Payments",
        /* 76 */ "Add Customer Advance Payments", "Edit Customer Advance Payments", "Delete Customer Advance Payments",
        /* 79 */ "Add Direct Refund to Customer", "Edit Direct Refund to Customer", "Delete Direct Refund to Customer",
        /* 82 */ "Add Customer Credit Memo (InDirect Topup)", "Edit Customer Credit Memo (InDirect Topup)", "Delete Customer Credit Memo (InDirect Topup)",
        /* 85 */ "Add Direct Topup from Customer", "Edit Direct Topup from Customer", "Delete Direct Topup from Customer",
        /* 88 */ "Add Customer Debit Memo (InDirect Refund)", "Edit Customer Debit Memo (InDirect Refund)", "Delete Customer Debit Memo (InDirect Refund)",
        /* 91 */ "Add Customers/Suppliers", "Edit Customers/Suppliers", "Delete Customers/Suppliers",
        /* 94 */ "Add Fixed Assets", "Edit Fixed Assets", "Delete Fixed Assets",
        /* 97 */ "View Petty Cash Vouchers", "View Petty Cash Payments", "Add Petty Cash Payments", "Edit Petty Cash Payments", "Delete Petty Cash Payments",
        /* 102 */ "View Petty Cash Re-imbursements", "Add Petty Cash Re-imbursements", "Edit Petty Cash Re-imbursements", "Delete Petty Cash Re-imbursements",
        /* 106 */ "Edit Journal Entries(Debit/Credit)", "Edit Journal Entries(Increase/Decrease)", "Edit Simplified Double Entries",
        /* 109 */ "Review/Approve Petty Cash Payments", "Review/Approve Petty Cash Re-imbursements",
        /* 111 */ "View Expense Vouchers", "Add Expense Vouchers", "Edit Expense Vouchers", "Delete Expense Vouchers",
        /* 115 */ "View Income Vouchers", "Add Income Vouchers", "Edit Income Vouchers", "Delete Income Vouchers",
        /* 119 */ "View Fund Management", "Add Fund Management", "Edit Fund Management", "Delete Fund Management"
    );

    $subGrpNames = array("Chart of Accounts", "Fixed Assets", "Customers/Suppliers", "Fixed Assets PM Records", "Receivable Invoices", "Payable Invoices"); //, "Accounting Transactions"
    $mainTableNames = array(
        "accb.accb_chart_of_accnts", "accb.accb_fa_assets_rgstr", "scm.scm_cstmr_suplr", "accb.accb_fa_assets_pm_recs", "accb.accb_rcvbls_invc_hdr",
        "accb.accb_pybls_invc_hdr"
    ); //, "accb.accb_trnsctn_details"
    $keyColumnNames = array("accnt_id", "asset_id", "cust_sup_id", "asset_pm_rec_id", "rcvbls_invc_hdr_id", "pybls_invc_hdr_id"); //, "transctn_id" 
    $myName = "Accounting";
    $myDesc = "This module helps you to manage your organization's Accounting!";
    $audit_tbl_name = "accb.accb_audit_trail_tbl";
    $smplRoleName = "Accounting Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createAcctngRqrdLOVs();
    createAcctngRqrdLOVs1();
    if (getEnbldPssblValID($myName, $allMdlslovID) > 0) {
        if ($orgID <= 0) {
            $orgID = getPrsnOrgID($usrID);
        }
        if ($orgID > 0) {
            $fnccurid = getOrgFuncCurID($orgID);
            updtOrgAccntCurrID($orgID, $fnccurid);
            execUpdtInsSQL("Update accb.accb_budget_details SET entrd_curr_id=" . $fnccurid .
                ", entrd_amount=COALESCE(limit_amount,0)" .
                ", func_curr_rate=1.0000 WHERE entrd_curr_id<=0");
        }
        execUpdtInsSQL("UPDATE accb.accb_payments 
SET amount_given=(CASE WHEN (amount_paid>0 and change_or_balance>=0) or (amount_paid<0 and change_or_balance<=0) THEN amount_paid 
            ELSE (amount_paid / abs(amount_paid)) * abs(amount_paid - change_or_balance) END)
					 WHERE amount_given = 0 and amount_paid != 0");

        execUpdtInsSQL("UPDATE accb.accb_payments_batches a SET pymnt_date=last_update_date, 
	incrs_dcrs1=(select max(CASE WHEN Coalesce(b.incrs_dcrs1,'D')='D' THEN 'Decrease' ELSE 'Increase' END) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        rcvbl_lblty_accnt_id=(select max(b.rcvbl_lblty_accnt_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        incrs_dcrs2=(select max(CASE WHEN Coalesce(b.incrs_dcrs2,'I')='I' THEN 'Increase'ELSE 'Decrease' END) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        cash_or_suspns_acnt_id=(select max(b.cash_or_suspns_acnt_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
	amount_given=(select sum(b.amount_given) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        amount_being_paid=(select sum(b.amount_paid) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        change_or_balance=(select sum(b.change_or_balance) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        entrd_curr_id=(select max(b.entrd_curr_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
	func_curr_id=(select max(b.func_curr_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        func_curr_rate=(select max(b.func_curr_rate) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        func_curr_amount=(select sum(b.func_curr_amount) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        accnt_curr_id=(select max(b.accnt_curr_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        accnt_curr_rate=(select max(b.accnt_curr_rate) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
	accnt_curr_amnt=(select sum(b.accnt_curr_amnt) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        cheque_card_name=(select max(b.cheque_card_name) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        cheque_card_num=(select max(b.cheque_card_num) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        sign_code=(select max(b.sign_code) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id), 
        gl_batch_id=(select min(b.gl_batch_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id)
        WHERE (gl_batch_id<=0 OR gl_batch_id IS NULL) AND (select count(b.pymnt_id) from accb.accb_payments b where a.pymnt_batch_id=b.pymnt_batch_id)>0");
    }
}

function createAcctngRqrdLOVs()
{
    $sysLovs = array(
        "Control Accounts", "Transactions not Allowed Days",
        "Transactions not Allowed Dates", "Account Transaction Templates",
        "Currencies", "Payment Document Templates", "Payment Methods",
        "Supplier Prepayments", "Supplier Debit Memos", "Supplier Standard Payments",
        "Customer Prepayments", "Customer Credit Memos", "Customer Standard Payments",
        "Transaction Amount Breakdown Parameters", "Receivables Docs. with Prepayments Applied",
        "Payables Docs. with Prepayments Applied", "Unposted Batches", "Weekend Days",
        "Holiday Dates", "Cheque Clearing Accounts", "Customer File Numbers", "Budgets",
        "Supplier Standard Payments New", "Unprocessed Payment Batches", "Period Types"
    );
    $sysLovsDesc = array(
        "Control Accounts", "Transactions not Allowed Days",
        "Transactions not Allowed Dates", "Account Transaction Templates",
        "Currencies", "Payment Document Templates", "Payment Methods",
        "Supplier Prepayments", "Supplier Debit Memos", "Supplier Standard Payments",
        "Customer Prepayments", "Customer Credit Memos", "Customer Standard Payments",
        "Transaction Amount Breakdown Parameters", "Receivables Docs. with Prepayments Applied",
        "Payables Docs. with Prepayments Applied", "Unposted Batches", "Weekend Days",
        "Holiday Dates", "Cheque Clearing Accounts", "Customer File Numbers", "Budgets",
        "Supplier Standard Payments New", "Unprocessed Payment Batches", "Period Types"
    );
    $sysLovsDynQrys = array(
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_type e, accnt_num f from accb.accb_chart_of_accnts where (has_sub_ledgers = '1' and is_enabled = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "", "",
        "SELECT distinct trim(to_char(z.template_id,'999999999999999999999999999999')) a, z.template_name b,'' c, z.org_id d, 
        trim(to_char(w.user_id,'999999999999999999999999999999')) e, ''||(Select count(k.detail_id) from accb.accb_trnsctn_templates_det k where k.template_id = z.template_id) f, z.doc_type g
                            FROM accb.accb_trnsctn_templates_hdr z
                            LEFT OUTER JOIN accb.accb_trnsctn_templates_usrs w
                            ON ((z.template_id=w.template_id) and (now() between to_timestamp(w.valid_start_date,'YYYY-MM-DD HH24:MI:SS')
                            AND to_timestamp(w.valid_end_date,'YYYY-MM-DD HH24:MI:SS'))) WHERE z.is_enabled='1' ORDER BY z.template_name", "",
        "select distinct trim(to_char(doc_tmplts_hdr_id,'999999999999999999999999999999')) a, doc_tmplt_name b, '' c, org_id d, doc_type e from accb.accb_doc_tmplts_hdr where (is_enabled = '1') order by doc_tmplt_name",
        "select distinct trim(to_char(paymnt_mthd_id,'999999999999999999999999999999')) a, pymnt_mthd_name b, '' c, org_id d, supported_doc_type e from accb.accb_paymnt_mthds where (is_enabled = '1') order by pymnt_mthd_name",
        "select distinct trim(to_char(pybls_invc_hdr_id,'999999999999999999999999999999')) a, pybls_invc_number ||' ('||(invoice_amount-invc_amnt_appld_elswhr)||')' b, '' c, org_id d, trim(to_char(supplier_id,'999999999999999999999999999999')) e, trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, pybls_invc_hdr_id g from accb.accb_pybls_invc_hdr where (((pybls_invc_type = 'Supplier Advance Payment' and (invoice_amount-amnt_paid)<=0) or pybls_invc_type = 'Supplier Credit Memo (InDirect Refund)') and approval_status='Approved' and (invoice_amount-invc_amnt_appld_elswhr)>0) order by pybls_invc_hdr_id DESC",
        "select distinct trim(to_char(pybls_invc_hdr_id,'999999999999999999999999999999')) a, pybls_invc_number b, '' c, org_id d, trim(to_char(supplier_id,'999999999999999999999999999999')) e, trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, pybls_invc_hdr_id g from accb.accb_pybls_invc_hdr where ((pybls_invc_type = 'Supplier Debit Memo (InDirect Topup)') and approval_status='Approved' and (invoice_amount-invc_amnt_appld_elswhr)>0) order by pybls_invc_hdr_id DESC",
        "select distinct trim(to_char(pybls_invc_hdr_id,'999999999999999999999999999999')) a, pybls_invc_number b, '' c, org_id d, trim(to_char(supplier_id,'999999999999999999999999999999')) e, trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, pybls_invc_hdr_id g from accb.accb_pybls_invc_hdr where ((pybls_invc_type = 'Supplier Standard Payment') and approval_status='Approved' and (invoice_amount-amnt_paid)<=0) order by pybls_invc_hdr_id DESC",
        "select distinct trim(to_char(rcvbls_invc_hdr_id,'999999999999999999999999999999')) a, rcvbls_invc_number ||' ('||(invoice_amount-invc_amnt_appld_elswhr)||')' b, '' c, org_id d, trim(to_char(customer_id,'999999999999999999999999999999')) e, trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, rcvbls_invc_hdr_id g from accb.accb_rcvbls_invc_hdr where (((rcvbls_invc_type = 'Customer Advance Payment' and (invoice_amount-amnt_paid)<=0) or rcvbls_invc_type = 'Customer Debit Memo (InDirect Refund)') and approval_status='Approved' and (invoice_amount-invc_amnt_appld_elswhr)>0) order by rcvbls_invc_hdr_id DESC",
        "select distinct trim(to_char(rcvbls_invc_hdr_id,'999999999999999999999999999999')) a, rcvbls_invc_number b, '' c, org_id d, trim(to_char(customer_id,'999999999999999999999999999999')) e, trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, rcvbls_invc_hdr_id g from accb.accb_rcvbls_invc_hdr where ((rcvbls_invc_type = 'Customer Credit Memo (InDirect Topup)') and approval_status='Approved' and (invoice_amount-invc_amnt_appld_elswhr)>0) order by rcvbls_invc_hdr_id DESC",
        "select distinct trim(to_char(rcvbls_invc_hdr_id,'999999999999999999999999999999')) a, rcvbls_invc_number b, '' c, org_id d, trim(to_char(customer_id,'999999999999999999999999999999')) e, trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, rcvbls_invc_hdr_id g from accb.accb_rcvbls_invc_hdr where ((rcvbls_invc_type = 'Customer Standard Payment') and approval_status='Approved' and (invoice_amount-amnt_paid)<=0) order by rcvbls_invc_hdr_id DESC",
        "",
        "SELECT y.rcvbls_invc_number a, z.rcvbl_smmry_amnt || ' (' || y.approval_status || ')' b, '' c, 1 d, z.appld_prepymnt_doc_id||'' e, accb.get_src_doc_type(z.appld_prepymnt_doc_id,'Customer') f FROM accb.accb_rcvbls_invc_hdr y,accb.accb_rcvbl_amnt_smmrys z WHERE y.rcvbls_invc_hdr_id =z.src_rcvbl_hdr_id and z.appld_prepymnt_doc_id > 0 UNION Select accb.get_src_doc_num(w.src_doc_id, w.src_doc_typ) a, CASE WHEN (w.amount_paid>0 and w.change_or_balance <=0) or (w.amount_paid<0 and w.change_or_balance >=0) THEN Round(((w.amount_paid/abs(w.amount_paid))*w.amount_paid)-w.change_or_balance,2)|| ' (' || w.pymnt_vldty_status || ')' ELSE w.amount_paid || ' (' || w.pymnt_vldty_status || ')' END b, '' c, 1 d, w.prepay_doc_id||'' e, prepay_doc_type f FROM accb.accb_payments w WHERE w.prepay_doc_id>0 and prepay_doc_type ilike '%Customer%'",
        "SELECT y.pybls_invc_number a, z.pybls_smmry_amnt || ' (' || y.approval_status || ')' b, '' c, 1 d, z.appld_prepymnt_doc_id||'' e, accb.get_src_doc_type(z.appld_prepymnt_doc_id,'Supplier') f FROM accb.accb_pybls_invc_hdr y,accb.accb_pybls_amnt_smmrys z WHERE y.pybls_invc_hdr_id =z.src_pybls_hdr_id and z.appld_prepymnt_doc_id > 0 UNION Select accb.get_src_doc_num(w.src_doc_id, w.src_doc_typ) a, CASE WHEN (w.amount_paid>0 and w.change_or_balance <=0) or (w.amount_paid<0 and w.change_or_balance >=0) THEN Round(((w.amount_paid/abs(w.amount_paid))*w.amount_paid)-w.change_or_balance,2)|| ' (' || w.pymnt_vldty_status || ')' ELSE w.amount_paid || ' (' || w.pymnt_vldty_status || ')' END b, '' c, 1 d, w.prepay_doc_id||'' e, prepay_doc_type f FROM accb.accb_payments w WHERE w.prepay_doc_id>0 and prepay_doc_type ilike '%Supplier%'",
        "SELECT distinct '' || z.batch_id a, z.batch_name b,'' c, z.org_id d, ''||z.last_update_by e, z.batch_status f, z.batch_id g 
                            FROM accb.accb_trnsctn_batches z 
                            ORDER BY z.batch_id DESC", "", "",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_id IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE lower(z.maj_rpt_ctgry) = lower('Cheque Clearing Accounts')) and is_prnt_accnt = '0'
                       and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0'
                       and has_sub_ledgers = '0' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0)
                       order by accnt_num",
        "select trim(to_char(rcvbls_invc_hdr_id,'999999999999999999999999999999')) a,
cstmrs_doc_num ||' - '|| COALESCE(scm.get_cstmr_splr_name(customer_id),'') ||' ('|| comments_desc||' - '|| rcvbls_invc_number || ')' b, '' c,
org_id d, trim(to_char(customer_id,'999999999999999999999999999999')) e,
trim(to_char(invc_curr_id,'999999999999999999999999999999')) f, rcvbls_invc_hdr_id g
from accb.accb_rcvbls_invc_hdr where (cstmrs_doc_num!='' and cstmrs_doc_num IS NOT NULL)
order by rcvbls_invc_hdr_id DESC",
        "SELECT distinct ''||budget_id a, budget_name b, '' c, org_id d FROM accb.accb_budget_header",
        "select distinct trim(to_char(pybls_invc_hdr_id,'999999999999999999999999999999')) a, pybls_invc_number b, '' c,
 org_id d, trim(to_char(supplier_id,'999999999999999999999999999999')) e,
 gst.get_pssbl_val(invc_curr_id) f, pybls_invc_hdr_id g
 from accb.accb_pybls_invc_hdr where ((pybls_invc_type = 'Supplier Standard Payment')
 and approval_status='Approved' and (invoice_amount-amnt_paid)<=0) order by pybls_invc_hdr_id DESC",
        "SELECT distinct '' || z.pymnt_batch_id a, z.pymnt_batch_name b,'' c, z.org_id d, ''||z.last_update_by e, z.batch_status f, z.pymnt_batch_id g  FROM accb.accb_payments_batches z
  WHERE z.batch_status='Unprocessed' ORDER BY z.pymnt_batch_id DESC", ""
    );

    $pssblVals = array(
        "2", "01-JAN-1901", "Sample Holiday Date Disallowed",
        "2", "01-JAN-2014", "Sample Holiday Date Disallowed",
        "1", "SUNDAY", "No Weekend Transactions",
        "1", "SATURDAY", "No Weekend Transactions",
        "4", "EUR", "European Euro",
        "4", "CNY", "Chinese Yuan",
        "4", "ZAR", "South African Rand",
        "4", "XAF", "CFA Franc (BEAC)",
        "4", "XOF", "CFA Franc (BCEAO)",
        "4", "NGN", "Nigerian Naira",
        "13", "GHS 50", "50",
        "13", "GHS 20", "20",
        "13", "GHS 10", "10",
        "13", "GHS 5", "5",
        "13", "GHS 2", "2",
        "13", "GHS 1", "1",
        "13", "GHS 0.50", "0.50",
        "13", "GHS 0.20", "0.20",
        "13", "GHS 0.10", "0.10",
        "13", "GHS 0.05", "0.05",
        "13", "GHS 0.01", "0.01",
        "17", "SUNDAY", "Weekend",
        "17", "SATURDAY", "Weekend",
        "18", "01-JAN-1901", "Sample Holiday Date",
        "18", "01-JAN-2014", "Sample Holiday Date",
        "24", "Yearly", "Yearly",
        "24", "Half Yearly", "Half Yearly",
        "24", "Quarterly", "Quarterly",
        "24", "Monthly", "Monthly",
        "24", "Fortnightly", "Fortnightly",
        "24", "Weekly", "Weekly"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
    $prcsstyps = array(
        "Trial Balance Report", "Profit and Loss Report",
        "Balance Sheet Report", "Subledger Balance Report",
        "Post GL Batch", "Open/Close Periods",
        "Inventory Journal Import", "Internal Payments Journal Import",
        "Banking Journal Import", "VMS Journal Import"
    );
    for ($i = 1; $i < 11; $i++) {
        if (getActnPrcssID($i) <= 0) {
            createActnPrcss($i, $prcsstyps[$i - 1]);
        } else {
            updtActnPrcss($i, $prcsstyps[$i - 1]);
        }
    }
}

function createAcctngRqrdLOVs1()
{
    $sysLovs = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Customer/Supplier Sites", "Users' Sales Stores", "Approved Pro-Forma Invoices",
        /* 14 */ "Approved Sales Orders", "Approved Internal Item Requests",
        /* 16 */ "Customers", "Approved Sales Invoices/Item Issues", "Customer/Supplier Classifications",
        /* 19 */ "Unlinked Persons (Customers/Suppliers)", "All Accounts",
        /* 21 */ "All Asset Accounts", "All Liability Accounts", "All Equity Accounts",
        "All Revenue Accounts",
        /* 25 */ "All Expense Accounts", "All Memo Accounts", "Asset Classifications",
        /* 28 */ "Asset Categories", "Asset Building Names", "Asset Room Names",
        "Asset Numbers",
        /* 32 */ "PM Measurement Types", "PM Measurement Units", "PM Actions Taken",
        "All Business/Trade Partners", "Petty Cash Accounts"
    );
    $sysLovsDesc = array(
        "Cash Accounts", "Inventory/Asset Accounts", "Contra Expense Accounts",
        "Contra Revenue Accounts", "Customer Classifications", "Supplier Classifications",
        "Tax Codes", "Discount Codes", "Extra Charges", "Approved Requisitions",
        "Suppliers", "Customer/Supplier Sites", "Users' Sales Stores", "Approved Pro-Forma Invoices",
        "Approved Sales Orders", "Approved Internal Item Requests",
        "Customers", "Approved Sales Invoices/Item Issues", "Simultaneous Customer/Supplier Classifications",
        "Persons not Linked as Customers/Suppliers", "All Accounts",
        "All Asset Accounts", "All Liability Accounts", "All Equity Accounts", "All Revenue Accounts", "All Expense Accounts", "All Memo Accounts", "Asset Classifications", "Asset Categories", "Asset Building Names", "Asset Room Names", "Asset Numbers",
        /* 32 */ "PM Measurement Types", "PM Measurement Units", "PM Actions Taken",
        "All Business/Trade Partners", "Petty Cash Accounts"
    );
    $sysLovsDynQrys = array(
        "", "",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EX' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'R' and is_prnt_accnt = '0' and is_enabled = '1' and is_contra = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "", "",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d, is_parent e from scm.scm_tax_codes where (itm_type = 'Tax' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d, is_parent e from scm.scm_tax_codes where (itm_type = 'Discount' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(code_id,'999999999999999999999999999999')) a, code_name b, '' c, org_id d, is_parent e from scm.scm_tax_codes where (itm_type = 'Extra Charge' and is_enabled = '1') order by code_name",
        "select distinct trim(to_char(y.prchs_doc_hdr_id,'999999999999999999999999999999')) a, y.purchase_doc_num b, '' c, y.org_id d, y.prchs_doc_hdr_id g " .
            "from scm.scm_prchs_docs_hdr y, scm.scm_prchs_docs_det z " .
            "where (y.purchase_doc_type = 'Purchase Requisition' " .
            "and y.approval_status = 'Approved' " .
            "and z.prchs_doc_hdr_id = y.prchs_doc_hdr_id and (z.quantity - z.rqstd_qty_ordrd)>0) order by y.prchs_doc_hdr_id DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Supplier%' and is_enabled='1') order by 2",
        "select distinct trim(to_char(cust_sup_site_id,'999999999999999999999999999999')) a, site_name b, '' c, cust_supplier_id d from scm.scm_cstmr_suplr_sites where is_enabled='1' order by 2",
        "select distinct trim(to_char(y.subinv_id,'999999999999999999999999999999')) a, y.subinv_name b, '' c, y.org_id d, trim(to_char(z.user_id,'999999999999999999999999999999')) e from inv.inv_itm_subinventories y, inv.inv_user_subinventories z where y.subinv_id=z.subinv_id and y.allow_sales = '1' order by 2",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Pro-Forma Invoice' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Sales Order' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where (y.invc_type = 'Internal Item Request' " .
            "and y.approval_status = 'Approved' " .
            "and z.invc_hdr_id = y.invc_hdr_id and (z.doc_qty - z.qty_trnsctd_in_dest_doc)>0) order by y.invc_hdr_id DESC",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d from scm.scm_cstmr_suplr where (cust_or_sup ilike '%Customer%' and is_enabled='1') order by 2",
        "select distinct trim(to_char(y.invc_hdr_id,'999999999999999999999999999999')) a, y.invc_number b, '' c, y.org_id d, y.invc_hdr_id g " .
            "from scm.scm_sales_invc_hdr y, scm.scm_sales_invc_det z " .
            "where ((y.invc_type = 'Item Issue-Unbilled' or y.invc_type = 'Sales Invoice') " .
            "and (y.approval_status = 'Approved') " .
            "and (z.invc_hdr_id = y.invc_hdr_id) and ((z.doc_qty - z.qty_trnsctd_in_dest_doc)>0)) order by y.invc_hdr_id DESC",
        "",
        "SELECT distinct local_id_no a, trim(title || ' ' || sur_name || " .
            "', ' || first_name || ' ' || other_names) b, '' c, org_id d " .
            "FROM prs.prsn_names_nos a where a.person_id NOT IN (Select lnkd_prsn_id from scm.scm_cstmr_suplr where lnkd_prsn_id>0) order by local_id_no DESC",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, (CASE WHEN prnt_accnt_id>0 THEN accnt_num || '.' || accnt_name || ' ('|| accb.get_accnt_num(prnt_accnt_id)||'.'||accb.get_accnt_name(prnt_accnt_id)|| ')' WHEN control_account_id>0 THEN accnt_num || '.' || accnt_name || ' ('|| accb.get_accnt_num(control_account_id)||'.'||accb.get_accnt_name(control_account_id)|| ')' ELSE accnt_num || '.' || accnt_name END) b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (is_enabled = '1' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'A' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'L' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EQ' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'R' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_name b, '' c, org_id d, accnt_num e from accb.accb_chart_of_accnts where (accnt_type = 'EX' and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num",
        "select distinct trim(to_char(memo_accnt_id,'999999999999999999999999999999')) a, memo_accnt_name b, '' c, org_id d, memo_accnt_num e from accb.accb_memo_accounts where (is_enabled = '1') order by memo_accnt_num",
        "", "", "", "",
        "select distinct '' || asset_id a, trim(asset_code_name || ' ' || REPLACE(asset_desc, asset_code_name, '')) b, '' c, org_id d from accb.accb_fa_assets_rgstr order by 2",
        "", "", "",
        "select distinct trim(to_char(cust_sup_id,'999999999999999999999999999999')) a, cust_sup_name b, '' c, org_id d, lnkd_prsn_id e, cust_or_sup f from scm.scm_cstmr_suplr where (is_enabled='1') order by 2",
        "select distinct trim(to_char(accnt_id,'999999999999999999999999999999')) a, accnt_num || '.' || accnt_name b, '' c,
org_id d, accnt_num e from accb.accb_chart_of_accnts
where (accnt_type = 'A' and accnt_id IN (SELECT z.account_id
                       FROM accb.accb_account_clsfctns z
                       WHERE lower(z.maj_rpt_ctgry) = lower('Petty Cash')) and is_prnt_accnt = '0'
                       and is_enabled = '1' and  is_retained_earnings= '0' and is_net_income = '0' and has_sub_ledgers = '0'
                       and org.does_prsn_hv_accnt_id({:prsn_id},accnt_id)>0) order by accnt_num"
    );
    $pssblVals = array(
        "4", "Retail Customer", "Retail Customer", "4", "Wholesale customer", "Wholesale customer",
        "4", "Individual", "Individual Person", "4", "Organisation", "Company/Organisation",
        "5", "Service Provider", "Service Provider", "5", "Goods Provider", "Goods Provider",
        "5", "Service and Goods Provider", "Service and Goods Provider", "5", "Consultant", "Consultant", "5", "Training Provider", "Training Provider", "18", "Customer/Service Provider Organisation", "Customer/Service Provider Organisation", "18", "Customer/Service Provider Individual", "Customer/Service Provider Individual", "18", "Customer/Provider of Goods & Services", "Customer/Provider of Goods & Services", "27", "Major Fixed Asset", "Major Fixed Asset", "27", "Minor Fixed Asset", "Minor Fixed Asset", "27", "Financial Instrument", "Financial Instrument", "27", "Other Investment", "Other Investment", "28", "Computers", "Computers", "28", "Computers Accessories", "Computers Accessories", "28", "Office Equipment", "Office Equipment", "28", "Plant & Other Equipment", "Plant & Other Equipment", "28", "Land", "Land", "28", "Building", "Building", "28", "Treasury Bill Investment", "Treasury Bill Investment", "28", "Fixed Deposit Investment", "Fixed Deposit Investment", "29", "Head Office Building", "Head Office Building", "30", "Ground Floor Room 1", "Ground Floor Room 1", "32", "Mileage", "Distance Covered", "32", "Hours Worked", "Hours being turned on", "32", "Oil Level", "Oil Level", "33", "KM", "Kilometers", "33", "Hours", "Hours", "33", "Miles", "Miles", "33", "m", "meters", "33", "Percent", "Percent", "34", "General Servicing", "General Servicing", "34", "Oil Change", "Oil Change", "34", "General Cleaning", "General Cleaning"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function updtOrgAccntCurrID($orgID, $crncyID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE accb.accb_chart_of_accnts SET crncy_id = " . $crncyID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (org_id = " . $orgID . " and crncy_id<=0)";
    execUpdtInsSQL($updtSQL);
    $updtSQL = "UPDATE accb.accb_trnsctn_details SET dbt_or_crdt='C' WHERE dbt_or_crdt='U' and dbt_amount=0 and crdt_amount !=0;
UPDATE accb.accb_trnsctn_details SET dbt_or_crdt='D' WHERE dbt_or_crdt='U' and dbt_amount!=0 and crdt_amount =0;";
    execUpdtInsSQL($updtSQL);
    $updtSQL = "UPDATE accb.accb_trnsctn_details SET entered_amnt=dbt_amount, accnt_crncy_amnt=dbt_amount WHERE dbt_amount!=0 and crdt_amount =0 and entered_amnt=0 and accnt_crncy_amnt=0;
UPDATE accb.accb_trnsctn_details SET entered_amnt=crdt_amount, accnt_crncy_amnt=crdt_amount WHERE dbt_amount=0 and crdt_amount!=0 and entered_amnt=0 and accnt_crncy_amnt=0";
    execUpdtInsSQL($updtSQL);
    $updtSQL = "UPDATE accb.accb_trnsctn_details SET entered_amt_crncy_id=func_cur_id WHERE entered_amt_crncy_id=-1;
UPDATE accb.accb_trnsctn_details SET accnt_crncy_id=func_cur_id WHERE accnt_crncy_id=-1";
    execUpdtInsSQL($updtSQL);
    $updtSQL = "UPDATE prs.prsn_names_nos SET org_id=" . $orgID . " WHERE org_id=-1";
    execUpdtInsSQL($updtSQL);
}

function createDfltAcnts($orgid)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO scm.scm_dflt_accnts(" .
        "itm_inv_asst_acnt_id, cost_of_goods_acnt_id, expense_acnt_id, " .
        "prchs_rtrns_acnt_id, rvnu_acnt_id, sales_rtrns_acnt_id, sales_cash_acnt_id, " .
        "sales_check_acnt_id, sales_rcvbl_acnt_id, rcpt_cash_acnt_id, " .
        "rcpt_lblty_acnt_id, rho_name, org_id, created_by, creation_date, " .
        "last_update_by, last_update_date, inv_adjstmnts_lblty_acnt_id) " .
        "VALUES (-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,'Default Accounts', " .
        $orgid . ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "',-1)";
    execUpdtInsSQL($insSQL);
}

function updtOrgInvoiceCurrID($orgID, $crncyID, $pymtMthdID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE scm.scm_sales_invc_hdr SET invc_curr_id = " . $crncyID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (org_id = " . $orgID . " and invc_curr_id<=0)";
    execUpdtInsSQL($updtSQL);
    $updtSQL = "UPDATE scm.scm_sales_invc_hdr SET pymny_method_id = " . $pymtMthdID .
        ", last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " .
        "WHERE (org_id = " . $orgID . " and pymny_method_id<=0)";
    execUpdtInsSQL($updtSQL);
}

function updateOrgnlSellingPrice()
{
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE inv.inv_itm_list SET " .
        "orgnl_selling_price = selling_price  WHERE (orgnl_selling_price = 0 and selling_price IS NOT NULL)";
    execUpdtInsSQL($updtSQL);
}

function updateUOMPrices()
{
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE inv.itm_uoms SET 
            selling_price = scm.get_item_unit_sllng_price(item_id, 1)*cnvsn_factor, 
            price_less_tax=scm.get_item_unit_price_ls_tx(item_id, 1)*cnvsn_factor
      WHERE (selling_price = 0 and price_less_tax =0)";
    execUpdtInsSQL($updtSQL);
}

function getActnPrcssID($rnngprcsID)
{
    $strSql = "SELECT process_id FROM accb.accb_running_prcses WHERE which_process_is_rnng = " . $rnngprcsID . "";

    //Global.myNwMainFrm.cmmnCode.showMsg(strSql, 0);
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createActnPrcss($prcsID, $process_type)
{
    $dtestr = getDB_Date_time();
    $strSql = "INSERT INTO accb.accb_running_prcses(
            which_process_is_rnng, last_active_time, process_type)
    VALUES (" . $prcsID . ", '" . $dtestr . "','" . loc_db_escape_string($process_type) . "')";
    execUpdtInsSQL($strSql);
}

function updtActnPrcss($prcsID, $process_type)
{
    $dtestr = getDB_Date_time();
    $strSql = "UPDATE accb.accb_running_prcses SET
            last_active_time='" . $dtestr . "', process_type='" . loc_db_escape_string($process_type) . "' " .
        "WHERE which_process_is_rnng = " . $prcsID . " ";
    execUpdtInsSQL($strSql);
}

function loadPersonMdl()
{
    //For Accounting
    $DefaultPrvldgs = array(
        "View Person", "View Basic Person Data",
        /* 2 */ "View Curriculum Vitae", "View Basic Person Assignments",
        /* 4 */ "View Person Pay Item Assignments", "View SQL", "View Record History",
        /* 7 */ "Add Person Info", "Edit Person Info", "Delete Person Info",
        /* 10 */ "Add Basic Assignments", "Edit Basic Assignments", "Delete Basic Assignments",
        /* 13 */ "Add Pay Item Assignments", "Edit Pay Item Assignments", "Delete Pay Item Assignments", "View Banks",
        /* 17 */ "Define Assignment Templates", "Edit Assignment Templates", "Delete Assignment Templates",
        /* 20 */ "View Assignment Templates", "Manage My Firm",
        /* 22 */ "View Leave Management", "Add Leave Management", "Edit Leave Management", "Delete Leave Management"
    );

    $subGrpNames = array("Person Data");
    $mainTableNames = array("prs.prsn_names_nos");
    $keyColumnNames = array("person_id");
    $myName = "Basic Person Data";
    $myDesc = "This module helps you to setup basic information " .
        "about people in your organization!";
    $audit_tbl_name = "prs.prsn_audit_trail_tbl";
    $smplRoleName = "Basic Person Data Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);

    $sysLovs = array("All Other Basic Person Setups");
    $sysLovsDesc = array("All Other Basic Person Setups");
    $sysLovsDynQrys = array("");
    $pssblVals = array(
        "0", "Html Person Profile Print File Name", "htm_rpts/prs_prfl_rpt.php"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function loadGenStpMdl()
{
    $DefaultPrvldgs = array(
        "View General Setup", "View Value List Names", "View possible values", /* 3 */ "Add Value List Names", "Edit Value List Names", "Delete Value List Names", /* 6 */ "Add Possible Values", "Edit Possible Values", "Delete Possible Values", "View Record History", "View SQL"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "General Setup";
    $myDesc = "This module helps you to setup basic information " .
        "to be used by the software later!";
    $audit_tbl_name = "gst.gen_stp_audit_trail_tbl";

    $smplRoleName = "General Setup Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function loadProjsMdl()
{
    $DefaultPrvldgs = array(
        "View Projects Management",
        /* 1 */ "View Projects", "View Accounts Setup",
        /* 3 */ "View Resources Setup", "View SQL", "View Record History",
        /* 6 */ "Add Projects", "Edit Projects", "Delete Projects",
        /* 9 */ "Add Accounts", "Edit Accounts", "Delete Accounts",
        /* 12 */ "Add Resource", "Edit Resource", "Delete Resource",
        /* 15 */ "View Project Costs", "Edit Project Costs"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Projects Management";
    $myDesc = "This module helps you to manage your organization's Projects and their Associated Costs!";
    $audit_tbl_name = "proj.proj_audit_trail_tbl";

    $smplRoleName = "Projects Management Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function loadGenericMdl()
{
    $DefaultPrvldgs = array("View Generic Module");

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Generic Module";
    $myDesc = "This module is a mere place holder for categorising reports and processes!";
    $audit_tbl_name = "sec.sec_audit_trail_tbl";

    $smplRoleName = "Generic Module Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function loadIntPymntsMdl()
{
    global $orgID;
    global $allMdlslovID;
    $DefaultPrvldgs = array(
        "View Internal Payments",
        /* 1 */ "View Manual Payments", "View Pay Item Sets", "View Person Sets",
        /* 4 */ "View Mass Pay Runs", "View Payment Transactions", "View GL Interface Table",
        /* 7 */ "View Record History", "View SQL",
        /* 9 */ "Add Manual Payments", "Reverse Manual Payments",
        /* 11 */ "Add Pay Item Sets", "Edit Pay Item Sets", "Delete Pay Item Sets",
        /* 14 */ "Add Person Sets", "Edit Person Sets", "Delete Person Sets",
        /* 17 */ "Add Mass Pay", "Edit Mass Pay", "Delete Mass Pay", "Send Mass Pay Transactions to Actual GL",
        /* 21 */ "Send All Transactions to Actual GL", "Run Mass Pay",
        /* 23 */ "Rollback Mass Pay Run", "Send Selected Transactions to Actual GL",
        /* 25 */ "View Pay Items", "Add Pay Items", "Edit Pay Items", "Delete Pay Items",
        /* 29 */ "View Person Pay Item Assignments", "View Banks", "Add Pay Item Assignments",
        /* 32 */ "Edit Pay Item Assignments", "Delete Pay Item Assignments",
        /* 34 */ "View Global Values", "Add Global Values", "Edit Global Values", "Delete Global Values",
        /* 38 */ "View other User's Mass Pays", "View Loan Requests", "View Fund Management", "View Loan and Payment Types",
        /* 42 */ "Add Loan and Payment Requests", "Edit Loan and Payment Requests", "Delete Loan and Payment Requests",
        /* 45 */ "Add Fund Management", "Edit Fund Management", "Delete Fund Management",
        /* 48 */ "Add Loan and Payment Types", "Edit Loan and Payment Types", "Delete Loan and Payment Types",
        /* 51 */ "View PAYE Tax Rates"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Internal Payments";
    $myDesc = "This module helps you to manage your organization's HR Payments to Personnel!";
    $audit_tbl_name = "pay.pay_audit_trail_tbl";

    $smplRoleName = "Internal Payments Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);

    if (getEnbldPssblValID($myName, $allMdlslovID) > 0) {
        $updtSql = "UPDATE pay.pay_mass_pay_run_hdr
                    SET entered_amt_crncy_id=org.get_orgfunc_crncy_id(" . $orgID . ")
                 WHERE entered_amt_crncy_id <= 0";
        execUpdtInsSQL($updtSql);
        $updtSql1 = "UPDATE pay.pay_mass_pay_run_hdr
                    SET is_quick_pay='1'
                 WHERE mass_pay_name ILIKE 'Quick%'";
        execUpdtInsSQL($updtSql1);
    }
    createPayRqrdLOVs();
}

function loadPSBMdl()
{
    //For Accounting
    //global $sysLovs, $sysLovsDesc, $sysLovsDynQrys;
    $DefaultPrvldgs = array(
        "View PSB",
        /* 1 */ "View PSB Periods", "Add PSB Periods", "Edit PSB Periods", "Save PSB Periods",
        "Delete PSB Periods",
        /* 6 */ "Open PSB Periods", "Close PSB Periods",
        /* 8 */ "View PSB1", "View PSB2", "View PSB3", "View PSB4", "View PSB5",
        "View PSB6",
        /* 14 */ "View PSB7", "View PSB8A", "View PSB8B", "View PSB8C", "View PSB9",
        "View PSB10",
        /* 20 */ "Add PSB1", "Add PSB2", "Add PSB3", "Add PSB4", "Add PSB5", "Add PSB6",
        /* 26 */ "Add PSB7", "Add PSB8A", "Add PSB8B", "Add PSB8C", "Add PSB9", "Add PSB10",
        /* 32 */ "Edit PSB1", "Edit PSB2", "Edit PSB3", "Edit PSB4", "Edit PSB5",
        "Edit PSB6",
        /* 38 */ "Edit PSB7", "Edit PSB8A", "Edit PSB8B", "Edit PSB8C", "Edit PSB9",
        "Edit PSB10",
        /* 44 */ "Delete PSB1", "Delete PSB2", "Delete PSB3", "Delete PSB4", "Delete PSB5",
        "Delete PSB6",
        /* 50 */ "Delete PSB7", "Delete PSB8A", "Delete PSB8B", "Delete PSB8C", "Delete PSB9",
        "Delete PSB10",
        /* 56 */ "Submit PSB1", "Submit PSB2", "Submit PSB3", "Submit PSB4", "Submit PSB5",
        "Submit PSB6",
        /* 62 */ "Submit PSB7", "Submit PSB8A", "Submit PSB8B", "Submit PSB8C", "Submit PSB9",
        "Submit PSB10",
        /* 67 */ "View PSB2 Summary", "View PSB4 P/I Involved", "View PSB7 P/I Involved",
        /* 70 */ "Upload PSB8A CSV", "Upload PSB8B CSV", "Upload PSB8C CSV",
        /* 73 */ "PSB1 Superuser", "PSB2 Superuser", "PSB3 Superuser", "PSB4 Superuser",
        "PSB5 Superuser", "PSB6 Superuser",
        /* 79 */ "PSB7 Superuser", "PSB8A Superuser", "PSB8B Superuser", "PSB8C Superuser",
        "PSB9 Superuser", "PSB10 Superuser",
        /* 85 */ "Download PSB8A CSV", "Download PSB8B CSV", "Download PSB8C CSV",
        /* 88 */ "View SQL", "View Record History"
    );

    $subGrpNames = ""; //, "Accounting Transactions"
    $mainTableNames = ""; //, "accb.accb_trnsctn_details"
    $keyColumnNames = ""; //, "transctn_id" 
    $myName = "Payment Systems Banking";
    $myDesc = "This module helps you to manage the payment systems banking returns!";
    $audit_tbl_name = "sec.sec_audit_trail_tbl";
    $smplRoleName = "PSB Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    //createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
}

function loadOrgStpMdl()
{
    $DefaultPrvldgs = array(
        "View Organization Setup",
        "View Org Details", "View Divisions/Groups", "View Sites/Locations",
        /* 4 */ "View Jobs", "View Grades", "View Positions", "View Benefits",
        /* 8 */ "View Pay Items", "View Remunerations", "View Working Hours",
        /* 11 */ "View Gathering Types", "View SQL", "View Record History",
        /* 14 */ "Add Org Details", "Edit Org Details",
        /* 16 */ "Add Divisions/Groups", "Edit Divisions/Groups", "Delete Divisions/Groups",
        /* 19 */ "Add Sites/Locations", "Edit Sites/Locations", "Delete Sites/Locations",
        /* 22 */ "Add Jobs", "Edit Jobs", "Delete Jobs",
        /* 25 */ "Add Grades", "Edit Grades", "Delete Grades",
        /* 28 */ "Add Positions", "Edit Positions", "Delete Positions",
        /* 31 */ "Add Pay Items", "Edit Pay Items", "Delete Pay Items",
        /* 34 */ "Add Working Hours", "Edit Working Hours", "Delete Working Hours",
        /* 37 */ "Add Gathering Types", "Edit Gathering Types", "Delete Gathering Types"
    );
    $subGrpNames = array(
        "Organisation's Details", "Divisions/Groups",
        "Sites/Locations", "Jobs", "Grades", "Positions"
    );
    $mainTableNames = array(
        "org.org_details", "org.org_divs_groups",
        "org.org_sites_locations", "org.org_jobs", "org.org_grades", "org.org_positions"
    );
    $keyColumnNames = array(
        "org_id", "div_id",
        "location_id", "job_id", "grade_id", "position_id"
    );
    $myName = "Organization Setup";
    $myDesc = "This module helps you to setup basic information " .
        "about your organization!";
    $audit_tbl_name = "org.org_audit_trail_tbl";

    $smplRoleName = "Organization Setup Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    $sysLovs = array(
        "Account Segment Values", "Control Account Segment Values", "Account Segment Value IDs", "All Segment Value IDs",
        "Segment 1 Values", "Segment 2 Values", "Segment 3 Values", "Segment 4 Values", "Segment 5 Values", "Segment 6 Values",
        "Segment 7 Values", "Segment 8 Values", "Segment 9 Values", "Segment 10 Values"
    );
    $sysLovsDesc = array(
        "Account Segment Values", "Control Account Segment Values", "Account Segment Value IDs", "All Segment Value IDs",
        "Segment 1 Values", "Segment 2 Values", "Segment 3 Values", "Segment 4 Values", "Segment 5 Values", "Segment 6 Values",
        "Segment 7 Values", "Segment 8 Values", "Segment 9 Values", "Segment 10 Values"
    );
    $sysLovsDynQrys = array(
        "Select segment_value a, segment_description b, '' c, segment_id d, is_enabled e, ''||dpndnt_sgmnt_val_id f from org.org_segment_values y",
        "Select segment_value a, segment_description b, '' c, segment_id d, is_enabled e, ''||dpndnt_sgmnt_val_id f from org.org_segment_values y where y.has_sub_ledgers='1'",
        "Select ''||segment_value_id a, segment_value ||':'|| segment_description b, '' c, segment_id d, is_enabled e, ''||dpndnt_sgmnt_val_id f from org.org_segment_values y",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=1",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=2",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=3",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=4",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=5",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=6",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=7",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=8",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=9",
        "Select ''||y.segment_value_id a, y.segment_value || '-' || y.segment_description b, '' c, y.segment_id d, y.is_enabled e, ''||y.dpndnt_sgmnt_val_id f from org.org_segment_values y,
org.org_acnt_sgmnts z where z.segment_id=y.segment_id and z.segment_number=10"
    );
    //$pssblVals = array();
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    //Global.myNwMainFrm.cmmnCode.createSysLovsPssblVals(sysLovs, pssblVals);
}

function loadRptMdl()
{
    $DefaultPrvldgs = array(
        "View Reports And Processes",
        /* 1 */ "View Report Definitions", "View Report Runs", "View SQL", "View Record History",
        /* 5 */ "Add Report/Process", "Edit Report/Process", "Delete Report/Process",
        /* 8 */ "Run Reports/Process", "Delete Report/Process Runs", "View Runs from Others",
        /* 11 */ "Delete Run Output File", "Add Alert", "Edit Alert", "Delete Alert"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "Reports And Processes";
    $myDesc = "This module helps you to manage all reports in the software!";
    $audit_tbl_name = "rpt.rpt_audit_trail_tbl";

    $smplRoleName = "Reports And Processes Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createRqrdRptsLOVs();
}

function createRqrdRptsLOVs()
{
    $sysLovs = array(
        "Report Output Formats", "Report Orientations", "Report/Process Runs", "Reports and Processes", "Background Process Runners", "Alert Types",
        "Max Allowed Concurrent Connections", "Reference Numbers for Letters"
    );
    $sysLovsDesc = array(
        "Report Output Formats", "Report Orientations", "Report/Process Runs", "Reports and Processes", "Background Process Runners", "Alert Types",
        "Max Allowed Concurrent Connections", "Reference Numbers for Letters"
    );
    $sysLovsDynQrys = array(
        "", "",
        "select distinct trim(to_char(rpt_run_id,'999999999999999999999999999999')) a, (run_status_txt || '-' || run_by || '-' || run_date) b, '' c, report_id d, run_by e, rpt_run_id f from rpt.rpt_report_runs order by rpt_run_id DESC",
        "select distinct trim(to_char(report_id,'999999999999999999999999999999')) a, report_name b, '' c from rpt.rpt_reports order by report_name",
        "select distinct trim(to_char(prcss_rnnr_id,'999999999999999999999999999999')) a, rnnr_name b, '' c from rpt.rpt_prcss_rnnrs where rnnr_name != 'REQUESTS LISTENER PROGRAM' order by rnnr_name",
        "", "", ""
    );
    $pssblVals = array(
        "0", "None", "None", "0", "MICROSOFT EXCEL", "MICROSOFT EXCEL", "0", "HTML", "HTML", "0", "STANDARD", "STANDARD", "0", "PDF", "PDF", "0", "MICROSOFT WORD", "MICROSOFT WORD", "0", "CHARACTER SEPARATED FILE (CSV)", "DELIMITER SEPARATED FILE (CSV)", "0", "COLUMN CHART", "COLUMN CHART", "0", "PIE CHART", "PIE CHART", "0", "LINE CHART", "LINE CHART", "1", "Portrait", "Portrait", "1", "Landscape", "Landscape", "5", "EMAIL", "EMAIL", "5", "SMS", "SMS", "6", "5", "After 5 db connections don't launch any new process runner", "7", "RHO/PF", "00001"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);


    $prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM");
    if ($prgmID <= 0) {
        createPrcsRnnr(
            "REQUESTS LISTENER PROGRAM",
            "This is the main Program responsible for making sure that " .
                "your reports and background processes are run by their respective " .
                "programs when a request is submitted for them to be run.",
            "2013-01-01 00:00:00",
            "Not Running",
            "3-Normal",
            "\\bin\\REMSProcessRunner.exe"
        );
    } else {
        updatePrcsRnnrNm(
            $prgmID,
            "REQUESTS LISTENER PROGRAM",
            "This is the main Program responsible for making sure that " .
                "your reports and background processes are run by their respective " .
                "programs when a request is submitted for them to be run.",
            "\\bin\\REMSProcessRunner.exe"
        );
    }

    $prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "Standard Process Runner");
    if ($prgmID <= 0) {
        createPrcsRnnr(
            "Standard Process Runner",
            "This is a standard runner that can run almost all kinds of reports and processes in the background.",
            "2013-01-01 00:00:00",
            "Not Running",
            "3-Normal",
            "\\bin\\REMSProcessRunner.exe"
        );
    } else {
        updatePrcsRnnrNm(
            $prgmID,
            "Standard Process Runner",
            "This is a standard runner that can run almost all kinds of reports and processes in the background.",
            "\\bin\\REMSProcessRunner.exe"
        );
    }
    $prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "Customised Process Runner");
    if ($prgmID <= 0) {
        createPrcsRnnr(
            "Customised Process Runner",
            "This is a standard runner that runs customer specific reports and processes.",
            "2013-01-01 00:00:00",
            "Not Running",
            "3-Normal",
            "\\bin\\REMSCustomRunner.exe"
        );
    } else {
        updatePrcsRnnrNm(
            $prgmID,
            "Customised Process Runner",
            "This is a standard runner that runs customer specific reports and processes.",
            "\\bin\\REMSCustomRunner.exe"
        );
    }
    $prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "REQUESTS LISTENER PROGRAM-JAVA");
    if ($prgmID <= 0) {
        createPrcsRnnr(
            "REQUESTS LISTENER PROGRAM-JAVA",
            "This is the main Program responsible for making sure that " .
                "your reports and background processes especially Linux based processes are run by their respective " .
                "programs when a request is submitted for them to be run.",
            "2013-01-01 00:00:00",
            "Not Running",
            "3-Normal",
            "\\bin\\REMSProcessRunner.jar"
        );
    } else {
        updatePrcsRnnrNm(
            $prgmID,
            "REQUESTS LISTENER PROGRAM-JAVA",
            "This is the main Program responsible for making sure that " .
                "your reports and background processes especially Linux based processes are run by their respective " .
                "programs when a request is submitted for them to be run.",
            "\\bin\\REMSProcessRunner.jar"
        );
    }
    $prgmID = getGnrlRecID2("rpt.rpt_prcss_rnnrs", "rnnr_name", "prcss_rnnr_id", "Jasper Process Runner");
    if ($prgmID <= 0) {
        createPrcsRnnr(
            "Jasper Process Runner",
            "This is a standard runner that can run almost all kinds of jasper reports and other processes especially Linux based processes in the background.",
            "2013-01-01 00:00:00",
            "Not Running",
            "3-Normal",
            "\\bin\\REMSProcessRunner.jar"
        );
    } else {
        updatePrcsRnnrNm(
            $prgmID,
            "Jasper Process Runner",
            "This is a standard runner that can run almost all kinds of jasper reports and other processes especially Linux based processes in the background.",
            "\\bin\\REMSProcessRunner.jar"
        );
    }
}

function loadEvoteMdl()
{
    $DefaultPrvldgs = array(
        "View e-Voting",
        /* 1 */ "View All Surveys/Elections", "View Questions Bank", "View Person Sets",
        /* 4 */ "View Record History", "View SQL",
        /* 6 */ "Add Surveys/Elections", "Edit Surveys/Elections", "Delete Surveys/Elections",
        /* 9 */ "Add Questions Bank", "Edit Questions Bank", "Delete Questions Bank"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "e-Voting";
    $myDesc = "This is where Elections/Polls/Surveys in the Organisation are Conducted and Managed!";
    $audit_tbl_name = "self.self_prsn_audit_trail_tbl";

    $smplRoleName = "e-Voting Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);

    $sysLovs = array("Questions Bank", "Question Possible Answers");
    $sysLovsDesc = array("Questions Bank", "Question Possible Answers");
    $sysLovsDynQrys = array(
        "select '' || qstn_id a, qstn_desc b, '' c "
            . "from self.self_question_bank order by qstn_id",
        "select '' || psbl_ansr_id a, psbl_ansr_desc b, '' c, qstn_id d, '' || qstn_id e "
            . "from self.self_question_possible_answers where is_enabled='1' order by psbl_ansr_order_no"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
}

function loadELearnMdl()
{
    $DefaultPrvldgs = array(
        "View e-Learning",
        /* 1 */ "View All Exams/Tests", "View Questions Bank", "View Person Sets",
        /* 4 */ "View Record History", "View SQL",
        /* 6 */ "Add Exams/Tests", "Edit Exams/Tests", "Delete Exams/Tests",
        /* 9 */ "Add Questions Bank", "Edit Questions Bank", "Delete Questions Bank"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "e-Learning";
    $myDesc = "This is where Examinations/Tests in the Institution are Conducted and Managed!";
    $audit_tbl_name = "self.self_prsn_audit_trail_tbl";

    $smplRoleName = "e-Learning Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function loadHospMdl()
{
    $DefaultPrvldgs = array(/* 0 */
        "View Visits and Appointments",
        /* 1 */ "View Visits/Appointments", "View Appointments Data", "View Service Providers",
        "View Services Offered",
        /* 5 */ "View SQL", "View Record History",
        /* 7 */ "Add Visits/Appointments", "Edit Visits/Appointments", "Delete Visits/Appointments",
        /* 10 */ "Add Appointment Data", "Edit Appointment Data", "Delete Appointment Data",
        /* 13 */ "Add Services Offered", "Edit Services Offered", "Delete Services Offered",
        /* 16 */ "Add Service Providers", "Edit Service Providers", "Delete Service Providers",
        /* 19 */ "View only Self-Created Sales", "Cancel Documents", "Take Payments",
        /* 22 */ "Apply Adhoc Discounts", "Apply Pre-defined Discounts",
        /* 24 */ "Can Edit Unit Price", "View Other Provider's Data"
    );


    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Visits and Appointments";
    $myDesc = "This module helps you to manage your organization's Client Visits and Appointments Scheduling!!";
    $audit_tbl_name = "hosp.hosp_audit_trail_tbl";
    $smplRoleName = "Visits and Appointments Administrator";

    $DefaultPrvldgs1 = array(/* 0 */
        "View Clinic/Hospital",
        /* 1 */ "View Visits/Appointments", "View Appointments Data", "View Services Offered", "View Services Providers",
        /* 5 */ "View Diagnosis List", "View Investigations List", "View Dashboard",
        /* 8 */ "Add Visits/Appointments", "Edit Visits/Appointments", "Delete Visits/Appointments",
        /* 11 */ "Add Appointment Data", "Edit Appointment Data", "Delete Appointment Data",
        /* 14 */ "Add Services Offered", "Edit Services Offered", "Delete Services Offered",
        /* 17 */ "Add Service Providers", "Edit Service Providers", "Delete Service Providers",
        /* 20 */ "Add Diagnosis List", "Edit Diagnosis List", "Delete Diagnosis List",
        /* 23 */ "Add Investigations List", "Edit Investigations List", "Delete Investigations List",
        /* 26 */ "Close Visit", "View Appointment Data Items",
        /* 28 */ "Add Appointment Data Items", "Edit Appointment Data Items", "Delete Appointment Data Items",
        /* 31 */ "Generate Sales Invoice", "Cancel Appointment", "Setup Extra Service Data", "View Sales Invoice",
        /* 35 */ "Add Consultation Data", "Edit Consultation Data", "Delete Consultation Data",
        /* 38 */ "Add Presciptions", "Edit Presciptions", "Delete Presciptions",
        /* 41 */ "Add Vitals", "Edit Vitals", "Delete Vitals",
        /* 44 */ "Add Recommended Service", "Edit Recommended Service", "Delete Recommended Service"
    );

    $smplRoleName1 = "Clinic/Hospital Administrator";
    $myName1 = "Clinic/Hospital";
    $myDesc1 = "This module helps you to manage your Clinic/Hospital's Appointments Scheduling and Data Capturing!";
    $cntr = 0;
    $lovID = getLovID("All Enabled Modules");
    createSysLovsPssblVals1($myName, $lovID);
    if (getEnbldPssblValID($myName, $lovID) > 0) {
        checkNAssignReqrmnts(
            $myName,
            $myDesc,
            $audit_tbl_name,
            $smplRoleName,
            $DefaultPrvldgs,
            $subGrpNames,
            $mainTableNames,
            $keyColumnNames
        );
        $cntr++;
    }
    createSysLovsPssblVals1($myName1, $lovID);
    if (getEnbldPssblValID($myName1, $lovID) > 0) {
        checkNAssignReqrmnts(
            $myName1,
            $myDesc1,
            $audit_tbl_name,
            $smplRoleName1,
            $DefaultPrvldgs1,
            $subGrpNames,
            $mainTableNames,
            $keyColumnNames
        );
        $cntr++;
    }
    if ($cntr > 0) {
        createRqrdHospLOVs();
    }
}

function createRqrdHospLOVs()
{

    $sysLovs = array(
        /**0**/
        "Service Types", "Service Providers", "Provider Groups", "Dosage Methods",
        /**4**/
        "Inventory Services", "Diagnosis Types", "Investigation Types", "Laboratory Locations",
        /**8**/
        "Inventory Items", "Recommended Services", "Service Provider Groups", "Item UOM",
        /**12**/
        "Pharmacy Items", "Hospital Patients", "Hospital Staff"
    );

    $sysLovsDesc = array(
        /**0**/
        "Service Types", "Service Providers", "Provider Groups",  "Dosage Methods",
        /**4**/
        "Inventory Services", "Diagnosis Types", "Investigation Types", "Laboratory Locations",
        /**8**/
        "Inventory Items", "Recommended Services", "Service Provider Groups", "Item UOM",
        /**12**/
        "Pharmacy Items", "Hospital Patients", "Hospital Staff"
    );
    $sysLovsDynQrys = array(
        /**0**/
        "select distinct trim(to_char(type_id,'999999999999999999999999999999')) a, trim(type_name ||' ('||type_desc||')') b, '' c from hosp.srvs_types WHERE sys_code != 'VS-0001' order by 2",
        /**1**/
        "SELECT distinct trim(to_char(x.prsn_id,'999999999999999999999999999999')) a, 
    (SELECT trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) FROM prs.prsn_names_nos WHERE person_id = x.prsn_id)
    ||'('||(SELECT type_name from hosp.srvs_types WHERE type_id = x.srvs_type_id)||')' b, '' c, srvs_type_id d FROM hosp.srvs_prvdrs x order by 2",
        /**2**/
        "SELECT distinct trim(to_char(prvdr_grp_id,'999999999999999999999999999999')) a, prvdr_grp_name b, '' c FROM hosp.prvdr_grps order by 2",
        /**3**/
        "",
        /**4**/
        "SELECT distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_desc||' ('||item_code||')' b, '' c, org_id d FROM inv.inv_itm_list WHERE item_type = 'Services'  order by 2",
        /**5**/
        "SELECT distinct trim(to_char(disease_id,'999999999999999999999999999999')) a, trim(disease_name)||' ('||symtms||')' b, '' c  FROM hosp.diseases WHERE is_enabled = '1' order by 1",
        /**6**/
        "SELECT distinct trim(to_char(invstgtn_list_id,'999999999999999999999999999999')) a, invstgtn_name||' ('||invstgtn_desc||')' b, '' c  FROM hosp.invstgtn_list order by 2",
        /**7**/
        "",
        /**8**/
        "SELECT distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_desc || '(' || item_code || ')' b, '' c, org_id d FROM inv.inv_itm_list order by 2",
        /**9**/
        "select distinct trim(to_char(type_id,'999999999999999999999999999999')) a, trim(type_name ||' ('||sys_code||')') b, '' c from hosp.srvs_types where sys_code not in ('MC-0001','IA-0001','LI-0001','PH-0001','RD-0001','VS-0001') order by 2",
        /**10**/
        "SELECT distinct trim(to_char(prvdr_grp_id,'999999999999999999999999999999')) a, prvdr_grp_name b, '' c, main_srvc_type_id d FROM hosp.prvdr_grps x order by 2",
        /**11**/
        "SELECT (SELECT v.uom_name FROM inv.unit_of_measure v WHERE v.uom_id = x.uom_id) a, (SELECT w.uom_desc FROM inv.unit_of_measure w WHERE w.uom_id = x.uom_id) b,
            '' c, z.org_id d, (SELECT w.item_code FROM inv.inv_itm_list w WHERE w.item_id = x.item_id) e
            FROM inv.inv_itm_list z, inv.itm_uoms x WHERE z.item_id = x.item_id 
            union
            SELECT (SELECT y.uom_name FROM inv.unit_of_measure y WHERE y.uom_id = x.base_uom_id) a, (SELECT y.uom_desc FROM inv.unit_of_measure y WHERE y.uom_id = x.base_uom_id) b,
            '' c, x.org_id d, x.item_code e FROM inv.inv_itm_list x  ORDER BY 1",
        /**12**/
        "SELECT distinct trim(to_char(item_id,'999999999999999999999999999999')) a, item_desc || '(' || item_code || ')' b, '' c, x.org_id d 
                FROM inv.inv_itm_list x, inv.inv_product_categories y  WHERE x.category_id = y.cat_id AND cat_name = 'Pharmacy' order by 2",
        /**13**/
        "SELECT distinct person_id|| '' a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names||' ('||local_id_no||')') b, '' c, org_id d FROM prs.prsn_names_nos a WHERE pasn.get_prsn_type(person_id) = 'Patient' order by 1 DESC",
        /**14**/
        "SELECT distinct person_id|| '' a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names||' ('||local_id_no||')') b, '' c, org_id d FROM prs.prsn_names_nos a WHERE pasn.get_prsn_type(person_id) = 'Employee' order by 1 DESC"
    );

    $pssblVals = array(
        "3", "Oral", "To be taken my mouth", "3", "Suppository", "To be taken through the anus, urethra", "3", "External", "External applications", "3", "Injection", "To be injected", "3", "Intra-venous Infusion", "To be infused into the veins", "7", "BOG Clinic", "Bank Of Ghana Clinic Laboratory", "7", "Ridge Hospital", "Ridge Hospital Laboratory"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}


function loadHotlMdl()
{
    $DefaultPrvldgs = array(
        /* 1 */
        "View Hospitality Manager", "View Rooms Dashboard",
        /* 2 */ "View Reservations", "View Check Ins", "View Service Types",
        /* 5 */ "View Restaurant", "View Gym",
        /* 7 */ "Add Service Types", "Edit Service Types", "Delete Service Types",
        /* 10 */ "Add Check Ins", "Edit Check Ins", "Delete Check Ins",
        /* 13 */ "Add Applications", "Edit Applications", "Delete Applications",
        /* 16 */ "Add Gym Types", "Edit Gym Types", "Delete Gym Types",
        /* 19 */ "Add Gym Registration", "Edit Gym Registration", "Delete Gym Registration",
        /* 22 */ "View SQL", "View Record History",
        /* 24 */ "Add Table Order", "Edit Table Order", "Delete Table Order", "Setup Tables",
        /* 28 */ "View Complaints/Observations", "Add Complaints/Observations", "Edit Complaints/Observations",
        "Delete Complaints/Observations",
        /* 32 */ "View only Self-Created Sales", "Cancel Documents", "Take Payments",
        "Apply Adhoc Discounts", "Apply Pre-defined Discounts",
        /* 37 */ "View Rental Item", "Can Edit Unit Price", "View only Branch-Related Documents"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Hospitality Management";
    $myDesc = "This module helps you to manage your organization's Hospitality Needs!";
    $audit_tbl_name = "hotl.hotl_audit_trail_tbl";
    $smplRoleName = "Hospitality Management Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function loadSelfMdl()
{
    $DefaultPrvldgs = array(
        "View Self-Service",
        /* 1 */ "View Internal Payments", "View Payables Invoices", "View Receivables Invoices",
        /* 4 */ "View Leave of Absence", "View Events/Attendances", "View Elections",
        /* 7 */ "View Forums", "View E-Library", "View E-Learning",
        /* 10 */ "View Elections Administration", "View Personal Records Administration",
        "View Forum Administration",
        /* 13 */ "View Self-Service Administration",
        /* 14 */ "View SQL", "View Record History",
        /* 16 */ "Administer Elections",
        /* 17 */ "Administer Leave",
        /* 18 */ "Administer Self-Service", "Make Requests for Others", "Administer Other's Inbox",
        /* 21 */ "View Comments", "Add Comments", "Edit Comments", "Delete Comments"
    );

    $DefaultPrvldgs1 = array(
        "View Self-Service",
        /* 1 */ "View Internal Payments", "View Payables Invoices", "View Receivables Invoices",
        /* 4 */ "View Leave of Absence", "View Events/Attendances", "View Elections",
        /* 7 */ "View Forums", "View E-Library", "View Comments", "Add Comments",
        /* 11 */ "View Grade Requests", "View My Bookings", "View My Appointments",
        /* 14 */ "View My Performance", "View Internal Pay Loan Requests", "View Internal Pay Payment Requests",
        /* 17 */ "View Internal Pay Settlement Requests", "View Help Desk", "View My Appraisal"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Self Service";
    $myDesc = "This module helps your Registered Persons to view and manage their Individual Records when approved!";
    $audit_tbl_name = "self.self_prsn_audit_trail_tbl";

    $smplRoleName = "Self-Service Administrator";
    $smplRoleName1 = "Self-Service (Standard)";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName1, $DefaultPrvldgs1, $subGrpNames, $mainTableNames, $keyColumnNames);
    //,

    $sysLovs = array("All Other Self-Service Setups");
    $sysLovsDesc = array("All Other Self-Service Setups");
    $sysLovsDynQrys = array("");
    $pssblVals = array(
        "0", "Create Links to Main App", "Yes"
    );

    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function loadAttnMdl()
{
    $DefaultPrvldgs = array(
        "View Events And Attendance",
        /* 1 */ "View Attendance Records", "View Time Tables", "View Events",
        /* 4 */ "View Venues", "View Attendance Search", "View SQL", "View Record History",
        /* 8 */ "Add Attendance Records", "Edit Attendance Records", "Delete Attendance Records",
        /* 11 */ "Add Time Tables", "Edit Time Tables", "Delete Time Tables",
        /* 14 */ "Add Events", "Edit Events", "Delete Events",
        /* 17 */ "Add Venues", "Edit Venues", "Delete Venues",
        /* 20 */ "Add Event Results", "Edit Event Results", "Delete Event Results",
        /* 23 */ "View Adhoc Registers", "Add Adhoc Registers", "Edit Adhoc Registers", "Delete Adhoc Registers",
        /* 27 */ "View Event Cost", "Add Event Cost", "Edit Event Cost", "Delete Event Cost",
        /* 31 */ "View Complaints/Observations", "Add Complaints/Observations", "Edit Complaints/Observations", "Delete Complaints/Observations",
        /* 35 */ "View only Self-Created Sales", "Cancel Documents", "Take Payments", "Apply Adhoc Discounts", "Apply Pre-defined Discounts",
        /* 40 */ "Can Edit Unit Price",
        /* 41 */ "View Default Accounts", "Add Default Accounts", "Edit Default Accounts", "Delete Default Accounts"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Events And Attendance";
    $myDesc = "This module helps you to manage your organization's Events And Attendance!";
    $audit_tbl_name = "attn.attn_audit_trail_tbl";

    $smplRoleName = "Events And Attendance Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createAttnRqrdLOVs();
}

function createAttnRqrdLOVs()
{
    $sysLovs = array(
        "Venue Classifications", "Event Classifications",
        "Event Venues", "Attendance Events", "Time Table Event Lines",
        "Time Tables", "Attendance HeadCount Metrics",
        "Visitor Classifications", "Ad hoc Visitors",
        "Labels for Attendance Points", "Event Cost Categories",
        "Event Price Categories", "Attendance Registers", "Attendance Registers (TimeTable)"
    );
    $sysLovsDesc = array(
        "Venue Classifications", "Event Classifications",
        "Event Venues", "Attendance Events", "Time Table Event Lines",
        "Time Tables", "Attendance HeadCount Metrics",
        "Visitor Classifications", "Ad hoc Visitors",
        /* 9 */ "Labels for Attendance Points", "Event Cost Categories",
        "Event Price Categories", "Attendance Registers", "Attendance Registers Linked with TimeTable"
    );
    $sysLovsDynQrys = array(
        "", "",
        "select distinct trim(to_char(venue_id,'999999999999999999999999999999')) a, venue_name b, '' c, org_id d from attn.attn_event_venues where is_enabled='1' order by venue_name",
        "select distinct trim(to_char(event_id,'999999999999999999999999999999')) a, event_name b, '' c, org_id d from attn.attn_attendance_events where is_enabled='1' order by event_name",
        "SELECT distinct trim(to_char(time_table_det_id,'999999999999999999999999999999')) a, 'EVENT: ' || COALESCE(attn.get_event_name(event_id),'') || ' VENUE: ' || COALESCE(attn.get_venue_name(assgnd_venue_id),'') || ' HOST: ' || COALESCE(prs.get_prsn_name(assgnd_host_id),'') b, '' c, 1 d, time_table_id e FROM attn.attn_time_table_details where is_enabled='1' order by 2",
        "select distinct trim(to_char(time_table_id,'999999999999999999999999999999')) a, time_table_name b, '' c, org_id d from attn.attn_time_table_hdrs where is_enabled='1' order by time_table_name",
        "", "", "", "", "",
        "select distinct '' || price_ctgry_id a, price_category b, '' c, event_id d from attn.event_price_categories where is_enabled='1' order by price_category",
        "select distinct '' || recs_hdr_id a, recs_hdr_name || '(' || recs_hdr_desc || ')' b, '' c, org_id d from attn.attn_attendance_recs_hdr",
        "select distinct '' || recs_hdr_id a, recs_hdr_name || '(' || recs_hdr_desc || ')' b, '' c, org_id d from attn.attn_attendance_recs_hdr w where time_table_id>0 and time_table_det_id>0 and (select count(x.price_ctgry_id) from attn.event_price_categories x where x.event_id=(select max(u.event_id) from attn.attn_time_table_details u where u.time_table_det_id=w.time_table_det_id))>0"
    );
    $pssblVals = array(
        "0", "Standard Size Venue", "Standard Size Venue",
        "0", "Medium Size Venue", "Medium Size Venue",
        "0", "Large Size Venue", "Large Size Venue",
        "0", "Small Size Venue", "Small Size Venue",
        "0", "Rented Venue", "Rented Venue",
        "0", "Own Venue", "Own Venue",
        "1", "All-Inclusive Event", "All-Inclusive Event",
        "1", "Group-Specific Event", "Group-Specific Event",
        "6", "Male Attendance", "Total Number of Male Participants",
        "6", "Female Attendance", "Total Number of Female Participants",
        "6", "Total Attendance", "Total Attendance for the Event",
        "7", "Existing Person", "Existing Person",
        "7", "Customer", "Customer",
        "7", "Vehicle", "Vehicle",
        "7", "Visitor", "Visitor",
        "9", "1. CPD", "Continuous Professional Development",
        "9", "2. Exam Score", "Scores Obtained if Examinations were taken",
        "10", "1. Event Fee", "1Income",
        "10", "2. Facility Hiring", "2Expenditure",
        "10", "3. Event Materials", "2Expenditure",
        "10", "4. Advertisement", "2Expenditure",
        "10", "5. Photocopy", "2Expenditure",
        "10", "6. Photographs", "2Expenditure",
        "10", "7. Catering", "2Expenditure",
        "10", "8. Resource Persons", "2Expenditure",
        "10", "9. Administrative Expenses", "2Expenditure"
    );
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function createPayRqrdLOVs()
{
    $sysLovs = array(
        "Item Sets for Payments(Enabled)", "Person Sets for Payments(Enabled)",
        "Pay Run Names/Numbers", "Retro Pay Items", "Pay Balance Items", "Pay Run IDs",
        "All Other Internal Payment Setups", "Internal Pay Investment Types",
        "Internal Pay Loan Types", "Internal Pay Payment Types", "Internal Pay Settlement Types",
        /* 11 */ "Internal Pay Loan Classifications", "Internal Pay Payment Classifications", "Internal Pay Settlement Classifications",
        /* 14 */ "Internal Pay Loan Requests", "Internal Pay Payment Requests", "Internal Pay Settlement Requests",
        /* 17 */ "Internal Pay Item Classifications"
    );
    $sysLovsDesc = array(
        "Item Sets for Payments(Enabled)", "Person Sets for Payments(Enabled)",
        "Pay Run Names/Numbers", "Retro Pay Items", "Pay Balance Items", "Pay Run IDs",
        "All Other Internal Payment Setups", "Internal Pay Investment Types",
        "Internal Pay Loan Types", "Internal Pay Payment Types", "Internal Pay Settlement Types",
        /* 11 */ "Internal Pay Loan Classifications", "Internal Pay Payment Classifications", "Internal Pay Settlement Classifications",
        /* 14 */ "Internal Pay Loan Requests", "Internal Pay Payment Requests", "Internal Pay Settlement Requests",
        /* 17 */ "Internal Pay Item Classifications"
    );
    $sysLovsDynQrys = array(
        "select distinct trim(to_char(z.hdr_id,'999999999999999999999999999999')) a, z.itm_set_name b, '' c, z.org_id d, '' e, '' f, y.user_role_id g from pay.pay_itm_sets_hdr z, pay.pay_sets_allwd_roles y where z.hdr_id = y.itm_set_id and z.is_enabled='1' order by z.itm_set_name",
        "select distinct trim(to_char(z.prsn_set_hdr_id,'999999999999999999999999999999')) a, z.prsn_set_hdr_name b, '' c, z.org_id d, '' e, '' f, y.user_role_id g  from pay.pay_prsn_sets_hdr z, pay.pay_sets_allwd_roles y where z.prsn_set_hdr_id = y.prsn_set_id and z.is_enabled='1' order by z.prsn_set_hdr_name",
        "select distinct mass_pay_name a, mass_pay_desc b, '' c, org_id d, mass_pay_id e from pay.pay_mass_pay_run_hdr where run_status='1' order by mass_pay_id DESC",
        "select '' || item_id a, item_code_name b, '' c, org_id d from org.org_pay_items where is_retro_element='1' and (item_id NOT IN (select distinct z.retro_item_id from org.org_pay_items z)) order by item_code_name",
        "select item_code_name a, item_desc||' ('|| item_id||')' b, '' c, org_id d from org.org_pay_items where item_maj_type='Balance Item' order by item_code_name",
        "select distinct ''||mass_pay_id a, REPLACE(mass_pay_name||'-'||mass_pay_desc, '-' || mass_pay_name,'') b, '' c, org_id d, mass_pay_id e from pay.pay_mass_pay_run_hdr order by mass_pay_id DESC",
        "",
        "select distinct ''||item_type_id a, item_type_name b, '' c, org_id d from pay.loan_pymnt_invstmnt_typs where is_enabled='1' and item_type='INVESTMENT'",
        "select distinct ''||item_type_id a, item_type_name b, '' c, org_id d from pay.loan_pymnt_invstmnt_typs where is_enabled='1' and item_type='LOAN'",
        "select distinct ''||item_type_id a, item_type_name b, '' c, org_id d from pay.loan_pymnt_invstmnt_typs where is_enabled='1' and item_type='PAYMENT'",
        "select distinct ''||item_type_id a, item_type_name b, '' c, org_id d from pay.loan_pymnt_invstmnt_typs where is_enabled='1' and item_type='SETTLEMENT'",
        /* 11 */ "select distinct ''||clsfctn_name a, LPAD(order_number::text, 3, '0')||'-'||clsfctn_desc b, '' c, item_type_id d from pay.loan_pymnt_typ_clsfctn where is_enabled='1' and pay.get_trans_type(item_type_id)='LOAN'",
        /* 12 */ "select distinct ''||clsfctn_name a, LPAD(order_number::text, 3, '0')||'-'||clsfctn_desc b, '' c, item_type_id d from pay.loan_pymnt_typ_clsfctn where is_enabled='1' and pay.get_trans_type(item_type_id)='PAYMENT'",
        /* 13 */ "select distinct ''||clsfctn_name a, LPAD(order_number::text, 3, '0')||'-'||clsfctn_desc b, '' c, item_type_id d from pay.loan_pymnt_typ_clsfctn where is_enabled='1' and pay.get_trans_type(item_type_id)='SETTLEMENT'",
        /* 14 */ "select ''||pay_request_id a, REPLACE(prs.get_prsn_surname(RQSTD_FOR_PERSON_ID) || ' ('
                   || prs.get_prsn_loc_id(RQSTD_FOR_PERSON_ID) || ')', ' ()', '')||'-'||local_clsfctn b, '' c, RQSTD_FOR_PERSON_ID d from pay.pay_loan_pymnt_rqsts where request_status NOT IN ('Not Submitted','Rejected','Withdrawn') and pay.get_trans_type(item_type_id)='LOAN'",
        /* 15 */ "select ''||pay_request_id a, REPLACE(prs.get_prsn_surname(RQSTD_FOR_PERSON_ID) || ' ('
                   || prs.get_prsn_loc_id(RQSTD_FOR_PERSON_ID) || ')', ' ()', '')||'-'||local_clsfctn b, '' c, RQSTD_FOR_PERSON_ID d from pay.pay_loan_pymnt_rqsts where request_status NOT IN ('Not Submitted','Rejected','Withdrawn') and pay.get_trans_type(item_type_id)='PAYMENT'",
        /* 16 */ "select ''||pay_request_id a, REPLACE(prs.get_prsn_surname(RQSTD_FOR_PERSON_ID) || ' ('
                   || prs.get_prsn_loc_id(RQSTD_FOR_PERSON_ID) || ')', ' ()', '')||'-'||local_clsfctn b, '' c, RQSTD_FOR_PERSON_ID d from pay.pay_loan_pymnt_rqsts where request_status NOT IN ('Not Submitted','Rejected','Withdrawn') and pay.get_trans_type(item_type_id)='SETTLEMENT'",
        /* 17 */ "select DISTINCT ''||local_classfctn a, local_classfctn b, '' c from org.org_pay_items"
    );

    $pssblVals = array(
        "6", "Html POS Receipt File Name", "htm_rpts/pay_pos_rpt.php"
    );
    /* ,
      "10", "None", "All",
      "11", "None", "All"
      $vPsblValID1 = getEnbldPssblValID("Application Instance SHORT CODE", getLovID("All Other General Setups"));
      $vPsblVal1 = getPssblValDesc($vPsblValID1);
      if ($vPsblVal1 == "TAKBG_SWLFR_APP_1") {
      $pssblVals = array(
      "6", "Html POS Receipt File Name", "htm_rpts/pay_pos_rpt.php",
      "10", "None", "All",
      "10", "Semi-Month", "TAKBG_SWLFR_APP_1",
      "10", "January Clothing", "TAKBG_SWLFR_APP_1",
      "10", "February PF Interest", "TAKBG_SWLFR_APP_1",
      "10", "March PF", "TAKBG_SWLFR_APP_1",
      "10", "July Clothing", "TAKBG_SWLFR_APP_1",
      "10", "September PF", "TAKBG_SWLFR_APP_1",
      "10", "December Bonus", "TAKBG_SWLFR_APP_1",
      "11", "None", "All");
      } */
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function loadAcaMdl()
{
    $DefaultPrvldgs = array(
        /* 1 */
        "View Summary Reports", "View Learning/Performance Management",
        /* 2 */ "View Assessment Sheets", "View Task Assignment Setups", "View Groups/Courses/Subjects",
        /* 5 */ "View Position Holders", "View Assessment Periods", "View Assessment Reports Types",
        /* 8 */ "View Record History", "View SQL",
        /* 10 */ "Add Subjects/Tasks", "Edit Subjects/Tasks", "Delete Subjects/Tasks",
        /* 13 */ "Add Courses/Objectives", "Edit Courses/Objectives", "Delete Courses/Objectives",
        /* 16 */ "Add Assessment Types", "Edit Assessment Types", "Delete Assessment Types",
        /* 19 */ "Add Assessment Periods", "Edit Assessment Periods", "Delete Assessment Periods",
        /* 22 */ "Add Position Holders", "Edit Position Holders", "Delete Position Holders",
        /* 25 */ "Add Groups/Classes", "Edit Groups/Classes", "Delete Groups/Classes",
        /* 28 */ "Add Registrations", "Edit Registrations", "Delete Registrations",
        /* 31 */ "Add Assessment Sheets", "Edit Assessment Sheets", "Delete Assessment Sheets",
        /* 34 */ "Add Summary Reports", "Edit  Summary Reports", "Delete  Summary Reports",
        /* 37 */ "View Only Self-Created Sheets", "View Only Self-Created Summary Reports",
        /* 39 */ "View Appraisal"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Learning/Performance Management";
    $myDesc = "This module helps you to manage your organization's Learning/Performance Assessment Needs!";
    $audit_tbl_name = "aca.aca_audit_trail_tbl";

    $smplRoleName = "Learning/Performance Management Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createAcaRqrdLOVs();
}

function createAcaRqrdLOVs()
{
    $sysLovs = array(
        "Assessment Types", "ACA Courses", "ACA Subjects", "PMS Objectives", "PMS Tasks",
        /* 5 */ "All Other Performance Setups", "Assessment Groups", "Assessment Objectives/Courses", "Assessment Periods",
        /* 9 */ "Assessment Tasks/Subjects", "All Assessment Sheets", "Grade Scales/Schemes"
    );

    $sysLovsDesc = array(
        "Assessment Types", "ACA Courses", "ACA Subjects", "PMS Objectives", "PMS Tasks",
        /* 5 */ "All Other Performance Setups", "Assessment Groups", "Assessment Objectives/Courses", "Assessment Periods",
        /* 9 */ "Assessment Tasks/Subjects", "All Assessment Sheets", "Grade Scales/Schemes"
    );

    $sysLovsDynQrys = array(
        "select ''||assmnt_typ_id a, assmnt_typ_nm b, '' c, org_id d, assmnt_type e from aca.aca_assessment_types where is_enabled='1' order by assmnt_typ_nm",
        "select ''||course_id a, course_code||'.'||course_name b, '' c, org_id d from aca.aca_courses where is_enabled='1' and record_type='Course' order by course_code",
        "select ''||subject_id a, subject_code||'.'||subject_name b, '' c, org_id d from aca.aca_subjects where is_enabled='1' and record_type='Subject' order by subject_code",
        "select ''||course_id a, course_code||'.'||course_name b, '' c, org_id d from aca.aca_courses where is_enabled='1' and record_type='Objective' order by course_code",
        "select ''||subject_id a, subject_code||'.'||subject_name b, '' c, org_id d from aca.aca_subjects where is_enabled='1' and record_type='Task' order by subject_code",
        /* 5 */ "",
        "select ''||class_id a, class_name||' ('||group_type||')' b, '' c, org_id d, group_type e from aca.aca_classes where is_enabled='1' order by class_name",
        "select ''||z.course_id a, z.course_code||'.'||z.course_name b, '' c, p.class_id d, z.record_type e from aca.aca_classes_n_thr_crses p, aca.aca_courses z where z.course_id=p.course_id and z.is_enabled='1' order by z.course_code",
        "select ''||assmnt_period_id a, assmnt_period_name||' ('||to_char(to_timestamp(period_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY')||')' b, period_start_date c, org_id d from aca.aca_assessment_periods where 1=1 order by period_start_date desc",
        "select ''||z.subject_id a, z.subject_code||'.'||z.subject_name b, '' c, p.class_id d, ''||p.course_id e from aca.aca_crsrs_n_thr_sbjcts p, aca.aca_subjects z where z.subject_id=p.subject_id and z.is_enabled='1' order by z.subject_code",
        "Select '' || assess_sheet_hdr_id a, assess_sheet_name b, '' c, org_id d, aca.get_assesstyp(assessment_type_id) e from aca.aca_assess_sheet_hdr order by 2",
        "Select distinct '' || scale_id a, scale_name b, '' c, org_id d from aca.aca_grade_scales"
    );

    $pssblVals = array(
        "5", "Default Course/Objective/Programme Label ACA", "Programme",
        "5", "Default Course/Objective/Programme Label PMS", "Objective",
        "5", "Default Subject/Task Label ACA 1", "Course",
        "5", "Default Subject/Task Label ACA 2", "Subject",
        "5", "Default Subject/Task Label PMS", "Target",
        "5", "Default Group Label ACA", "Class",
        "5", "Default Group Label PMS", "Department",
        "5", "Default Assessment Sheet Level ACA", "Subject/Task",
        "5", "Default Assessment Sheet Level PMS", "Course/Objective/Programme",
        "5", "Html Report Card Print File Name", "htm_rpts/terminal_rpt.php",
        "5", "Html Registration Slip Print File Name", "htm_rpts/rgstrtn_slip.php",
        "5", "ACA Registration Eligibility Criteria SQL (YES/NO)", "SELECT aca.isPrsnElgblToRgstr ({:person_id}, ';Student;Pupil;', 60, 'Fees Payment Items Set', 'Fees Bills Items Set', 'Fees Balances Items Set')",
        "5", "PMS Registration Eligibility Criteria SQL (YES/NO)", "SELECT aca.isPrsnElgblToRgstr ({:person_id}, ';Staff;Employee;', 0, 'Fees Payment Items Set', 'Fees Bills Items Set', 'Fees Balances Items Set')",
        "5", "ACA Results Display Criteria SQL (YES/NO)", "SELECT 'YES:'",
        "5", "PMS Results Display Criteria SQL (YES/NO)", "SELECT 'YES:'",
        "5", "vClass URL", "https://vclass.rhomicom.com/login/index.php"
    );
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function loadMcfMdl()
{
    $DefaultPrvldgs = array(
        "View Banking",
        /* 1 */ "View SQL", "View Record History", "View Standard Reports",
        /* 4 */ "View Customer Management", "View Customer Accounts", "View Core Banking",
        /* 7 */ "View Credit Management", "View Investment Management", "View Vault Management", "View Product Setup",
        /* 11 */ "View Utilities & Configuration", "View Treasury Management", "View Standard Banking Reports",
        /* 14 */ "View Individual Customers", "View Corporate Customer", "View Customer Groups", "View Other Persons",
        /* 18 */ "View All Customer Correspondence", "View Customer Additional Data Setup", "View Customer Accounts", "View Teller Operations",
        /* 22 */ "View Uncleared Transactins", "View Miscellaneous Account Transactions", "View Cheque Management", "View Account Transfers and Standing Orders",
        /* 26 */ "View Loan Applications", "View Disbursements", "View Loan Repayments", "View Loan Calculator",
        /* 30 */ "View Cash Loan Payments", "View Loans Summary Dashboard", "View Investments Summary Dashboard", "View Investments",
        /* 34 */ "View Investment Rediscounts", "View Savings and Investment Products", "View Credit Products", "View Services",
        /* 38 */ "View GL Interface", "View Currencies", "View Teller Tills", "View Exchange Rates",
        /* 42 */ "View Default Transaction Accounts", "View All Banks and Branches", "View End of Day", "View Cash Flow Analysis",
        /* 46 */ "View Corporate Investments", "View My Till Position", "View Withdrawal Transactions", "View Deposit Transactions",
        /* 50 */ "View Forex Sales", "View Forex Purchase", "View Loan Repayment", "View Mobile and Other Money Transfers",
        /* 54 */ "View Utility Payments", "View Banker's Draft", "View All Account Transactions", "View Mobile and Other Money Transfers",
        /* 58 */ "View Cheque Book Register", "View Cheques Register",
        /* 60 */ "Add Individual Customer", "Edit Individual Customer", "Delete Individual Customer", "Authorize Individual Customer",
        /* 64 */ "Add Corporate Customer", "Edit Corporate Customer", "Delete Corporate Customer", "Authorize Corporate Customer",
        /* 68 */ "Add Customer Group", "Edit Customer Group", "Delete Customer Group", "Authorize Customer Group",
        /* 72 */ "Add Other Person", "Edit Other Person", "Delete Other Person", "Authorize Other Person",
        /* 76 */ "Add Customers - Local", "Add Customers - Global",
        /* 78 */ "Add Additional Customer Data", "Delete Additional Customer Data",
        /* 80 */ "Add Customer Account", "Edit Customer Account", "Delete Customer Account", "Authorize Customer Account",
        /* 84 */ "Add Customer Account - Local", "Add Customer Account - Global", "Create Loan Repayment Accounts",
        /* 87 */ "Add Account Lien", "Edit Account Lien", "Delete Account Lien",
        /* 90 */ "View Risk Factors", "View Risk Profiles", "View Assessment Sets",
        /* 93 */ "Add Withdrawal Transaction", "Edit Withdrawal Transactions", "Delete/Void Withdrawal Transactions", "Authorize Withdrawal Transactions",
        /* 97 */ "Add Deposit Transaction", "Edit Deposit Transactions", "Delete/Void Deposit Transactions", "Authorize Deposit Transactions",
        /* 101 */ "Add Forex Sale Transaction", "Edit Forex Sale Transactions", "Delete/Void Forex Sale Transactions", "Authorize Forex Sale Transactions",
        /* 105 */ "Add Forex Purchase Transaction", "Edit Forex Purchase Transactions", "Delete/Void Forex Purchase Transactions", "Authorize Forex Purchase Transactions",
        /* 109 */ "Add Loan Repayment", "Edit Loan Repayment", "Delete/Void Loan Repayment", "Authorize Loan Repayment",
        /* 113 */ "Add Mobile & Other Money Transfers", "Edit Mobile & Other Money Transfers", "Delete Mobile & Other Money Transfers", "Authorize Mobile & Other Money Transfers",
        /* 117 */ "Add Utility Payment", "Edit Utility Payment", "Delete Loan Utility Payment", "Authorize Loan Utility Payment",
        /* 121 */ "Add Banker's Draft", "Edit Banker's Draft", "Delete Banker's Draft", "Authorize Banker's Draft",
        /* 125 */ "Add Cheque Book Register", "Edit Cheque Book", "Delete Cheque Book Register", "Authorize  Cheque Book Draft Register",
        /* 129 */ "Add Cheques Register", "Edit Cheques Register", "Delete Cheques Register", "Authorize  Cheques Register",
        /* 133 */ "Auto-Execute All Transactions Due", "Execute Standing Order",
        /* 135 */ "Add Adjustment Transaction", "Edit Adjustment Transaction", "Delete Adjustment Transaction", "Authorize Adjustment Transaction",
        /* 139 */ "Add Standing Order", "Edit Standing Order", "Delete Standing Order", "Authorize Standing Order",
        /* 143 */ "Add Loan Application", "Edit Loan Application", "Delete/Void Loan Application", "Authorize Loan Application",
        /* 147 */ "Add Disbursement", "Edit Disbursement", "Delete Disbursement", "Void Disbursement",
        /* 151 */ "Add Loan Payment", "Edit Loan Payment", "Delete/Void Loan Payment", "Authorize Loan Payment",
        /* 155 */ "Add Investment", "Edit Investment", "Delete Investment", "Authorize Investment",
        /* 159 */ "Add Investment Rediscount", "Edit Investment Rediscount", "Delete Investment Rediscount", "Authorize Investment Rediscount",
        /* 163 */ "Add Savings and Investment Products", "Edit Savings and Investment Products", "Delete Savings and Investment Products", "Authorize Savings and Investment Products",
        /* 167 */ "Add Credit Products", "Edit Credit Products", "Delete Credit Products", "Authorize Credit Products",
        /* 171 */ "Add Services", "Edit Services", "Delete Services", "Authorize Services",
        /* 175 */ "Add Currencies", "Edit Currencies", "Delete Currencies", "Authorize Currencies",
        /* 179 */ "Add Tell Tills", "Edit Tell Tills", "Delete Tell Tills", "Authorize Tell Tills",
        /* 183 */ "Add Exchange Rate", "Edit Exchange Rate", "Delete Exchange Rate", "Authorize Exchange Rate",
        /* 187 */ "Add Exchange Rate", "Edit Exchange Rate", "Delete Exchange Rate", "Authorize Exchange Rate",
        /* 189 */ "Add Default Transaction Account", "Edit Default Transaction Account", "Delete Default Transaction Account", "Authorize Default Transaction Account",
        /* 193 */ "Add Bank", "Edit Bank", "Delete Bank", "Authorize Bank",
        /* 197 */ "Add Branch", "Edit Branch", "Delete Branch", "Authorize Branch",
        /* 203 */ "Run End of Day", "Void End of Day",
        /* 205 */ "Add Corporate Investment", "Edit Corporate Investment", "Delete Corporate Investment", "Authorize Corporate Investment",
        /* 209 */ "Add Authorization Limit", "Edit Authorization Limit", "Delete Authorization Limit",
        /* 212 */ "Search All Transactions", "See non-related Transactions",
        /* 214 */ "Can Send to GL",
        /* 215 */ "Export Individual Customer", "Import Individual Customer",
        /* 217 */ "Export National IDs", "Import National IDs",
        /* 219 */ "Export Additional Data - Individual Customer", "Import Additional Data - Individual Customer",
        /* 221 */ "Export Corporate Customer", "Import Corporate Customer",
        /* 223 */ "Export Corporate Directors", "Import Corporate Directors",
        /* 225 */ "Export Additional Data - Corporate Customer", "Import Additional Data - Corporate Customer",
        /* 227 */ "Export Group Customer", "Import Group Customer",
        /* 229 */ "Export Group Members", "Import Group Members",
        /* 231 */ "Export Additional Data - Group Customer", "Import Additional Data - Group Customer",
        /* 233 */ "Export Other Persons", "Import Other Persons",
        /* 235 */ "Export National IDs - Other Persons", "Import National IDs - Other Persons",
        /* 237 */ "Export Additional Data - Other Persons", "Import Additional Data -Other Persons",
        /* 239 */ "Export Customer Accounts", "Import Customer Accounts",
        /* 241 */ "Export Customer Account Signtories", "Import Customer Account Signtories",
        /* 243 */ "Export Customer Accounts Liens", "Import Customer Accounts Liens",
        /* 245 */ "View Credit Risk Assessment", "View Property Collaterals", "View Loan Sector Classifications",
        /* 248 */ "Block Account", "Close Accounts", "Re-Open Account", "Unblock Account",
        /* 252 */ "View Loan Classification", "Add Loan Classification", "Edit Loan Classification", "Delete Loan Classification",
        /* 256 */ "View Global Variables", "Add Global Variable", "Edit Global Variable",
        /* 259 */ "Reject Individual Customer", "Reject Corporate Customer", "Reject Group Customer", "Reject Other Customer",
        /* 263 */ "Reject Customer Accounts", "Can Clear Transactions",
        /* 265 */ "Add Risk Factors", "Edit Risk Factors", "Delete Risk Factors",
        /* 268 */ "Add Risk Profile", "Edit Risk Profile", "Delete Risk Profile",
        /* 271 */ "Add Assessment Set", "Edit Assessment Set", "Delete Assessment Set",
        /* 274 */ "Add Property Collaterals", "Edit Property Collaterals", "Delete Property Collaterals",
        /* 277 */ "Add Sector Classifications", "Edit Sector Classifications", "Delete Sector Classifications",
        /* 280 */ "Add Loan Write-Off", "Edit Loan Write-Off", "Delete Loan Write-Off", "View Loan Write-Off",
        /* 284 */ "Authorize Loan Write-Off", "Process Loan Write-Off",
        /* 286 */ "Add Overdraft Interest Process", "Edit Overdraft Interest Process", "Delete Overdraft Interest Process", "View Overdraft Interest Process",
        /* 290 */ "Authorize Overdraft Interest Process", "Process Overdraft Interest Process",
        /* 292 */ "View Bulk Teller Transactions", "Add Bulk Teller Transactions", "Edit Bulk Teller Transactions", "Delete Bulk Teller Transactions",
        "Authorize Bulk Teller Transactions",
        /* 297 */ "See Other Branch Transactions", "See Other Staff Account Transactions", "Can Modify Customer Account Number"
    );
    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "Banking";
    $myDesc = "This is where all Banking & Microfinance Operations in a Bank are carried out!";
    $audit_tbl_name = "mcf.mcf_audit_trail_tbl";

    $smplRoleName = "Banking Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);

    $sysLovs = array(
        /* 0 */
        "All Bank Customers",
        /* 1 */ "All Bank Customers - Account Holders",
        /* 2 */ "All Bank Accounts (For Loan Repayment)",
        /* 3 */ "Account Transaction Types",
        /* 4 */ "Bank Individual Customers",
        /* 5 */ "Bank Other Persons",
        /* 6 */ "Bank Other Persons - Guarantors",
        /* 7 */ "Bank Currencies (Select List)",
        /* 8 */ "Bank Account Types",
        /* 9 */ "Bank Account Mandate",
        /* 10 */ "Bank Product Types",
        /* 11 */ "Bank Customer Types",
        /* 12 */ "Bank Currencies (LOV)",
        /* 13 */ "Bank Branches MCF",
        /* 14 */ "Bank Account Person Types/Entity - INDV",
        /* 15 */ "Bank Account Person Types/Entity - CGRP",
        /* 16 */ "Bank Account Person Types/Entity - CORP",
        /* 17 */ "Customer Attachment Types",
        /* 18 */ "Customer Classifications",
        /* 19 */ "Interest Accrual Frequency",
        /* 20 */ "Interest Posting Frequencies",
        /* 21 */ "Interest Calculation Methods",
        /* 22 */ "Interest Calculation Types",
        /* 23 */ "Investment Collaterals",
        /* 24 */ "Loan Products",
        /* 25 */ "Purpose of Account",
        /* 26 */ "Property Collaterals",
        /* 27 */ "Sites/Locations New",
        /* 28 */ "Source of Funds",
        /* 29 */ "Savings Product Types",
        /* 30 */ "Types of Incorporation",
        /* 31 */ "Withdrawal Document Types",
        /* 32 */ "MCF All Bank Acccounts(Advanced Search)",
        /* 33 */ "MCF All Bank Accounts",
        /* 34 */ "MCF All Bank Accounts For Cash Collateral",
        /* 35 */ "MCF All Bank Loan Accounts",
        /* 36 */ "MCF All Banks",
        /* 37 */ "MCF All Individual Customers",
        /* 38 */ "MCF Approved Customer Loans Per Product",
        /* 39 */ "MCF Bank Other Persons - Directors",
        /* 40 */ "MCF Corporate/Group Customers",
        /* 41 */ "MCF Corporate/Individual Loans TBD",
        /* 42 */ "MCF Corporate N Individual Loans TBD",
        /* 43 */ "MCF Customer Form Types",
        /* 44 */ "MCF Customer Group Members",
        /* 45 */ "MCF Customer Groups",
        /* 46 */ "MCF Deposit Cheque Types",
        /* 47 */ "MCF Deposit Document Types",
        /* 48 */ "MCF Disbursed Loans for Manual Payments",
        /* 49 */ "MCF Group Member Positions",
        /* 50 */ "MCF Group Members Loans TBD",
        /* 51 */ "MCF Loan Products - Corporate",
        /* 52 */ "MCF Loan Products - Group",
        /* 53 */ "MCF Loan Products - Individual",
        /* 54 */ "MCF Manual Loan Payment Accounts",
        /* 55 */ "MCF Manual Payment Disbursements",
        /* 56 */ "MCF Meeting Days",
        /* 57 */ "MCF Other Person Types",
        /* 58 */ "MCF Person Types",
        /* 59 */ "MCF Recovery Officers",
        /* 60 */ "Customer Classifications - Corporate",
        /* 61 */ "Customer Classifications - Customer Group",
        /* 62 */ "MCF Credit Risk Factors",
        /* 63 */ "MCF Credit Risk Profiles - Valid",
        /* 64 */ "MCF Assessemnt Set - Valid",
        /* 65 */ "MCF Loan Processing Fee Types",
        /* 66 */ "MCF Loan Late Fee Targets",
        /* 67 */ "MCF Loan Sectors - Major",
        /* 68 */ "MCF Loan Sectors - Minor",
        /* 69 */ "MCF Investment Account Deposits",
        /* 70 */ "MCF Investment Products",
        /* 71 */ "MCF Bank Branch Current Accounts",
        /* 72 */ "MCF Client Ratings",
        /* 73 */ "Sample Tellering Narrations",
        /* 74 */ "MCF Property Collateral Types",
        /* 75 */ "MCF All Customer Accounts",
        /* 76 */ "MCF Loan Canditates For Write-Off",
        /* 77 */ "MCF End of Day Run Modes",
        /* 78 */ "MCF End of Day Process Types",
        /* 79 */ "MCF Customer Request Types",
        /* 80 */ "MCF Requestor Types",
        /* 81 */ "MCF Bank Other Persons - Next of Kin",
        /* 82 */ "MCF Disbursed Loans",
        /* 83 */ "MCF Bank Other Persons - Directors, Joint and Trust Persons",
        /* 84 */ "All Bank Customers - Investment",
        /* 85 */ "MCF Corporate/Group Customers - Investment",
        /* 86 */ "MCF Customer Bank Accounts - (For Investment)",
        /* 87 */ "MCF Customer Investments",
        /* 88 */ "MCF Liquidation Purposes",
        /* 89 */ "All Persons IDs 2",
        /* 90 */ "MCF OD Facilities With Unpaid Accrued Interest",
        /* 91 */ "MCF All Loan Products",
        /* 92 */ "MCF All Savings and Investments Products",
        /* 93 */ "MCF All Customer Investments",
        /* 94 */ "MCF SMS Triggers",
        /* 95 */ "Bank Product Types (for Reports)",
        /* 96 */ "Loan Repayment Accounts"
    );

    $sysLovsDesc = array(
        /* 0 */
        "All Bank Customers",
        /* 1 */ "All Bank Customers - Account Holders",
        /* 2 */ "All Bank Accounts (For Loan Repayment)",
        /* 3 */ "Account Transaction Types",
        /* 4 */ "Bank Individual Customers",
        /* 5 */ "Bank Other Persons",
        /* 6 */ "Bank Other Persons - Guarantors",
        /* 7 */ "Bank Currencies (Select List)",
        /* 8 */ "Bank Account Types",
        /* 9 */ "Bank Account Mandate",
        /* 10 */ "Bank Product Types",
        /* 11 */ "Bank Customer Types",
        /* 12 */ "Bank Currencies (LOV)",
        /* 13 */ "Bank Branches MCF",
        /* 14 */ "Bank Account Person Types/Entity - INDV",
        /* 15 */ "Bank Account Person Types/Entity - CGRP",
        /* 16 */ "Bank Account Person Types/Entity - CORP",
        /* 17 */ "Customer Attachment Types",
        /* 18 */ "Customer Classifications",
        /* 19 */ "Interest Accrual Frequency",
        /* 20 */ "Interest Posting Frequencies",
        /* 21 */ "Interest Calculation Methods",
        /* 22 */ "Interest Calculation Types",
        /* 23 */ "Investment Collaterals",
        /* 24 */ "Loan Products",
        /* 25 */ "Purpose of Account",
        /* 26 */ "Property Collaterals",
        /* 27 */ "Sites/Locations New",
        /* 28 */ "Source of Funds",
        /* 29 */ "Savings Product Types",
        /* 30 */ "Types of Incorporation",
        /* 31 */ "Withdrawal Document Types",
        /* 32 */ "MCF All Bank Acccounts(Advanced Search)",
        /* 33 */ "MCF All Bank Accounts",
        /* 34 */ "MCF All Bank Accounts For Cash Collateral",
        /* 35 */ "MCF All Bank Loan Accounts",
        /* 36 */ "MCF All Banks",
        /* 37 */ "MCF All Individual Customers",
        /* 38 */ "MCF Approved Customer Loans Per Product",
        /* 39 */ "MCF Bank Other Persons - Directors",
        /* 40 */ "MCF Corporate/Group Customers",
        /* 41 */ "MCF Corporate/Individual Loans TBD",
        /* 42 */ "MCF Corporate N Individual Loans TBD",
        /* 43 */ "MCF Customer Form Types",
        /* 44 */ "MCF Customer Group Members",
        /* 45 */ "MCF Customer Groups",
        /* 46 */ "MCF Deposit Cheque Types",
        /* 47 */ "MCF Deposit Document Types",
        /* 48 */ "MCF Disbursed Loans for Manual Payments",
        /* 49 */ "MCF Group Member Positions",
        /* 50 */ "MCF Group Members Loans TBD",
        /* 51 */ "MCF Loan Products - Corporate",
        /* 52 */ "MCF Loan Products - Group",
        /* 53 */ "MCF Loan Products - Individual",
        /* 54 */ "MCF Manual Loan Payment Accounts",
        /* 55 */ "MCF Manual Payment Disbursements",
        /* 56 */ "MCF Meeting Days",
        /* 57 */ "MCF Other Person Types",
        /* 58 */ "MCF Person Types",
        /* 59 */ "MCF Recovery Officers",
        /* 60 */ "Customer Classifications - Corporate",
        /* 61 */ "Customer Classifications - Customer Group",
        /* 62 */ "MCF Credit Risk Factors",
        /* 63 */ "MCF Credit Risk Profiles - Valid",
        /* 64 */ "MCF Assessemnt Set - Valid",
        /* 65 */ "MCF Loan Processing Fee Types",
        /* 66 */ "MCF Loan Late Fee Targets",
        /* 67 */ "MCF Loan Sectors - Major",
        /* 68 */ "MCF Loan Sectors - Minor",
        /* 69 */ "MCF Investment Account Deposits",
        /* 70 */ "MCF Investment Products",
        /* 71 */ "MCF Bank Branch Current Accounts",
        /* 72 */ "MCF Client Ratings",
        /* 73 */ "Sample Tellering Narrations",
        /* 74 */ "MCF Property Collateral Types",
        /* 75 */ "MCF All Customer Accounts",
        /* 76 */ "MCF Loan Canditates For Write-Off",
        /* 77 */ "MCF End of Day Run Modes",
        /* 78 */ "MCF End of Day Process Types",
        /* 79 */ "MCF Customer Request Types",
        /* 80 */ "MCF Requestor Types",
        /* 81 */ "MCF Bank Other Persons - Next of Kin",
        /* 82 */ "MCF Disbursed Loans",
        /* 83 */ "MCF Bank Other Persons - Directors, Joint and Trust Persons",
        /* 84 */ "All Bank Customers - Investment",
        /* 85 */ "MCF Corporate/Group Customers - Investment",
        /* 86 */ "MCF Customer Bank Accounts - (For Investment)",
        /* 87 */ "MCF Customer Investments",
        /* 88 */ "MCF Liquidation Purposes",
        /* 89 */ "All Persons IDs 2",
        /* 90 */ "MCF OD Facilities With Unpaid Accrued Interest",
        /* 91 */ "MCF All Loan Products",
        /* 92 */ "MCF All Savings and Investments Products",
        /* 93 */ "MCF All Customer Investments",
        /* 94 */ "MCF SMS Triggers",
        /* 95 */ "Bank Product Types (for Reports)",
        /* 96 */ "Loan Repayment Accounts"
    );

    $sysLovsDynQrys = array(
        /* 0 */
        "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)||' ['||x.local_id_no||']' b, '' c,
        x.org_id d,  y.cust_type e, y.branch_id f from mcf.mcf_customers_ind x, mcf.mcf_customers y  where (x.cust_id = y.cust_id) AND y.status = 'Authorized'
        UNION
        select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
        trim(cust_name)||' ['||x.local_id_no||']' b, '' c,
        x.org_id d,  y.cust_type e, y.branch_id from mcf.mcf_customers_corp x, mcf.mcf_customers y  where (x.cust_id = y.cust_id) AND y.status = 'Authorized'
         order by 2
        ",
        /* 1 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)||' ['||y.account_number||']'  b, '' c, 
        x.org_id d,  y.cust_type e, y.branch_id f from mcf.mcf_customers_ind x, mcf.mcf_accounts y  where (x.cust_id = y.cust_id) AND y.status = 'Authorized'
        UNION
        select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a, 
        trim(cust_name)||' ['||y.account_number||']' b, '' c, 
        x.org_id d,  y.cust_type e, y.branch_id f from mcf.mcf_customers_corp x, mcf.mcf_accounts y  where (x.cust_id = y.cust_id) AND y.status = 'Authorized'
        order by 2",
        /* 2 */ "select distinct trim(to_char(y.account_id,'999999999999999999999999999999')) a, 
        trim(account_number) b, '' c, 
        y.org_id d, cust_id e from mcf.mcf_accounts y where (account_type in ('Savings','Current','Susu') and (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)) AND y.status = 'Authorized' 
        order by 2",
        /* 3 */ "",
        /* 4 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) || ' Cust No: ['||id_no||']' || ' AcNo:'||trim(account_number) b, '' c, 
        x.org_id d,  y.cust_type e 
		from mcf.mcf_customers_ind x
		LEFT OUTER JOIN mcf.mcf_customers y ON (x.cust_id = y.cust_id) 
		LEFT OUTER JOIN mcf.mcf_accounts z ON (x.cust_id = z.cust_id) 
		where (1=1) AND y.status = 'Authorized'
		AND z.account_type != 'Loan' ORDER BY 2",
        /* 5 */ "select distinct trim(to_char(y.person_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)||' ['||y.local_id_no||']' b, '' c, 
        y.org_id d,  y.is_signatory e from mcf.mcf_prsn_names_nos y where (1 = 1) AND y.status = 'Authorized' 
         order by 2",
        /* 6 */ "select distinct trim(to_char(y.person_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, 
        y.org_id d,  y.prsn_type e from mcf.mcf_prsn_names_nos y where (1 = 1)
        AND y.prsn_type ilike '%Guarantor%' AND y.status = 'Authorized'
         order by 2",
        /* 7 */ "select distinct iso_code a, iso_code||' ('||description||')' b, '' c, org_id d from 
        mcf.mcf_currencies order by 2",
        /* 8 */ "",
        /* 9 */ "",
        /* 10 */ "select distinct trim(to_char(svngs_product_id,'999999999999999999999999999999')) a, product_name b, '' c, 
        '' d,  product_type e from mcf.mcf_prdt_savings where (1=1) AND status = 'Authorized' AND product_name != 'Loans Payment Account Product' AND status = 'Authorized'
        UNION
        select distinct trim(to_char(svngs_product_id,'999999999999999999999999999999')) a, product_name b, '' c, 
        '' d,  product_type e from mcf.mcf_prdt_savings where (1=1) AND status = 'Authorized' AND product_name != 'Loans Payment Account Product' AND status = 'Authorized'
        order by 2",
        /* 11 */ "",
        /* 12 */ "select distinct trim(to_char(crncy_id,'999999999999999999999999999999')) a, iso_code||' ('||description||')' b, '' c, org_id d from 
        mcf.mcf_currencies order by 2",
        /* 13 */ "select distinct trim(to_char(branch_id,'999999999999999999999999999999')) a, branch_name b, '' c, bank_id d from mcf.mcf_bank_branches order by 2",
        /* 14 */ "",
        /* 15 */ "",
        /* 16 */ "",
        /* 17 */ "",
        /* 18 */ "",
        /* 19 */ "",
        /* 20 */ "",
        /* 21 */ "",
        /* 22 */ "",
        /* 23 */ "select distinct trim(to_char(y.invstmnt_id,'999999999999999999999999999999')) a, 
        trim(trnsctn_no) b, '' c, 
        y.branch_id d, y.cust_type e, y.cust_id f from mcf.mcf_investments y where (status in ('Approved','Authorized'))
        order by 2",
        /* 24 */ "select distinct trim(to_char(x.loan_product_id,'999999999999999999999999999999')) a, 
        product_name b, '' c, 
        x.org_id d from mcf.mcf_prdt_loans x WHERE x.status = 'Authorized'",
        /* 25 */ "",
        /* 26 */ "select distinct trim(to_char(y.prpty_collateral_id,'999999999999999999999999999999')) a, 
        trim(collateral_name) b, '' c, 
        y.org_id d, y.owner_cust_type e, y.owner_cust_id f from mcf.mcf_property_collaterals y where (status in ('Approved','Authorized'))
        order by 2",
        /* 27 */ "select distinct trim(to_char(location_id,'999999999999999999999999999999')) a, site_desc||' ('||location_code_name||')' b, '' c, org_id d from org.org_sites_locations WHERE is_enabled = '1' order by 2",
        /* 28 */ "",
        /* 29 */ "",
        /* 30 */ "",
        /* 31 */ "",
        /* 32 */ "select distinct trim(to_char(account_id,'999999999999999999999999999999')) a, 
        trim(account_number)||' ['||account_title||'],['||account_type||']' b, '' c, 
        y.org_id d from mcf.mcf_accounts y where account_type in ('Savings','Current','Susu') AND y.status = 'Authorized'
        order by 2",
        /* 33 */ "select distinct trim(to_char(account_id,'999999999999999999999999999999')) a, 
        trim(account_number)||' ['||account_title||' ('||mcf.get_cust_local_idno(cust_id)||')]' b, '' c, 
        y.org_id d, y.branch_id e from mcf.mcf_accounts y where account_type in ('Savings','Current','Susu','Investment') AND y.status = 'Authorized'
        AND (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)
        order by 2",
        /* 34 */ "select distinct trim(to_char(y.account_id,'999999999999999999999999999999')) a, 
        trim(account_number) b, '' c, 
        y.org_id d, cust_id e from mcf.mcf_accounts y
        WHERE account_type in ('Savings','Current','Susu')
        AND (SELECT COUNT(*) FROM mcf.mcf_account_liens
        WHERE to_char(now(), 'YYYY-MM-DD') between start_date_active AND end_date_active
        AND account_id = y.account_id) <= 0 AND y.status = 'Authorized'
        order by 2",
        /* 35 */ "select distinct trim(to_char(y.account_id,'999999999999999999999999999999')) a, 
        trim(x.account_number)||' {['||x.account_title||'], ['||y.trnsctn_id||']}' b, '' c, 
        x.org_id d from mcf.mcf_accounts x, mcf.mcf_loan_request y
        where x.account_id = y.account_id
        AND is_disbursed = 'YES'
        AND y.status = 'Approved'
        AND crdt_type = 'Loan'
        AND account_type in ('Loan') AND x.status = 'Authorized' AND y.status = 'Approved'
        ORDER BY 2",
        /* 36 */ "select '' || bank_id a, bank_name b, '' c, org_id d from mcf.mcf_all_banks order by b",
        /* 37 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, 
        x.org_id d from mcf.mcf_customers_ind x, mcf.mcf_customers y WHERE x.cust_id = y.cust_id AND y.status = 'Authorized'
        ORDER BY 2",
        /* 38 */ "SELECT distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a, 
        '('||trnsctn_id||' ['||mcf.get_customer_name(cust_type, x.cust_id)||'])' b, '' c, 
        y.org_id d,  y.loan_product_id e, x.cust_id f FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans  y
        WHERE x.loan_product_id = y.loan_product_id
        AND x.status = 'Approved' AND x.is_disbursed = 'YES' AND y.status = 'Authorized'
        ORDER BY 2",
        /* 39 */ "select distinct trim(to_char(y.person_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, 
        y.org_id d,  y.prsn_type e from mcf.mcf_prsn_names_nos y where (1 = 1) AND y.status = 'Authorized'
        AND y.prsn_type ilike '%Director%'
         order by 2",
        /* 40 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
        trim(cust_name)||' ['||x.local_id_no||']' b, '' c,
        x.org_id d,  y.cust_type e, z.branch_id f FROM mcf.mcf_customers_corp x, mcf.mcf_customers y, mcf.mcf_accounts z  where (x.cust_id = y.cust_id) AND y.status = 'Authorized'
         AND z.cust_id = y.cust_id order by 2
        ",
        /* 41 */ "select distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a, 
        '('||trnsctn_id||' ['||mcf.get_customer_name(cust_type, x.cust_id)||' {'||mcf.get_customer_data(cust_type, x.cust_id, 'local_id_no')||'}])' b, '' c, 
        branch_id d,  currency_id e, cust_type f FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans  y
        WHERE x.loan_product_id = y.loan_product_id
        AND x.status = 'Approved' AND UPPER(x.is_disbursed) != 'YES' AND y.status = 'Authorized'
        ORDER BY 2
        ",
        /* 42 */ "select distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a, 
        '('||trnsctn_id||' ['||mcf.get_customer_name(cust_type, x.cust_id)||' {'||mcf.get_customer_data(cust_type, x.cust_id, 'local_id_no')||'}])' b, '' c, 
        y.org_id d,  branch_id e, currency_id f FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans  y
        WHERE x.loan_product_id = y.loan_product_id
        AND x.status = 'Approved'  AND x.cust_type = 'Individual' AND x.is_disbursed != 'YES' AND y.status = 'Authorized'
        UNION  
        select distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a, 
        '('||trnsctn_id||' ['||mcf.get_customer_name(cust_type, x.cust_id)||' {'||mcf.get_customer_data(cust_type, x.cust_id, 'local_id_no')||'}])' b, '' c, 
        y.org_id d,  branch_id e, currency_id f FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans  y
        WHERE x.loan_product_id = y.loan_product_id
        AND x.status = 'Approved' AND x.cust_type = 'Corporate' AND x.is_disbursed != 'YES' AND y.status = 'Authorized'
        ORDER BY 2",
        /* 43 */ "",
        /* 44 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)||' ['||x.local_id_no||','||y.position||']' b, '' c,
        x.org_id d,  y.grp_cust_id e, z.branch_id f  from mcf.mcf_customers_ind x, mcf.mcf_group_members y, mcf.mcf_customers z
        where (x.cust_id = y.cust_id) AND x.cust_id = z.cust_id AND z.status = 'Authorized' order by 2",
        /* 45 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a, 
        trim(cust_name)||' ['||x.local_id_no||']' b, '' c, 
        x.org_id d from mcf.mcf_customers_corp x, mcf.mcf_customers y  where (x.cust_id = y.cust_id)
        AND y.cust_type = 'Group' AND y.status = 'Authorized'
         order by 2",
        /* 46 */ "",
        /* 47 */ "",
        /* 48 */ "SELECT distinct ''||y.disbmnt_det_id a, trnsctn_id||' ('||v.title ||' '|| v.sur_name || ', ' || v.first_name || ' ' || v.other_names||')' b, '' c, x.org_id d, x.disbmnt_hdr_id e
        FROM mcf.mcf_loan_disbursement_hdr x, mcf.mcf_loan_disbursement_det y,
        mcf.mcf_loan_request z, mcf.mcf_customers_ind v
        WHERE x.disbmnt_hdr_id = y.disbmnt_hdr_id
        AND y.loan_rqst_id = z.loan_rqst_id
        AND z.cust_id = v.cust_id
        AND x.status = 'Disbursed'
        AND x.voided_disbmnt_hdr_id = -1
        GROUP BY y.disbmnt_det_id, trnsctn_id||' ('||v.title ||' '|| v.sur_name || ', ' || v.first_name || ' ' || v.other_names||')', x.disbmnt_hdr_id, z.loan_rqst_id
        HAVING (SELECT count(*) FROM mcf.mcf_loan_request w where z.loan_rqst_id = w.loan_rqst_id
        AND z.repayment_type = 'Manual Payments' AND z.has_been_paid = 'No') > 0
        UNION
      SELECT distinct ''||y.disbmnt_det_id a, trnsctn_id||' ('||v.cust_name||')' b, '' c, x.org_id d, x.disbmnt_hdr_id e
        FROM mcf.mcf_loan_disbursement_hdr x, mcf.mcf_loan_disbursement_det y,
        mcf.mcf_loan_request z, mcf.mcf_customers_corp v
        WHERE x.disbmnt_hdr_id = y.disbmnt_hdr_id
        AND y.loan_rqst_id = z.loan_rqst_id
        AND z.cust_id = v.cust_id
        AND x.status = 'Disbursed'
        AND x.voided_disbmnt_hdr_id = -1
        GROUP BY y.disbmnt_det_id, trnsctn_id||' ('||v.cust_name||')', x.disbmnt_hdr_id, z.loan_rqst_id",
        /* 49 */ "",
        /* 50 */ "select distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a, 
        '('||trnsctn_id||' ['||mcf.get_customer_name('Individual', x.cust_id)||' {'||mcf.get_customer_data('Individual', x.cust_id, 'local_id_no')||'}])' b, '' c, 
        branch_id d,  currency_id e, grp_cust_id f FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans  y
        WHERE x.loan_product_id = y.loan_product_id
        AND x.status = 'Approved' AND x.is_disbursed != 'YES' AND y.status = 'Authorized'
        ORDER BY 2",
        /* 51 */ "select distinct trim(to_char(x.loan_product_id,'999999999999999999999999999999')) a, 
        product_name b, '' c, x.org_id d, cust_type_corp e, x.product_type f  from mcf.mcf_prdt_loans x WHERE x.status = 'Authorized'
        AND now() between to_timestamp(start_date_active,'YYYY-MM-DD') AND to_timestamp(end_date_active,'YYYY-MM-DD')
        AND (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)
        ORDER BY 2",
        /* 52 */ "select distinct trim(to_char(x.loan_product_id,'999999999999999999999999999999')) a, 
        product_name b, '' c, x.org_id d, cust_type_custgrp e, x.product_type f  from mcf.mcf_prdt_loans x WHERE x.status = 'Authorized'
        AND now() between to_timestamp(start_date_active,'YYYY-MM-DD') AND to_timestamp(end_date_active,'YYYY-MM-DD')
        AND (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)
        ORDER BY 2",
        /* 53 */ "select distinct trim(to_char(x.loan_product_id,'999999999999999999999999999999')) a, 
        product_name b, '' c, x.org_id d, cust_type_ind e, x.product_type f  from mcf.mcf_prdt_loans x WHERE x.status = 'Authorized'
        AND now() between to_timestamp(start_date_active,'YYYY-MM-DD') AND to_timestamp(end_date_active,'YYYY-MM-DD')
        AND (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)
        ORDER BY 2",
        /* 54 */ "select distinct trim(to_char(account_id,'999999999999999999999999999999')) a, 
        trim(account_number)||' ['||account_title||']' b, '' c, 
        y.org_id d from mcf.mcf_accounts y, mcf.mcf_prdt_savings z
        WHERE y.product_type_id = z.svngs_product_id
        AND account_type in ('Savings','Current','Susu')
        AND z.product_name = 'Loans Payment Account Product'
        AND account_title like 'Branch Loan Payments%' AND y.status = 'Authorized' AND z.status = 'Authorized'
        order by 2",
        /* 55 */ "SELECT distinct ''||x.disbmnt_hdr_id a, batch_no||' ('||description||')' b, '' c, x.org_id d, x.branch_id e
        FROM mcf.mcf_loan_disbursement_hdr x, mcf.mcf_loan_disbursement_det y,
        mcf.mcf_loan_request z
        WHERE x.disbmnt_hdr_id = y.disbmnt_hdr_id
        AND y.loan_rqst_id = z.loan_rqst_id
        AND x.status = 'Disbursed'
        AND x.voided_disbmnt_hdr_id = -1
        GROUP BY x.disbmnt_hdr_id, batch_no||' ('||description||')', x.branch_id, z.loan_rqst_id
        HAVING (SELECT count(*) FROM mcf.mcf_loan_request w where z.loan_rqst_id = w.loan_rqst_id
        AND z.repayment_type = 'Manual Payments' AND z.has_been_paid = 'No') > 0
        ORDER BY 2",
        /* 56 */ "",
        /* 57 */ "",
        /* 58 */ "",
        /* 59 */ "SELECT distinct trim(to_char(x.person_id,'999999999999999999999999999999')) a, trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, 
        org_id d FROM prs.prsn_names_nos x, pasn.prsn_divs_groups y
        where x.person_id = y.person_id
        and y.div_id = (select org.get_div_id('Recovery Office'))
        order by b DESC",
        /* 60 */ "",
        /* 61 */ "",
        /* 62 */ "select distinct trim(to_char(risk_factor_id,'999999999999999999999999999999')) a, 
        trim(risk_factor_code) b, '' c, 
        y.org_id d from mcf.mcf_credit_risk_factors y where upper(is_enabled) = 'YES' order by 2",
        /* 63 */ "select distinct trim(to_char(risk_profile_id,'999999999999999999999999999999')) a, 
        trim(profile_name) b, '' c, 
        y.org_id d from mcf.mcf_credit_risk_profiles y where upper(is_enabled) = 'YES' and validity = 'Valid' order by 2",
        /* 64 */ "select distinct trim(to_char(scoring_set_hdr_id,'999999999999999999999999999999')) a, 
        trim(set_name) b, '' c, 
        y.org_id d from mcf.mcf_credit_scoring_set_hdr y where(is_enabled='Yes' AND upper(status) IN ('VALID','AUTHORIZED','APPROVED')) order by 2",
        /* 65 */ "",
        /* 66 */ "",
        /* 67 */ "select distinct trim(to_char(major_sector_id,'999999999999999999999999999999')) a, sector_name||' ['||sector_desc||']' b, '' c, org_id d
        from mcf.mcf_loan_sectors_major where (is_enabled='Yes') order by 2",
        /* 68 */ "select distinct trim(to_char(minor_sector_id,'999999999999999999999999999999')) a, sector_name||' ['||sector_desc||']' b, '' c, major_sector_id d
        from mcf.mcf_loan_sectors_minor where (is_enabled='Yes') order by 2",
        /* 69 */ "select distinct trim(to_char(y.acct_trns_id,'999999999999999999999999999999')) a, 
        trim(doc_no)||' ['||account_number||']' b, '' c, 
        y.org_id d, z.cust_id e from mcf.mcf_cust_account_transactions y, mcf.mcf_accounts z
        WHERE y.account_id = z.account_id AND account_type = 'Investment' AND upper(y.status) = 'RECEIVED'
        AND trns_type = 'DEPOSIT' AND y.amount > 0 order by 2",
        /* 70 */ "select distinct trim(to_char(svngs_product_id,'999999999999999999999999999999')) a, product_name b, '' c, 
        org_id d,  invstmnt_type e from mcf.mcf_prdt_savings where (1=1) AND product_type = 'Investment'",
        /* 71 */ "select distinct trim(to_char(account_id,'999999999999999999999999999999')) a, 
        trim(account_number)||' ['||account_title||']' b, '' c, 
        y.org_id d, branch_id e from mcf.mcf_accounts y where account_type in ('Current')
        order by 2",
        /* 72 */ "",
        /* 73 */ "",
        /* 74 */ "",
        /* 75 */ "select distinct trim(to_char(account_id,'999999999999999999999999999999')) a,
        trim(account_number)||' ['||account_title||']' b, '' c,
        y.org_id d, y.branch_id e from mcf.mcf_accounts y where account_type in ('Savings','Current','Susu') AND y.status = 'Authorized'
        order by 2",
        /* 76 */ "select distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a,
        '('||w.account_number||' ['||mcf.get_customer_name_loanrqst(x.loan_rqst_id)||', BAL: '||iso_code||
        to_char(v.principal_amount_bal, 'FM999,999,999,990D00')||', AGE: '||
        EXTRACT(DAY FROM (now() - cast(v.repay_end_date AS DATE)))::character varying||' days])  => LOAN' b, '' c,
        y.org_id d, x.branch_id e FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans y, mcf.mcf_currencies z, mcf.mcf_loan_disbursement_det v,
        mcf.mcf_loan_disbursement_hdr u, mcf.mcf_accounts w
        WHERE x.loan_product_id = y.loan_product_id AND y.currency_id = z.crncy_id
        AND v.loan_rqst_id = x.loan_rqst_id AND v.disbmnt_hdr_id = u.disbmnt_hdr_id
	AND w.account_id = x.account_id
        AND u.status != 'Void'
        AND coalesce(v.principal_amount_bal,0) > 0
        AND x.crdt_type = 'Loan'
        AND x.status = 'Approved' AND UPPER(x.is_disbursed) = 'YES' AND v.principal_amount > 0
        --AND EXTRACT(DAY FROM (now() - cast(v.repay_end_date AS DATE)))::numeric > 0
        AND x.loan_rqst_id NOT IN (SELECT DISTINCT loan_rqst_id
                                                    FROM mcf.mcf_loan_writeoffs w
                                                    WHERE w.status = 'Authorized' AND w.writeoff_status = 'Processed')
        UNION
        select distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a,
        '('||w.account_number||' ['||mcf.get_customer_name_loanrqst(x.loan_rqst_id)||', BAL: '||iso_code||
        to_char((v.principal_amount - mcf.get_cstacnt_avlbl_bals(repayment_account_id, to_char(now(),'YYYY-MM-DD'))), 'FM999,999,999,990D00')||', AGE: '||
        EXTRACT(DAY FROM (now() - cast(v.repay_end_date AS DATE)))::character varying||' days]) => OD' b, '' c,
        y.org_id d, x.branch_id e FROM mcf.mcf_loan_request x, mcf.mcf_prdt_loans y, mcf.mcf_currencies z, mcf.mcf_loan_disbursement_det v,
        mcf.mcf_loan_disbursement_hdr u, mcf.mcf_accounts w
        WHERE x.loan_product_id = y.loan_product_id AND y.currency_id = z.crncy_id
        AND v.loan_rqst_id = x.loan_rqst_id AND v.disbmnt_hdr_id = u.disbmnt_hdr_id
        AND w.account_id = x.account_id
        AND u.status != 'Void'
        AND x.crdt_type = 'Overdraft'
        AND x.status = 'Approved' AND UPPER(x.is_disbursed) = 'YES' AND v.principal_amount > 0
        AND now() > to_timestamp(v.repay_end_date,'YYYY-MM-DD')
        AND (v.principal_amount - mcf.get_cstacnt_avlbl_bals(repayment_account_id, to_char(now(),'YYYY-MM-DD'))) > 0
        AND x.loan_rqst_id NOT IN (SELECT DISTINCT loan_rqst_id
                                                    FROM mcf.mcf_loan_writeoffs w
                                                    WHERE w.status = 'Authorized' AND w.writeoff_status = 'Processed')
        ORDER BY 2",
        /* 77 */ "",
        /* 78 */ "",
        /* 79 */ "",
        /* 80 */ "",
        /* 81 */ "select distinct trim(to_char(y.person_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names) b, '' c, 
        y.org_id d from mcf.mcf_prsn_names_nos y where (1 = 1) AND y.status = 'Authorized'
        AND y.prsn_type ilike '%Next of Kin%'
         order by 2",
        /* 82 */ "SELECT distinct trim(to_char(y.disbmnt_det_id,'999999999999999999999999999999')) a, 
        mcf.get_cust_account_number(v.account_id)||' ('||mcf.get_cust_accnt_name(v.account_id)||')' b, '' c, 
        x.org_id d  
        FROM mcf.mcf_loan_request v INNER JOIN mcf.mcf_prdt_loans x ON v.loan_product_id = x.loan_product_id
        INNER JOIN mcf.mcf_loan_disbursement_det y ON y.loan_rqst_id = v.loan_rqst_id
        INNER JOIN mcf.mcf_loan_disbursement_hdr z ON y.disbmnt_hdr_id = z.disbmnt_hdr_id
          AND repayment_type = 'Account Deductions' AND is_disbursed = 'YES'
          AND v.status = 'Approved' AND z.status = 'Disbursed' AND y.principal_amount > 0
          AND v.crdt_type = 'Loan'
                 order by 2",
        /* 83 */ "select distinct trim(to_char(y.person_id,'999999999999999999999999999999')) a, 
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)||' ['||y.prsn_type||', {'||local_id_no||'}]' b, '' c, 
        y.org_id d,  y.prsn_type e from mcf.mcf_prsn_names_nos y where (1 = 1) AND y.status = 'Authorized'
        AND (y.prsn_type ilike '%Director%' or y.prsn_type ilike '%Trust%' or y.prsn_type ilike '%Joint%')
        AND status = 'Authorized'
        order by 2",
        /* 84 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
        trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)||' (ID No:'||x.local_id_no||', Acct No:'||account_number||')' b, '' c,
        x.org_id d,  y.cust_type e, y.branch_id f from mcf.mcf_customers_ind x INNER JOIN mcf.mcf_customers y
        ON (x.cust_id = y.cust_id) LEFT OUTER JOIN mcf.mcf_accounts z ON y.cust_id = z.cust_id WHERE y.status = 'Authorized'
        AND z.account_type NOT IN ('Loan','Investment') AND z.status = 'Authorized'
        UNION
		select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
		trim(cust_name)||' (ID No:'||x.local_id_no||', Acct No:'||account_number||')' b, '' c,
		x.org_id d,  y.cust_type e, y.branch_id from mcf.mcf_customers_corp x INNER JOIN mcf.mcf_customers y  
			ON (x.cust_id = y.cust_id) LEFT OUTER JOIN mcf.mcf_accounts z ON y.cust_id = z.cust_id  WHERE y.status = 'Authorized'
			AND z.account_type NOT IN ('Loan','Investment') AND z.status = 'Authorized'
		 order by 2
		",
        /* 85 */ "select distinct trim(to_char(x.cust_id,'999999999999999999999999999999')) a,
        trim(cust_name)||' (ID No:'||x.local_id_no||', Acct No:'||account_number||')' b, '' c,
        x.org_id d,  y.cust_type e, y.branch_id f FROM mcf.mcf_customers_corp x INNER JOIN mcf.mcf_customers y
        ON (x.cust_id = y.cust_id) LEFT OUTER JOIN mcf.mcf_accounts z ON y.cust_id = z.cust_id  WHERE y.status = 'Authorized'
	AND z.account_type NOT IN ('Loan','Investment') AND z.status = 'Authorized'
         order by 2
        ",
        /* 86 */ "select distinct trim(to_char(y.account_id,'999999999999999999999999999999')) a, 
        trim(account_number) b, '' c, 
        y.org_id d, cust_id e from mcf.mcf_accounts y where (account_type in ('Savings','Current','Susu','Investment') and (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)) AND y.status = 'Authorized' 
        order by 2",
        /* 87 */ "select distinct trim(to_char(x.invstmnt_id,'999999999999999999999999999999')) a, 
        trim(x.trnsctn_no)||' ['||mcf.get_customer_name(y.cust_type, y.cust_id)||' ('||mcf.get_cust_local_idno(y.cust_id)||')]' b, '' c, 
        y.org_id d, y.branch_id e from mcf.mcf_investments x, mcf.mcf_accounts y where x.account_id = y.account_id AND (account_type in ('Investment') and (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)) AND y.status = 'Authorized' 
        AND x.status = 'Authorized' AND x.invstmnt_status = 'Running'
        order by 2",
        /* 88 */ "",
        /* 89 */ "SELECT distinct ''||person_id a, person_id||'~'||trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names || ' (' || local_id_no || ')') b, '' c, org_id d, local_id_no e FROM prs.prsn_names_nos a order by local_id_no DESC",
        /* 90 */ "SELECT distinct trim(to_char(x.loan_rqst_id,'999999999999999999999999999999')) a,
        '('||w.account_number||' ['||mcf.get_customer_name_loanrqst(x.loan_rqst_id)||' {Accrued Interest: '||iso_code||
        to_char(ROUND(SUM(interest_earned - amount_paid),2), 'FM999,999,999,990D00')||'}]' b, '' c,
        y.org_id d, x.branch_id e
        FROM mcf.mcf_daily_overdraft_interest a, mcf.mcf_loan_request x, mcf.mcf_prdt_loans y, mcf.mcf_currencies z, mcf.mcf_loan_disbursement_det v,
                mcf.mcf_loan_disbursement_hdr u, mcf.mcf_accounts w
        WHERE a.disbmnt_det_id = v.disbmnt_det_id AND
                x.loan_product_id = y.loan_product_id AND y.currency_id = z.crncy_id
                AND v.loan_rqst_id = x.loan_rqst_id AND v.disbmnt_hdr_id = u.disbmnt_hdr_id
                    AND w.account_id = x.account_id
                AND u.status != 'Void'
                AND coalesce(v.principal_amount_bal,0) > 0
                AND x.crdt_type = 'Overdraft'
                AND x.status = 'Approved' AND UPPER(x.is_disbursed) = 'YES' AND v.principal_amount > 0        
        AND is_interest_paid IN ('No','Partial')
        group by 1,w.account_number, x.loan_rqst_id, z.iso_code, y.org_id, x.branch_id
        having ROUND(SUM(interest_earned - amount_paid),2) > 0",
        /* 91 */ "SELECT distinct trim(to_char(x.loan_product_id,'999999999999999999999999999999')) a, 
        product_name b, '' c, x.loan_product_id d  FROM mcf.mcf_prdt_loans x  order by 2",
        /* 92 */ "SELECT distinct trim(to_char(svngs_product_id,'999999999999999999999999999999')) a, 
        product_name b, '' c, y.svngs_product_id d FROM mcf.mcf_prdt_savings y 
        WHERE (1=1) AND product_name != 'Loans Payment Account Product' order by 2",
        /* 93 */ "select distinct trim(to_char(x.invstmnt_id,'999999999999999999999999999999')) a, 
        y.account_number||'{'||trim(x.trnsctn_no)||' ['||mcf.get_customer_name(y.cust_type, y.cust_id)||' ('||mcf.get_cust_local_idno(y.cust_id)||')]}' b, '' c, 
        y.org_id d, y.branch_id e from mcf.mcf_investments x, mcf.mcf_accounts y where x.account_id = y.account_id AND account_type in ('Investment') AND y.status = 'Authorized' 
        AND x.status = 'Authorized'
        order by 2",
        /* 94 */ "",
        /* 95 */ "select distinct product_name a, product_name  b, '' c,
        '' d,  product_type e from mcf.mcf_prdt_savings where (1=1) AND status = 'Authorized' AND product_name != 'Loans Payment Account Product' AND status = 'Authorized'
        UNION
        select distinct product_name a, product_name  b, '' c,
        '' d,  product_type e from mcf.mcf_prdt_savings where (1=1) AND status = 'Authorized' AND product_name != 'Loans Payment Account Product' AND status = 'Authorized'
        order by 2",
        /* 96 */ "select distinct trim(to_char(y.account_id,'999999999999999999999999999999')) a, 
        trim(account_number) b, '' c, 
        y.org_id d, cust_id e from mcf.mcf_accounts y where (account_type in ('Savings','Current','Susu') and (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)) 
        AND CASE WHEN UPPER((SELECT var_value FROM mcf.mcf_global_variables WHERE var_name = 'Loan Repayment Product Code')) = 'ANY' THEN substr(account_number,1,3) 
        ELSE (SELECT var_value FROM mcf.mcf_global_variables WHERE var_name = 'Loan Repayment Product Code') END = substr(account_number,1,3) 
        AND y.status = 'Authorized' 
        order by 2"
    );
    $pssblVals = array(
        "3", "Deposits/Inward Transfers", "Deposits/Inward Transfers",
        "3", "Withdrawals/Outward Transfers", "Withdrawals/Outward Transfers",
        "8", "Current", "Current",
        "8", "Current Account", "Current Account",
        "8", "Investment", "Investment",
        "8", "Loan", "Loan",
        "8", "Savings", "Savings",
        "8", "Savings Account", "Savings Account",
        "8", "Susu", "Susu",
        "9", "All to sign", "All to sign",
        "9", "Any four to sign", "Any four to sign",
        "9", "Anyone to sign", "Anyone to sign",
        "9", "Any three to sign", "Any three to sign",
        "9", "Any two to sign", "Any two to sign",
        "9", "Both to sign", "Both to sign",
        "11", "Corporate", "Corporate",
        "11", "Group", "Group",
        "11", "Individual", "Individual",
        "11", "Joint-Individual", "Joint-Individual",
        "14", "INDV-Individual", "INDV-Individual",
        "15", "CGRP-Customer Group", "CGRP-Customer Group",
        "16", "CORP-Foreign Companies and Subsidiaries", "CORP-Foreign Companies and Subsidiaries",
        "16", "CORP-Government Ministries,Departments,Agencies and Authorities", "CORP-Government Ministries,Departments,Agencies and Authorities",
        "16", "CORP-Limited Liability Company", "CORP-Limited Liability Company",
        "16", "CORP-Non-Governmental Organisation", "CORP-Non-Governmental Organisation",
        "16", "CORP-Schools/Institutions", "CORP-Schools/Institutions",
        "16", "CORP-Societies/Clubs/Associations", "CORP-Societies/Clubs/Associations",
        "16", "CORP-Sole Proprietors", "CORP-Sole Proprietors",
        "17", "Residential Address Sketch", "Residential Address Sketch",
        "17", "Signature", "Signature",
        "17", "Thumbprint", "Thumbprint",
        "18", "Larg Size Group - (> 7 persons)", "Larg Size Group - (> 7 persons)",
        "18", "Limited Liability Company", "Limited Liability Company",
        "18", "Medium Size Group - (4 - 6 Persons)", "Medium Size Group - (4 - 6 Persons)",
        "18", "Organisation", "Organisation",
        "18", "Small Size Group - (Up to 3)", "Small Size Group - (Up to 3)",
        "18", "VMS BOG Banks", "VMS BOG Banks",
        "19", "Daily", "Daily",
        "19", "Monthly", "Monthly",
        "20", "End of Day", "End of Day",
        "20", "End of Month", "End of Month",
        "20", "End of Week", "End of Week",
        "20", "End of Year", "End of Year",
        "21", "Average Balance", "Average Balance",
        "21", "Minimum Balance", "Minimum Balance",
        "21", "Daily Balance", "Daily Balance",
        "22", "Compound", "Compound",
        "22", "Simple", "Simple",
        "25", "Investment", "Investment",
        "25", "Loan Servicing", "Loan Servicing",
        "25", "Other", "Other",
        "25", "Personal Savings", "Personal Savings",
        "25", "Salaries", "Salaries",
        "25", "Transactional", "Transactional",
        "28", "Sales Proceeds", "Sales Proceeds",
        "28", "Services Rendered", "Services Rendered",
        "28", "Trust Funds per Trust Deed", "Trust Funds per Trust Deed",
        "28", "Personal Savings", "Personal Savings",
        "28", "Inheritance/Gift", "Inheritance/Gift",
        "28", "Dividends", "Dividends",
        "28", "Commission", "Commission",
        "28", "Other Income", "Other Income",
        "28", "Salary", "Salary",
        "29", "Current", "Current",
        "29", "Investment", "Investment",
        "29", "Savings", "Savings",
        "29", "Susu", "Susu",
        "30", "Public Company Ltd", "Public Company Ltd",
        "30", "Private Company Ltd", "Private Company Ltd",
        "30", "Closed Corporation", "Closed Corporation",
        "30", "Joint Venture", "Joint Venture",
        "30", "Consortium", "Consortium",
        "30", "Partnership", "Partnership",
        "30", "Sole Proprietor", "Sole Proprietor",
        "30", "Foreign Company", "Foreign Company",
        "30", "Government/Parastatals", "Government/Parastatals",
        "30", "Trust", "Trust",
        "31", "Cheque", "Cheque",
        "31", "Paperless", "Paperless",
        "31", "Withdrawal Slip", "Withdrawal Slip",
        "43", "Corporate Customer", "Corporate Customer",
        "43", "Customer Group", "Customer Group",
        "43", "Other Person", "Other Person",
        "43", "Personal Customer", "Personal Customer",
        "46", "External", "External",
        "46", "In-House", "In-House",
        "47", "Deposit Slip", "Deposit Slip",
        "47", "Paperless", "Paperless",
        "49", "Member", "Member",
        "49", "Group President", "Group President",
        "49", "Group Secretary", "Group Secretary",
        "49", "Treasurer", "Treasurer",
        "49", "Vice President", "Vice President",
        "56", "1.Sunday", "1.Sunday",
        "56", "2.Monday", "2.Monday",
        "56", "3.Tuesday", "3.Tuesday",
        "56", "4.Wednesday", "4.Wednesday",
        "56", "5.Thursday", "5.Thursday",
        "56", "6.Friday", "6.Friday",
        "56", "7.Saturday", "7.Saturday",
        "57", "Director", "Director",
        "57", "Guarantor", "Guarantor",
        "57", "Next of Kin", "Next of Kin",
        "58", "Board Member", "Board Member",
        "58", "Customer", "Customer",
        "58", "Shareholder", "Shareholder",
        "58", "Staff", "Staff",
        "60", "Limited Liability Company", "Limited Liability Company",
        "60", "Organisatin", "Organisatin",
        "61", "Larg Size Group - (> 7 persons)", "Larg Size Group - (> 7 persons)",
        "61", "Medium Size Group - (4 - 6 Persons)", "Medium Size Group - (4 - 6 Persons)",
        "61", "Small Size Group - (Up to 3)", "Small Size Group - (Up to 3)",
        "65", "Processing", "Processing",
        "65", "Insurance", "Insurance",
        "65", "Management", "Management",
        "65", "Other", "Other",
        "66", "Principal and Interest Balance", "Principal and Interest Balance",
        "66", "Principal Balance", "Principal Balance",
        "66", "Interest Balance", "Interest Balance",
        "72", "Low Risk", "Low Risk",
        "72", "Medium Risk", "Medium Risk",
        "72", "High Risk", "High Risk",
        "73", "Withdrawal by Self", "Withdrawal by Self",
        "73", "Deposit by Self", "Deposit by Self",
        "73", "Withdrawal by Third Party", "Withdrawal by Third Party",
        "73", "Deposit by Third Party", "Deposit by Third Party",
        "74", "House", "House",
        "74", "Vehicle", "Vehicle",
        "74", "Land", "Land",
        "77", "Draft", "Draft",
        "77", "Final", "Final",
        "78", "End of Day", "End of Day",
        "78", "End of Day PLUS End of Month", "End of Day PLUS End of Month",
        "78", "End of Day PLUS End of Month PLUS End of Year", "End of Day PLUS End of Month PLUS End of Year",
        "79", "Block Account", "Block Account",
        "79", "Block Cheque", "Block Cheque",
        "79", "Check Book Request", "Check Book Request",
        "79", "Standing Order", "Standing Order",
        "79", "Account Transfer", "Account Transfer",
        "79", "Other", "Other",
        "80", "Account Holder", "Account Holder",
        "88", "Investment Maturity", "Investment Maturity",
        "88", "Investment Rediscount", "Investment Rediscount",
        "94", "Deposit", "Deposit",
        "94", "Withdrawal", "Withdrawal",
        "94", "Monthly Balance", "Monthly Balance"
    );
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function loadVMSMdl()
{
    $DefaultPrvldgs = array(
        "View Vault Management",
        /* 1 */ "View Branches & Vaults", "View Transactions", "View Standard Reports",
        /* 4 */ "View VMS Administration", "View SQL", "View Record History",
        /* 7 */ "View Branch Setup", "View Vault Setup", "View Cage Setup", "View Item List Setup",
        /* 11 */ "View Authorization Limits Setup", "View Customers/Suppliers Setup",
        /* 13 */ "View Transfer Transactions", "View Direct Customer Deposits", "View Direct Customer Withdrawals",
        /* 16 */ "View Currency Importation", "View Currency Destruction",
        /* 18 */ "View Transit Transfers", "View Teller Transfers", "View Exam Transfers", "View From Exam",
        /* 22 */ "Add Branch/Agency", "Edit Branch/Agency", "Delete Branch/Agency",
        /* 25 */ "Add Vault", "Edit Vault", "Delete Vault",
        /* 28 */ "Add Cage/Shelve", "Edit Cage/Shelve", "Delete Cage/Shelve",
        /* 31 */ "Add Items", "Edit Items", "Delete Items",
        /* 34 */ "Add Authorization Limit", "Edit Authorization Limit", "Delete Authorization Limit",
        /* 37 */ "Add Transactions", "Edit Transactions", "Delete Transactions", "Authorize Transactions",
        /* 41 */ "Search All Transactions", "See non-related Transactions",
        /* 43 */ "View Currency Sale", "View Currency Purchase", "View Miscellaneous Adjustments",
        /* 46 */ "Can Send to GL", "Can Add Correction Transactions", "Can Void Correction Transactions",
        /* 49 */ "Add Customer/Supplier", "Edit Customer/Supplier", "Delete Customer/Supplier",
        /* 52 */ "View Direct Cage/Shelve Transaction",
        /* 53 */ "View Vault/GL Account Transfers", "See Other Branch Transactions"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "Vault Management";
    $myDesc = "This is where all Vault Operations and Management in a Bank are carried out!";
    $audit_tbl_name = "vms.vms_audit_trail_tbl";

    $smplRoleName = "Vault Management Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);

    $sysLovs = array(
        "VMS Transaction Types", "VMS Transaction Classifications",
        "VMS Vaults", "VMS Vault Cages", "Vault Item List", "Vault Item States",
        "All VMS Vault Cages", "Linked Stock Items for Denominations", "All MCF Document Types"
    );
    $sysLovsDesc = array(
        "VMS Transaction Types", "VMS Transaction Classifications",
        "VMS Vaults", "VMS Vault Cages", "Vault Item List", "Vault Item States",
        "All VMS Vault Cages", "Linked Stock Items for Denominations", "All MCF Document Types"
    );
    $sysLovsDynQrys = array(
        "", "",
        "select distinct ''||subinv_id a, subinv_name b, '' c, org_id d from inv.inv_itm_subinventories where (enabled_flag='1' and (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)) order by 2",
        "select distinct ''||line_id a, shelve_name b, '' c, store_id d, gst.get_pssbl_val(accb.get_accnt_crncy_id(inv_asset_acct_id)) e from inv.inv_shelf where (enabled_flag='1' and (org.does_prsn_hv_crtria_id({:prsn_id}, allwd_group_value::bigint,allwd_group_type)>0)) order by 2",
        "select distinct ''||item_id a, item_code b, '' c, org_id d, gst.get_pssbl_val(value_price_crncy_id) e from inv.inv_itm_list where (enabled_flag='1' and item_type ilike 'VaultItem-%') order by 2",
        "",
        "select distinct ''||w.line_id a,w.shelve_name || ' (' || x.subinv_name || ')' b, '' c, w.store_id d, gst.get_pssbl_val(accb.get_accnt_crncy_id(w.inv_asset_acct_id)) e from inv.inv_shelf w, inv.inv_itm_subinventories x where (w.store_id = x.subinv_id and x.lnkd_site_id>0 and w.store_id>0 and COALESCE(w.shelve_name,'') !='' and w.enabled_flag='1' and (org.does_prsn_hv_crtria_id({:prsn_id}, w.allwd_group_value::bigint,w.allwd_group_type)>0)) order by 2",
        "select distinct ''||item_id a, item_code b, '' c, org_id d, gst.get_pssbl_val(value_price_crncy_id) e from inv.inv_itm_list where (enabled_flag='1' and item_type ilike 'VaultItem-Cash%') order by 2",
        ""
    );

    $pssblVals = array(
        "0", "Transits (Specie Movement)", "Transits (Specie Movement)",
        "0", "Teller/Cashier Transfers", "Teller/Cashier Transfers",
        "0", "To Exam", "To Exam",
        "0", "From Exam", "From Exam",
        "0", "Deposits", "VMS Deposits",
        "0", "Withdrawals", "VMS Withdrawals",
        "0", "Currency Importation", "Currency Importation",
        "0", "Currency Destruction", "Currency Destruction",
        "0", "Currency Sale", "Currency Sale",
        "0", "Currency Purchase", "Currency Purchase",
        "0", "Miscellaneous Adjustments", "Miscellaneous Adjustments",
        "0", "Direct Cage/Shelve Transaction", "Direct Cage/Shelve Transaction",
        "0", "Loan Applications", "Loan Applications",
        "0", "Account Transfers", "Account Transfers",
        "0", "LOAN_REPAY", "LOAN_REPAY",
        "0", "WITHDRAWAL", "WITHDRAWAL",
        "0", "DEPOSIT", "DEPOSIT",
        "0", "Bulk/Batch Transactions", "Bulk/Batch Transactions",
        "0", "Vault/GL Account Transfers", "Vault/GL Account Transfers",
        "1", "Dispatch of Specie Movement", "Dispatch of Specie Movement",
        "1", "Receiving from Specie Movement", "Receiving from Specie Movement",
        "1", "Funds Transfer to Cashier", "Funds Transfer to Cashier",
        "1", "Funds Transfer from Cashier", "Funds Transfer from Cashier",
        "1", "Movement of Cash for Examination", "Movement of Cash for Examination",
        "1", "Return of Cash from Examination", "Return of Cash from Examination",
        "1", "Miscellaneous Transfers", "Miscellaneous Transfers",
        "1", "Deposit by a Bank", "Deposit by a Bank",
        "1", "Withdrawal by a Bank", "Withdrawal by a Bank",
        "1", "Deposit by a Customer", "Deposit by a Customer",
        "1", "Withdrawal by a Customer", "Withdrawal by a Customer",
        "1", "Importation of Local Currency", "Importation of Local Currency",
        "1", "Destruction of Local Currency", "Destruction of Local Currency",
        "1", "Corrections/Adjustments", "Corrections/Adjustments",
        "1", "Initial Balance Feed", "Initial Balance Feed",
        "1", "Foreign Currency Sales", "Foreign Currency Sales",
        "1", "Foreign Currency Purchases", "Foreign Currency Purchases",
        "5", "Mint", "Mint",
        "5", "Stack(Non-Mint)", "Stack(Non-Mint)",
        "5", "Unexamined", "Unexamined",
        "5", "Examined", "Examined",
        "5", "Examined-Fit", "Examined-Fit",
        "5", "Examined-Unfit", "Examined-Unfit",
        "5", "Issuable", "Issuable",
        "5", "Shortage", "Shortage",
        "5", "Surplus", "Surplus",
        "5", "Mixture", "Mixture",
        "8", "Cheque", "Cheque",
        "8", "Paperless", "Paperless",
        "8", "Withdrawal Slip", "Withdrawal Slip",
        "8", "Deposit Slip", "Deposit Slip"
    );
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}

function loadAgntMdl()
{
    $DefaultPrvldgs = array(
        "View Agent Registry",
        /* 1 */ "View Agent Profiles", "View Terminated Affiliations", "View Suspicious Activities",
        "View System Configurations",
        /* 5 */ "View SQL", "View Record History", "View All Agent Information",
        /* 8 */ "View Registered EMIs", "View ID Formats", "Merge Agents", "View Only My Affiliated Agents Information",
        /* 12 */ "Add Agent Profile", "Edit Agent Profile", "Delete Agent Profile",
        /* 15 */ "Add Agent Affiliation", "Edit Agent Affiliation", "Terminate Agent Affiliation",
        /* 18 */ "Report Suspicious Activity", "Withdraw Reported Suspicious Activity"
    );
    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "Agent Registry";
    $myDesc = "This is where all EMI Agent Information in the Country are Managed!";
    $audit_tbl_name = "agnt.agnt_audit_trail_tbl";

    $smplRoleName = "Agent Registry Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);


    $DefaultPrvldgs1 = array(
        "View Agent Registry",
        /* 1 */ "View Agent Profiles", "View Terminated Affiliations", "View Suspicious Activities",
        /* 8 */ "View Registered EMIs", "View Only My Affiliated Agents Information",
        /* 10 */ "Add Agent Profile", "Edit Agent Profile", "Delete Agent Profile",
        /* 13 */ "Add Agent Affiliation", "Edit Agent Affiliation", "Terminate Agent Affiliation",
        /* 16 */ "Report Suspicious Activity", "Withdraw Reported Suspicious Activity"
    );
    $smplRoleName1 = "Agent Registry Data Capturer (EMI)";

    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName1, $DefaultPrvldgs1, $subGrpNames, $mainTableNames, $keyColumnNames);

    /* $sysLovs = array("Questions Bank", "Question Possible Answers");
      $sysLovsDesc = array("Questions Bank", "Question Possible Answers");
      $sysLovsDynQrys = array("select '' || qstn_id a, qstn_desc b, '' c "
      . "from self.self_question_bank order by qstn_id",
      "select '' || psbl_ansr_id a, psbl_ansr_desc b, '' c, qstn_id d, '' || qstn_id e "
      . "from self.self_question_possible_answers where is_enabled='1' order by psbl_ansr_order_no");
      createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys); */
}

function loadAstTrckrMdl()
{
    $DefaultPrvldgs = array(
        "View Asset Tracking",
        /* 1 */ "View Assets Register", "View Receipts", "View Movements/Dispatches", "View Retirements",
        /* 5 */ "View Maintenance Works", "View SQL", "View Record History",
        /* 8 */ "Add Assets Register", "Edit Assets Register", "Delete Assets Register",
        /* 11 */ "Add Receipts", "Edit Receipts", "Delete Receipts",
        /* 14 */ "Add Movements/Dispatches", "Edit Movements/Dispatches", "Delete Movements/Dispatches",
        /* 17 */ "Add Retirements", "Edit Retirements", "Delete Retirements",
        /* 20 */ "Add Maintenance Works", "Edit Maintenance Works", "Delete Maintenance Works"
    );
    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";

    $myName = "Asset Tracking";
    $myDesc = "This is where all Fixed Assets in the Institution are Tracked and Managed!";
    $audit_tbl_name = "atrckr.atrckr_audit_trail_tbl";

    $smplRoleName = "Asset Tracking Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
}

function createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys)
{
    for ($i = 0; $i < count($sysLovs); $i++) {
        $lovID = getLovID($sysLovs[$i]);
        if ($lovID <= 0) {
            if ($sysLovsDynQrys[$i] == "") {
                createLovNm(
                    $sysLovs[$i],
                    $sysLovsDesc[$i],
                    '0 ',
                    "",
                    "SYS",
                    '1'
                );
            } else {
                createLovNm(
                    $sysLovs[$i],
                    $sysLovsDesc[$i],
                    '1',
                    $sysLovsDynQrys[$i],
                    "SYS",
                    '1'
                );
            }
        } else {
            if ($sysLovsDynQrys[$i] != "") {
                updateLovNm($lovID, true, $sysLovsDynQrys[$i], "SYS", true);
            } else {
                updateLovNm($lovID, false, $sysLovsDynQrys[$i], "SYS", true);
            }
        }
    }
}

function get_all_OrgIDs()
{
    $strSql = "SELECT distinct org_id FROM org.org_details";
    $result = executeSQLNoParams($strSql);
    $allwd = ",";
    while ($row = loc_db_fetch_array($result)) {
        $allwd .= $row[0] . ",";
    }

    return $allwd;
}

function createSysLovsPssblVals($pssblVals, $sysLovs)
{
    $allwd = get_all_OrgIDs();
    for ($i = 0; $i < count($pssblVals); $i += 3) {
        //, $pssblVals[ $i + 2]
        if (getPssblValID($pssblVals[$i + 1], getLovID($sysLovs[(int) $pssblVals[$i]])) <= 0) {
            createPssblValsForLov(getLovID($sysLovs[(int) $pssblVals[$i]]), $pssblVals[$i + 1], $pssblVals[$i + 2], '1', $allwd);
        }
    }
}

function createLovNm(
    $lovNm,
    $lovDesc,
    $isDyn,
    $sqlQry,
    $dfndBy,
    $isEnbld
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO gst.gen_stp_lov_names(" .
        "value_list_name, value_list_desc, is_list_dynamic, " .
        "sqlquery_if_dyn, defined_by, created_by, creation_date, last_update_by, " .
        "last_update_date, is_enabled) " .
        "VALUES ('" . loc_db_escape_string($lovNm) . "', '" . loc_db_escape_string($lovDesc) .
        "', '" . $isDyn . "', '" . loc_db_escape_string($sqlQry) . "', '" . loc_db_escape_string($dfndBy) .
        "', " . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', '" . $isEnbld . "')";
    execUpdtInsSQL($sqlStr);
}

function updateLovNm($lovID, $isDyn, $sqlQry, $dfndBy, $isEnbld)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "UPDATE gst.gen_stp_lov_names SET " .
        "is_list_dynamic='" . cnvrtBoolToBitStr($isDyn) . "', " .
        "sqlquery_if_dyn='" . loc_db_escape_string($sqlQry) .
        "', defined_by='" . loc_db_escape_string($dfndBy) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date='" . $dateStr .
        "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "' WHERE value_list_id = " . $lovID;
    execUpdtInsSQL($sqlStr);
}

function createPssblValsForLov($lovID, $pssblVal, $pssblValDesc, $isEnbld, $allwd)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $sqlStr = "INSERT INTO gst.gen_stp_lov_values(" .
        "value_list_id, pssbl_value, pssbl_value_desc, " .
        "created_by, creation_date, last_update_by, last_update_date, is_enabled, allowed_org_ids) " .
        "VALUES (" . $lovID . ", '" . loc_db_escape_string($pssblVal) . "', '" . loc_db_escape_string($pssblValDesc) .
        "', " . $usrID . ", '" . $dateStr . "', " . $usrID .
        ", '" . $dateStr . "', '" . $isEnbld . "', '" . loc_db_escape_string($allwd) . "')";
    $result = execUpdtInsSQL($sqlStr);
}

function loadCcsMdl()
{
    //Catholic Church Sacrament
    $DefaultPrvldgs = array(
        "View Sacrament", 
		/* 1 */ "Add Sacrament",
        /* 2 */ "Edit Sacrament", "Delete Sacrament"
    );

    $subGrpNames = "";
    $mainTableNames = "";
    $keyColumnNames = "";
    $myName = "Catholic Church Sacrament";
    $myDesc = "This module helps you to manage the Catholic Church Sacrament Needs!";
    $audit_tbl_name = "ccs.ccs_audit_trail_tbl";

    $smplRoleName = "Sacrament Administrator";
    checkNAssignReqrmnts($myName, $myDesc, $audit_tbl_name, $smplRoleName, $DefaultPrvldgs, $subGrpNames, $mainTableNames, $keyColumnNames);
    createCcsRqrdLOVs();
}

function createCcsRqrdLOVs()
{
    $sysLovs = array(
        "Baptism ID Numbers", 
        /* 1 */ "Religions"
    );

    $sysLovsDesc = array(
        "Baptism ID Numbers", 
        /* 1 */ "Religions"
    );

    $sysLovsDynQrys = array(
        "select ''||bptsm_id a, bptsm_sys_code||' ('||first_name||' '||last_name||')' b, '' c from ccs.baptism where 1 = 1 order by bptsm_sys_code",
        /* 1 */ "",
    );

    $pssblVals = array(
        "1", "Anglican", "Anglican",
        "1", "Catholic", "Catholic",
        "1", "EP Church", "EP Church",
        "1", "I.C.G.C", "I.C.G.C",
        "1", "Presby", "Presby",
        "1", "Methodist", "Methodist"
    );
    createSysLovs($sysLovs, $sysLovsDesc, $sysLovsDynQrys);
    createSysLovsPssblVals($pssblVals, $sysLovs);
}
