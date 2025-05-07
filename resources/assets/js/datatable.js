window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables with pagination options
    // Docs: https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple, {
            perPage: 5, // Default entries per page
            perPageSelect: [5, 10, 15, 20,25,30], // Dropdown options
            searchable: true, // Enable search box
            sortable: true // Enable column sorting
        });
    }
});
