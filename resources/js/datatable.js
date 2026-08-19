window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables with pagination options
    // Docs: https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple, {
            perPage: 20, // Default entries per page
            perPageSelect: [20, 30, 50, 100], // Dropdown options
            searchable: true, // Enable search box
            sortable: true // Enable column sorting
        });
    }
});
