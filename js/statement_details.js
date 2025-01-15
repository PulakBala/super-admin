document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("salesModal");
    var span = document.getElementsByClassName("close")[0];

    var categoryElements = document.querySelectorAll('.category');

    categoryElements.forEach(function(element) {
        element.addEventListener('click', function() {
            var category = element.getAttribute('data-category');
            var month = element.getAttribute('data-month');
            var year = element.getAttribute('data-year');
            var type = element.getAttribute('data-type');
            
            var formattedMonth = month.toString().padStart(2, '0');
            
            fetch(`getCategoryData.php?category=${encodeURIComponent(category)}&month=${year}-${formattedMonth}&type=${encodeURIComponent(type)}`)
                .then(response => response.json())
                .then(response => {
                    var tbody = document.getElementById('salesDetailsTable').getElementsByTagName('tbody')[0];
                    tbody.innerHTML = ''; 
                    
                    if(response.data) {
                        response.data.forEach(function(detail) {
                            var row = tbody.insertRow();
                            
                            if(response.type === 'revenue') {
                                row.insertCell(0).innerHTML = detail.created_at;
                                row.insertCell(1).innerHTML = detail.amount;
                                row.insertCell(2).innerHTML = detail.payment_type || '';
                                row.insertCell(3).innerHTML = detail.category;
                                row.insertCell(4).innerHTML = detail.note || '';
                            } else {
                                row.insertCell(0).innerHTML = detail.date;
                                row.insertCell(1).innerHTML = detail.amount;
                                row.insertCell(2).innerHTML = '';
                                row.insertCell(3).innerHTML = detail.category;
                                row.insertCell(4).innerHTML = detail.note || '';
                            }
                        });
                    }
                    modal.style.display = "block";
                })
                .catch(error => console.error('Error:', error));
        });
    });

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});