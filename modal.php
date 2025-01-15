<div id="salesModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Sales Revenue Details</h2>
        <table id="salesDetailsTable">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Category</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sales details will be populated here -->
            </tbody>
        </table>
    </div>
</div>

<style>
    .modal {
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
        padding-top: 60px;
    }
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    /* New styles for the table */
    #salesDetailsTable {
        width: 100%;
        border-collapse: collapse;
    }
    #salesDetailsTable th, #salesDetailsTable td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
       
    }
    #salesDetailsTable th {
        background-color: #f2f2f2;
        color: black;
        width: 10%;
    }
    #salesDetailsTable tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #salesDetailsTable tr:hover {
        background-color: #ddd;
    }
</style>