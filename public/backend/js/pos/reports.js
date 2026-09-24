/**
 * Bogra Bazar POS - Reporting & Audit Logs Module
 */

function openReportsModal() {
    loadReportsData('today');
    new bootstrap.Modal(document.getElementById('reportsModal')).show();
}

function loadReportsData(range, btnObj = null) {
    if (btnObj) {
        $(btnObj).parent().find('button').removeClass('active');
        $(btnObj).addClass('active');
    }

    $.ajax({
        url: window.POS_CONFIG.reportsUrl,
        method: "GET",
        data: { range: range },
        success: function(response) {
            if (response.success) {
                // Summary
                $('#report-total-sales').text('৳' + (parseFloat(response.summary.total_sales) || 0).toFixed(2));
                $('#report-total-discount').text('৳' + (parseFloat(response.summary.total_discount) || 0).toFixed(2));
                $('#report-total-orders').text((response.summary.total_orders || 0) + ' টি');

                // Top Selling Products
                let productsTbody = $('#report-top-products-tbody');
                productsTbody.empty();
                if (response.top_products.length === 0) {
                    productsTbody.html('<tr><td colspan="3" class="text-center text-secondary">কোনো বিক্রয় নেই।</td></tr>');
                } else {
                    response.top_products.forEach((product, idx) => {
                        productsTbody.append(`
                            <tr>
                                <td>${idx+1}. ${product.name}</td>
                                <td class="text-center">${parseInt(product.qty_sold)}</td>
                                <td class="text-end">৳${parseFloat(product.total_sales).toFixed(2)}</td>
                            </tr>
                        `);
                    });
                }

                // Cashiers performance
                let cashiersTbody = $('#report-cashiers-tbody');
                cashiersTbody.empty();
                if (!response.cashier_performance || response.cashier_performance.length === 0) {
                    cashiersTbody.html('<tr><td colspan="3" class="text-center text-secondary">কোনো ক্যাশিয়ার পারফরম্যান্স নেই।</td></tr>');
                } else {
                    response.cashier_performance.forEach((cashier, idx) => {
                        cashiersTbody.append(`
                            <tr>
                                <td>${idx+1}. ${cashier.cashier_name}</td>
                                <td class="text-center">${cashier.total_orders}</td>
                                <td class="text-end">৳${parseFloat(cashier.total_sales).toFixed(2)}</td>
                            </tr>
                        `);
                    });
                }

                // Audit Logs
                let auditTbody = $('#report-audit-logs-tbody');
                auditTbody.empty();
                let logs = response.audit_logs.data || response.audit_logs;
                if (!logs || logs.length === 0) {
                    auditTbody.html('<tr><td colspan="4" class="text-center text-secondary">কোনো অডিট লগ নেই।</td></tr>');
                } else {
                    logs.forEach((log) => {
                        let dateStr = new Date(log.created_at).toLocaleString('bn-BD');
                        let userName = log.user ? log.user.name : 'Unknown';
                        auditTbody.append(`
                            <tr>
                                <td>${dateStr}</td>
                                <td>${userName}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary text-uppercase" style="font-size: 10px;">${log.action}</span></td>
                                <td>${log.details || ''}</td>
                            </tr>
                        `);
                    });
                }
            }
        }
    });
}
