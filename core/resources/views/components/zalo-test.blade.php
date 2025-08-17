<!-- Zalo Test Component - Debug Version -->
<div style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background: red;
    color: white;
    padding: 20px;
    border-radius: 10px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    max-width: 300px;
">
    <h3 style="margin: 0 0 10px 0;">🔍 ZALO DEBUG</h3>
    
    <div style="margin-bottom: 10px;">
        <strong>zalo_phone:</strong> 
        <span style="color: yellow;">{{ gs('zalo_phone') ?? 'NULL' }}</span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong>zalo_name:</strong> 
        <span style="color: yellow;">{{ gs('zalo_name') ?? 'NULL' }}</span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong>gs() function:</strong> 
        <span style="color: yellow;">{{ function_exists('gs') ? 'EXISTS' : 'NOT EXISTS' }}</span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong>Current time:</strong> 
        <span style="color: yellow;">{{ now() }}</span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong>Component loaded:</strong> 
        <span style="color: green;">✅ YES</span>
    </div>
    
    <button onclick="this.parentElement.remove()" style="
        background: white;
        color: red;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    ">✕ Close</button>
</div>

<script>
console.log('🔍 Zalo Test Component loaded!');
console.log('zalo_phone:', '{{ gs('zalo_phone') ?? 'NULL' }}');
console.log('zalo_name:', '{{ gs('zalo_name') ?? 'NULL' }}');
console.log('gs function exists:', {{ function_exists('gs') ? 'true' : 'false' }});
</script> 