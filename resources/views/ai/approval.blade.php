<!DOCTYPE html>
<html>
<head>
    <title>DAODES AI Approval</title>
</head>
<body>

<h1>AI Patch Approval</h1>

<div id="list"></div>

<script>
async function load() {
    let res = await fetch('/api/ai/patches');
    let data = await res.json();

    let html = '';

    data.forEach(p => {
        html += `
            <div style="border:1px solid #ccc; margin:10px; padding:10px;">
                <pre>${JSON.stringify(p.patch, null, 2)}</pre>

                <button onclick="approve('${p.id}')">APPROVE</button>
                <button onclick="reject('${p.id}')">REJECT</button>
            </div>
        `;
    });

    document.getElementById('list').innerHTML = html;
}

async function approve(id) {
    await fetch('/api/ai/patch/approve', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id})
    });

    load();
}

async function reject(id) {
    await fetch('/api/ai/patch/reject', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id})
    });

    load();
}

load();
</script>

</body>
</html>