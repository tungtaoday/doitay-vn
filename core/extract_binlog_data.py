#!/usr/bin/env python3
"""
Binary Log Data Extractor
Intelligently parse binary log and create SQL import statements
"""
import re
import json

def parse_binlog_insert(file_path):
    """Parse INSERT statements from decoded binary log"""
    
    users_data = []
    companies_data = []
    ratings_data = []
    leads_data = []
    user_logins_data = []
    
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    # Find all INSERT INTO blocks
    insert_blocks = re.findall(r'### INSERT INTO `t_review_db`\.`(\w+)`\n### SET\n(.*?)(?=###|\n#|$)', content, re.DOTALL)
    
    print(f"Found {len(insert_blocks)} insert operations")
    
    for table, data_block in insert_blocks:
        # Parse @field=value format
        fields = {}
        for line in data_block.split('\n'):
            line = line.strip()
            if line.startswith('###   @'):
                match = re.match(r'###\s+@(\d+)=(.+)', line)
                if match:
                    field_num, value = match.groups()
                    # Clean up value
                    if value == 'NULL':
                        value = None
                    elif value.startswith("'") and value.endswith("'"):
                        value = value[1:-1]  # Remove quotes
                    fields[int(field_num)] = value
        
        # Store by table
        if table == 'users':
            users_data.append(fields)
        elif table == 'companies':
            companies_data.append(fields)
        elif table == 'ratings':
            ratings_data.append(fields)
        elif table == 'leads':
            leads_data.append(fields)
        elif table == 'user_logins':
            user_logins_data.append(fields)
    
    return {
        'users': users_data,
        'companies': companies_data, 
        'ratings': ratings_data,
        'leads': leads_data,
        'user_logins': user_logins_data
    }

def create_user_sql(users_data):
    """Create SQL for users table with current structure"""
    sql_statements = []
    
    for user in users_data:
        try:
            # Map old structure to new structure
            # Old: @1=id, @2=firstname, @3=lastname, @5=email, @12=password, @31=created_at, @32=updated_at
            user_id = user.get(1, 'NULL')
            firstname = user.get(2, '')
            lastname = user.get(3, '')
            name = f"{firstname} {lastname}".strip()
            email = user.get(5, '')
            password = user.get(12, '')
            created_at = user.get(31, 'NULL')
            updated_at = user.get(32, 'NULL')
            
            if email and user_id != 'NULL':
                sql = f"""INSERT IGNORE INTO users (id, name, email, password, created_at, updated_at, loyalty_points, total_loyalty_earned, total_loyalty_redeemed, referral_count, total_referral_earnings) 
VALUES ({user_id}, '{name.replace("'", "''")}', '{email}', '{password}', FROM_UNIXTIME({created_at}), FROM_UNIXTIME({updated_at}), 0, 0, 0, 0, 0.00);"""
                sql_statements.append(sql)
        except Exception as e:
            print(f"Error processing user: {e}")
            continue
    
    return sql_statements

def create_company_sql(companies_data):
    """Create SQL for companies table"""
    sql_statements = []
    
    for company in companies_data:
        try:
            # Map company structure @1=id, @2=user_id, @3=category_id, @4=name, @7=email, @19=phone, etc.
            company_id = company.get(1, 'NULL')
            user_id = company.get(2, 'NULL')
            category_id = company.get(3, 'NULL')
            name = company.get(4, '').replace("'", "''")
            email = company.get(7, '')
            phone = company.get(19, '')
            address = company.get(8, '').replace("'", "''")
            city = company.get(9, '').replace("'", "''")
            description = company.get(12, '').replace("'", "''")
            rating = company.get(15, '0')
            created_at = company.get(17, 'NULL')
            updated_at = company.get(18, 'NULL')
            
            if name and company_id != 'NULL':
                sql = f"""INSERT IGNORE INTO companies (id, user_id, category_id, name, email, phone, address, city, description, rating, created_at, updated_at) 
VALUES ({company_id}, {user_id}, {category_id}, '{name}', '{email}', '{phone}', '{address}', '{city}', '{description}', {rating}, FROM_UNIXTIME({created_at}), FROM_UNIXTIME({updated_at}));"""
                sql_statements.append(sql)
        except Exception as e:
            print(f"Error processing company: {e}")
            continue
    
    return sql_statements

if __name__ == "__main__":
    print("🔄 Parsing binary log data...")
    data = parse_binlog_insert('complete_binlog_data.sql')
    
    print(f"📊 Found:")
    print(f"   Users: {len(data['users'])}")
    print(f"   Companies: {len(data['companies'])}")
    print(f"   Ratings: {len(data['ratings'])}")
    print(f"   Leads: {len(data['leads'])}")
    print(f"   User Logins: {len(data['user_logins'])}")
    
    # Generate SQL files
    print("\n🔧 Generating SQL import files...")
    
    with open('import_users.sql', 'w', encoding='utf-8') as f:
        f.write("-- Import Users Data from Binary Log\n")
        for sql in create_user_sql(data['users']):
            f.write(sql + '\n')
    
    with open('import_companies.sql', 'w', encoding='utf-8') as f:
        f.write("-- Import Companies Data from Binary Log\n")
        for sql in create_company_sql(data['companies']):
            f.write(sql + '\n')
    
    print("✅ SQL import files created:")
    print("   - import_users.sql")
    print("   - import_companies.sql")
    print("\n🎯 Ready to import data into database!") 