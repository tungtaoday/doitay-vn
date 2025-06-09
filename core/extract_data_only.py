#!/usr/bin/env python3
"""
Extract INSERT data from binary log recovery file
"""
import re

def extract_inserts(filename):
    with open(filename, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    # Find all INSERT statements
    inserts = []
    
    # Look for patterns that indicate data inserts for our specific tables
    tables = ['categories', 'users', 'companies', 'ratings']
    
    lines = content.split('\n')
    in_insert = False
    current_insert = []
    
    for line in lines:
        # Skip binary log specific lines
        if any(skip in line for skip in ['BINLOG', 'Table_map', '#250606', 'server id', 'CRC32']):
            continue
            
        # Look for SQL statements
        if line.strip().startswith('INSERT INTO') or line.strip().startswith('UPDATE') or line.strip().startswith('DELETE'):
            if any(table in line for table in tables):
                print(f"Found statement: {line[:100]}...")
                
        # Look for commit statements
        if 'COMMIT' in line and current_insert:
            current_insert = []
    
    return inserts

if __name__ == "__main__":
    print("Extracting data from recovered_data.sql...")
    inserts = extract_inserts('recovered_data.sql')
    print(f"Extraction complete!") 