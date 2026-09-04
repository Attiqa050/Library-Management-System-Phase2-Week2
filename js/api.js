const BASE_URL = "http://localhost/LMS_Phase2_week02/api";


// ================= Books =================

async function getBooks() {
    const response = await fetch(`${BASE_URL}/books.php`);
    return await response.json();
}

async function addBook(book) {
    const response = await fetch(`${BASE_URL}/books.php`, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(book)
    });
    return await response.json();
}

async function updateBook(id, book) {
    const response = await fetch(`${BASE_URL}/books.php?id=${id}`, {
        method: "PATCH",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(book)
    });
    return await response.json();
}

async function deleteBook(id) {
    return await fetch(`${BASE_URL}/books.php?id=${id}`, {
        method: "DELETE"
    });
}


// ================= Members =================

async function getMembers() {
    const response = await fetch(`${BASE_URL}/members.php`);
    return await response.json();
}

async function addMember(member) {
    const response = await fetch(`${BASE_URL}/members.php`, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(member)
    });
    return await response.json();
}

async function updateMember(id, member) {
    const response = await fetch(`${BASE_URL}/members.php?id=${id}`, {
        method: "PATCH",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(member)
    });
    return await response.json();
}

async function deleteMember(id) {
    return await fetch(`${BASE_URL}/members.php?id=${id}`, {
        method: "DELETE"
    });
}


// ================= Issues =================

async function getIssues() {
    const response = await fetch(`${BASE_URL}/issues.php`);
    return await response.json();
}

async function addIssue(issue) {
    const response = await fetch(`${BASE_URL}/issues.php`, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(issue)
    });
    return await response.json();
}

async function updateIssue(id, issue) {
    const response = await fetch(`${BASE_URL}/issues.php?id=${id}`, {
        method: "PATCH",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(issue)
    });
    return await response.json();
}

async function deleteIssue(id) {
    return await fetch(`${BASE_URL}/issues.php?id=${id}`, {
        method: "DELETE"
    });
}