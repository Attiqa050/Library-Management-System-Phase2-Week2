const bookSelect = document.getElementById("book");
const memberSelect = document.getElementById("member");
const issueDate = document.getElementById("issueDate");
const dueDate = document.getElementById("dueDate");
const issueForm = document.getElementById("issueForm");

let books = [];
let members = [];


// Load Books and Members
async function loadData() {

    try {
        books = await getBooks();
        members = await getMembers();

        bookSelect.innerHTML =
            `<option value="">-- Select Book --</option>`;

        books.forEach(book => {
            bookSelect.innerHTML += `
                <option value="${book.id}">
                    ${book.title} (${book.availableCopies} available)
                </option>`;
        });

        memberSelect.innerHTML =
            `<option value="">-- Select Member --</option>`;

        members.forEach(member => {
            memberSelect.innerHTML += `
                <option value="${member.id}">
                    ${member.name}
                </option>`;
        });

    } catch {
        alert("Cannot reach the server");
    }
}


// Due Date = Issue Date + 14 Days
issueDate.addEventListener("change", function () {

    const date = new Date(issueDate.value);

    date.setUTCDate(date.getUTCDate() + 14);

    dueDate.value = date.toISOString().split("T")[0];
});


// Issue Book
issueForm.addEventListener("submit", async function (event) {

    event.preventDefault();

    const bookId = bookSelect.value;
    const memberId = memberSelect.value;

    if (!bookId || !memberId || !issueDate.value) {
        alert("Please fill all fields!");
        return;
    }

    try {

        await addIssue({
            bookId: bookId,
            memberId: memberId,
            issueDate: issueDate.value,
            dueDate: dueDate.value
        });

        alert("Book issued successfully!");

        issueForm.reset();
        dueDate.value = "";

        await loadData();

    } catch {
        alert("Cannot issue book!");
    }
});

loadData();