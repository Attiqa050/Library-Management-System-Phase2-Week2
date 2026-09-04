// ===============================
// Issued Books Page
// ===============================

const issuedTableBody = document.getElementById("issuedTableBody");

let issues = [];
let books = [];
let members = [];


// Calculate Fine = Rs. 5 per late day
function calculateFine(dueDateStr, returnDateStr) {

    const dueDate = new Date(dueDateStr);
    const returnDate = new Date(returnDateStr);

    const lateDays = Math.floor(
        (returnDate - dueDate) / (1000 * 60 * 60 * 24)
    );

    return lateDays > 0 ? lateDays * 5 : 0;
}


// Load Data
async function loadIssuedBooks() {

    try {

        issues = await getIssues();
        books = await getBooks();
        members = await getMembers();

        displayIssuedBooks();

    } catch (error) {

        issuedTableBody.innerHTML =
            `<tr><td colspan="7">Cannot reach the server</td></tr>`;

    }
}


// Display Issued Books
function displayIssuedBooks() {

    issuedTableBody.innerHTML = "";

    const activeIssues = issues.filter(function (issue) {
        return issue.returnDate === null || issue.returnDate === "";
    });

    if (activeIssues.length === 0) {

        issuedTableBody.innerHTML =
            `<tr><td colspan="7">No Issued Books Found</td></tr>`;

        return;
    }

    const today = new Date().toISOString().split("T")[0];

    activeIssues.forEach(function (issue) {

        const book = books.find(function (book) {
            return String(book.id) === String(issue.bookId);
        });

        const member = members.find(function (member) {
            return String(member.id) === String(issue.memberId);
        });

        const fine = calculateFine(issue.dueDate, today);

        issuedTableBody.innerHTML += `
            <tr>
                <td>${issue.id}</td>
                <td>${book ? book.title : "Unknown"}</td>
                <td>${member ? member.name : "Unknown"}</td>
                <td>${issue.issueDate}</td>
                <td>${issue.dueDate}</td>
                <td>Rs. ${fine}</td>
                <td>
                    <button class="btn return-btn"
                        onclick="returnBook(${issue.id}, this)">
                        Return
                    </button>
                </td>
            </tr>
        `;
    });
}


// Return Book
async function returnBook(issueId, button) {

    const issue = issues.find(function (item) {
        return String(item.id) === String(issueId);
    });

    const book = books.find(function (book) {
        return String(book.id) === String(issue.bookId);
    });

    const today = new Date().toISOString().split("T")[0];
    const fine = calculateFine(issue.dueDate, today);

    button.disabled = true;

    try {

        await updateIssue(issue.id, {
            returnDate: today,
            status: "Returned"
        });

        await updateBook(book.id, {
            availableCopies: Number(book.availableCopies) + 1
        });

        alert("Book Returned Successfully!\nFine = Rs. " + fine);

        await loadIssuedBooks();

    } catch (error) {

        alert("Cannot reach the server");

    }

    button.disabled = false;
}


// Load Page
loadIssuedBooks();