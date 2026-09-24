/**
 * Bogra Bazar POS - Main Module & Event Handler
 */

// Setup CSRF token for jQuery AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Setup Inactivity Timer for Auto-Logout (15 Minutes)
let inactivityTimer;
const INACTIVITY_LIMIT = 15 * 60 * 1000; // 15 mins

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(triggerAutoLogout, INACTIVITY_LIMIT);
}

function triggerAutoLogout() {
    Swal.fire({
        title: 'সেশন নিষ্ক্রিয়!',
        text: 'নিষ্ক্রিয়তার কারণে আপনি স্বয়ংক্রিয়ভাবে লগআউট হয়ে যাচ্ছেন...',
        icon: 'warning',
        timer: 3000,
        showConfirmButton: false,
        allowOutsideClick: false,
        willClose: () => {
            // Submit CSRF-protected logout form
            let form = $('<form>', {
                action: window.POS_CONFIG.logoutUrl,
                method: 'POST'
            }).append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: window.POS_CONFIG.csrfToken
            }));
            $('body').append(form);
            form.submit();
        }
    });
}

// Inactivity listeners
window.onload = resetInactivityTimer;
window.onmousemove = resetInactivityTimer;
window.onmousedown = resetInactivityTimer;
window.onclick = resetInactivityTimer;
window.onscroll = resetInactivityTimer;
window.onkeypress = resetInactivityTimer;

// Real-time Stock Sync
function syncStock() {
    let syncBtn = $('#sync-stock-btn');
    let originalIcon = syncBtn.html();
    syncBtn.html('<i class="fa-solid fa-arrows-rotate fa-spin me-1"></i> সিঙ্ক হচ্ছে...');
    syncBtn.prop('disabled', true);

    $.ajax({
        url: window.POS_CONFIG.syncStockUrl,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                response.products.forEach(p => {
                    let localProd = products.find(lp => lp.id === p.id);
                    if (localProd) {
                        localProd.stock = p.stock;
                    }
                });
                renderProducts();
                toastr.success('স্টক রিয়েল-টাইমে সিঙ্ক করা হয়েছে!');
            }
        },
        error: function() {
            toastr.error('স্টক সিঙ্ক করতে ব্যর্থ হয়েছে।');
        },
        complete: function() {
            syncBtn.html(originalIcon);
            syncBtn.prop('disabled', false);
        }
    });
}

// Background auto-sync every 60 seconds
setInterval(syncStockBackground, 60000);
function syncStockBackground() {
    $.ajax({
        url: window.POS_CONFIG.syncStockUrl,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                response.products.forEach(p => {
                    let localProd = products.find(lp => lp.id === p.id);
                    if (localProd) {
                        localProd.stock = p.stock;
                    }
                });
                renderProducts();
            }
        }
    });
}

// Document Ready
$(document).ready(function() {
    // Restore cart from localStorage if exists
    restoreCart();

    // Fetch products initially
    fetchProducts();

    // Autofocus product search
    autofocusSearch();

    // Set up search bindings
    bindSearchEvents();

    // Setup keyboard shortcuts
    setupKeyboardShortcuts();

    // Initial layout configurations
    checkMode();
});

// Autofocus Search Input
function autofocusSearch() {
    setTimeout(() => {
        $('#product-search').focus();
    }, 300);
}

// Dark/Light Mode
function checkMode() {
    const isDark = localStorage.getItem('pos-theme') === 'dark';
    if (isDark) {
        $('body').addClass('dark-mode');
        $('#theme-icon').removeClass('bi-moon-stars').addClass('bi-sun-fill');
    } else {
        $('body').removeClass('dark-mode');
        $('#theme-icon').removeClass('bi-sun-fill').addClass('bi-moon-stars');
    }
}

function toggleDarkMode() {
    if ($('body').hasClass('dark-mode')) {
        $('body').removeClass('dark-mode');
        localStorage.setItem('pos-theme', 'light');
        $('#theme-icon').removeClass('bi-sun-fill').addClass('bi-moon-stars');
    } else {
        $('body').addClass('dark-mode');
        localStorage.setItem('pos-theme', 'dark');
        $('#theme-icon').removeClass('bi-moon-stars').addClass('bi-sun-fill');
    }
}

// Fetch Products from Server
function fetchProducts(query = '') {
    $.ajax({
        url: window.POS_CONFIG.searchProductsUrl,
        method: "GET",
        data: {
            q: query,
            category_id: currentCategoryId
        },
        success: function(response) {
            if (response.success) {
                products = response.products;
                renderProducts();
            }
        },
        error: function() {
            toastr.error('প্রোডাক্ট লোড করতে ব্যর্থ হয়েছে।');
        }
    });
}

// Render Product Cards (Infinite Scroll/Lazy Loading)
let currentPage = 1;
const itemsPerPage = 20;

function renderProducts(append = false) {
    let container = $('#products-list-container');
    if (!append) {
        container.empty();
        currentPage = 1;
    }

    if (products.length === 0) {
        container.html('<div class="col-12 text-center py-5 text-secondary">কোনো প্রোডাক্ট পাওয়া যায়নি।</div>');
        return;
    }

    let start = (currentPage - 1) * itemsPerPage;
    let end = start + itemsPerPage;
    let pageItems = products.slice(start, end);

    if (pageItems.length === 0 && !append) {
        container.html('<div class="col-12 text-center py-5 text-secondary">কোনো প্রোডাক্ট পাওয়া যায়নি।</div>');
        return;
    }

    pageItems.forEach(function(product) {
        let isOutOfStock = product.stock <= 0;
        let stockBadge = isOutOfStock
            ? '<span class="badge bg-danger">স্টক আউট</span>'
            : `<span class="badge bg-success">স্টক: ${product.stock}</span>`;

        let cardHtml = `
            <div class="col">
                <div class="product-card ${isOutOfStock ? 'opacity-75' : ''}" onclick="onProductCardClick(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                    <div>
                        <img src="${product.image}" class="product-img" alt="${product.name}" onerror="this.src='/uploads/image/product/default-product.png'">
                        <div class="product-name" title="${product.name}">${product.name}</div>
                        <div class="product-stock mb-1">${stockBadge}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="product-price">৳${product.final_price.toFixed(2)}</span>
                        <button class="btn btn-success btn-sm rounded-circle p-1" style="width: 28px; height: 28px;" ${isOutOfStock ? 'disabled' : ''}>
                            <i class="bi bi-plus-lg" style="font-size: 12px; display: block;"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.append(cardHtml);
    });

    bindGridScroll();
}

function bindGridScroll() {
    let gridContainer = $('.product-grid');
    gridContainer.off('scroll').on('scroll', function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 50) {
            if (currentPage * itemsPerPage < products.length) {
                currentPage++;
                renderProducts(true);
            }
        }
    });
}

// Handle Product Click
function onProductCardClick(product) {
    if (product.stock <= 0) {
        // Out of stock needs admin override
        requestAdminOverride("আউট অফ স্টক প্রোডাক্ট বিক্রি করার অনুমতি", product.id)
        .then(() => {
            // Admin approved! Add to cart with flag stock_override
            addToCart(product, 1, true);
            toastr.success('স্টক ওভাররাইড অনুমোদন সফল হয়েছে। প্রোডাক্টটি কার্টে যোগ করা হলো।');
        })
        .catch(() => {
            toastr.warning('স্টক আউট প্রোডাক্ট বিক্রির অনুমতি বাতিল করা হয়েছে।');
        });
    } else {
        addToCart(product, 1);
    }
}

// Product Search & Barcode scanning handler
function bindSearchEvents() {
    let searchInput = $('#product-search');

    searchInput.on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            let query = $(this).val().trim();
            if (query.length > 0) {
                // Check if query is an exact barcode of a product loaded
                let match = products.find(p => p.barcode === query);
                if (match) {
                    onProductCardClick(match);
                    $(this).val(''); // Clear immediately for next scan
                } else {
                    // If not loaded, run search on backend
                    $.ajax({
                        url: window.POS_CONFIG.searchProductsUrl,
                        method: "GET",
                        data: { q: query },
                        success: function(response) {
                            if (response.success && response.products.length > 0) {
                                let exactMatch = response.products.find(p => p.barcode === query);
                                if (exactMatch) {
                                    onProductCardClick(exactMatch);
                                    searchInput.val('');
                                } else {
                                    products = response.products;
                                    renderProducts();
                                }
                            } else {
                                toastr.warning('কোনো প্রোডাক্ট পাওয়া যায়নি।');
                            }
                        }
                    });
                }
            }
        }
    });

    // Live filter on keystroke (debounced)
    let timeout = null;
    searchInput.on('input', function() {
        clearTimeout(timeout);
        let query = $(this).val().trim();
        timeout = setTimeout(() => {
            if (query.length > 2 || query.length === 0) {
                fetchProducts(query);
            }
        }, 300);
    });
}

function clearProductSearch() {
    $('#product-search').val('');
    fetchProducts();
    autofocusSearch();
}

function filterByCategory(catId, btn) {
    currentCategoryId = catId;
    activeCategory = catId;
    $('.category-btn').removeClass('active');
    $(btn).addClass('active');
    fetchProducts($('#product-search').val().trim());
}

function switchMobileTab(tab) {
    $('.pos-mobile-tabs button').removeClass('active btn-success text-white').addClass('btn-light text-success');
    if (tab === 'products') {
        $('#mobile-tab-products').addClass('active btn-success text-white').removeClass('btn-light text-success');
        $('.products-panel').css('display', 'flex').removeClass('d-none');
        $('.cart-panel').css('display', 'none').addClass('d-none');
    } else {
        $('#mobile-tab-cart').addClass('active btn-success text-white').removeClass('btn-light text-success');
        $('.cart-panel').css('display', 'flex').removeClass('d-none');
        $('.products-panel').css('display', 'none').addClass('d-none');
    }
}

// Admin Override Authorization handler
function requestAdminOverride(reason, productId = null) {
    return new Promise((resolve, reject) => {
        overrideResolve = resolve;
        overrideReject = reject;
        overrideStockProductId = productId;

        $('#override-reason-label').text(reason);
        $('#override-email').val('');
        $('#override-password').val('');

        let modal = new bootstrap.Modal(document.getElementById('overrideModal'));
        modal.show();
    });
}

function submitOverride() {
    let email = $('#override-email').val().trim();
    let password = $('#override-password').val().trim();
    let reason = $('#override-reason-label').text();

    if (!email || !password) {
        toastr.error('ইমেইল ও পাসওয়ার্ড দিন।');
        return;
    }

    $.ajax({
        url: window.POS_CONFIG.verifyOverrideUrl,
        method: "POST",
        data: {
            email: email,
            password: password,
            reason: reason
        },
        success: function(response) {
            if (response.success) {
                $('#overrideModal').modal('hide');
                if (overrideResolve) overrideResolve(response.authorized_by);
            }
        },
        error: function(xhr) {
            let msg = xhr.responseJSON ? xhr.responseJSON.message : 'অনুমোদন ব্যর্থ হয়েছে।';
            toastr.error(msg);
        }
    });
}

function cancelOverride() {
    $('#overrideModal').modal('hide');
    if (overrideReject) overrideReject();
}

// Customer Selection Auto-complete
$('#customer-search').on('input', function() {
    let query = $(this).val().trim();
    let resultsDiv = $('#customer-search-results');

    if (query.length < 2) {
        resultsDiv.addClass('d-none');
        return;
    }

    $.ajax({
        url: window.POS_CONFIG.searchCustomersUrl,
        method: "GET",
        data: { q: query },
        success: function(response) {
            if (response.success && response.customers.length > 0) {
                resultsDiv.empty().removeClass('d-none');
                response.customers.forEach(customer => {
                    let itemHtml = `
                        <button type="button" class="list-group-item list-group-item-action py-2 px-3 small" onclick="selectCustomer(${JSON.stringify(customer).replace(/"/g, '&quot;')})">
                            <div class="fw-bold">${customer.name}</div>
                            <div class="text-secondary" style="font-size: 11px;">মোবাইল: ${customer.phone} ${customer.email ? ' | ইমেইল: ' + customer.email : ''}</div>
                        </button>
                    `;
                    resultsDiv.append(itemHtml);
                });
            } else {
                resultsDiv.html('<div class="list-group-item text-secondary py-2 text-center small">কোনো কাস্টমার পাওয়া যায়নি।</div>').removeClass('d-none');
            }
        }
    });
});

function selectCustomer(customer) {
    selectedCustomer = {
        id: customer.id,
        name: customer.name,
        phone: customer.phone
    };
    $('#customer-search').val(customer.name).attr('data-id', customer.id);
    $('#customer-search-results').addClass('d-none');
    toastr.success(`কাস্টমার '${customer.name}' সিলেক্ট করা হয়েছে।`);
}

function resetCustomerToGuest() {
    selectedCustomer = {
        id: window.POS_CONFIG.guestCustomer.id,
        name: window.POS_CONFIG.guestCustomer.name,
        phone: window.POS_CONFIG.guestCustomer.phone
    };
    $('#customer-search').val(selectedCustomer.name).attr('data-id', selectedCustomer.id);
    $('#customer-search-results').addClass('d-none');
}

// Quick Add Customer Submission
function openAddCustomerModal() {
    $('#new-cust-name').val('');
    $('#new-cust-phone').val('');
    $('#new-cust-email').val('');
    $('#new-cust-address').val('');
    new bootstrap.Modal(document.getElementById('addCustomerModal')).show();
}

function submitAddCustomer(e) {
    e.preventDefault();
    let name = $('#new-cust-name').val().trim();
    let phone = $('#new-cust-phone').val().trim();
    let email = $('#new-cust-email').val().trim();
    let address = $('#new-cust-address').val().trim();

    $.ajax({
        url: window.POS_CONFIG.createCustomerUrl,
        method: "POST",
        data: {
            name: name,
            phone: phone,
            email: email,
            present_address: address
        },
        success: function(response) {
            if (response.success) {
                $('#addCustomerModal').modal('hide');
                selectCustomer(response.customer);
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON ? xhr.responseJSON.errors : {};
            let firstErr = Object.values(errors)[0] ? Object.values(errors)[0][0] : 'নিবন্ধন ব্যর্থ হয়েছে।';
            toastr.error(firstErr);
        }
    });
}

// Submit Checkout via AJAX
function submitCheckout() {
    if (cart.length === 0) {
        toastr.error('কার্ট খালি রয়েছে। প্রোডাক্ট যোগ করুন।');
        return;
    }

    // Validate shift exists
    if (!window.POS_CONFIG.activeShift) {
        toastr.error('অর্ডার প্লেস করার আগে শিফট শুরু করা আবশ্যক।');
        return;
    }

    let checkoutData = {
        customer_id: selectedCustomer.id,
        customer_name: selectedCustomer.name,
        customer_phone: selectedCustomer.phone,
        items: cart.map(i => {
            return {
                product_id: i.product_id,
                quantity: i.quantity,
                price: i.price,
                discount_price: i.discount_price
            };
        }),
        payment_method: paymentMethod,
        discount: discountVal,
        discount_type: discountType,
        discount_authorized_by: authorizedDiscountUserId,
        amount_received: paymentMethod === 'cash' ? (parseFloat($('#cash-amount-received').val()) || 0) : totalPayable,
        notes: ''
    };

    if (cart.some(item => item.stock_override)) {
        checkoutData.stock_override = true;
    }

    if (paymentMethod === 'split') {
        let totalPaid = splitPayments.reduce((sum, item) => sum + item.amount, 0);
        if (Math.abs(totalPaid - totalPayable) > 0.01) {
            toastr.error('স্প্লিট পেমেন্ট ব্যালেন্স মিলিয়ে নিন। সর্বমোট পরিশোধযোগ্য টাকার সমান হতে হবে।');
            return;
        }
        checkoutData.payments = splitPayments;
        checkoutData.amount_received = totalPaid;
    } else if (paymentMethod !== 'cash') {
        checkoutData.transaction_id = $('#digital-trx-id').val().trim();
    }

    Swal.fire({
        title: 'অর্ডার প্লেস করতে চান?',
        text: "এটি ইনভয়েস প্রিভিউ দেখাবে এবং প্রিন্ট করতে সাহায্য করবে!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'হ্যাঁ, কনফার্ম করুন',
        cancelButtonText: 'বাতিল'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'অর্ডার প্রসেস হচ্ছে...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: window.POS_CONFIG.checkoutUrl,
                method: "POST",
                data: checkoutData,
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        toastr.success(response.message);

                        clearCart();
                        resetCustomerToGuest();
                        authorizedDiscountUserId = null;
                        $('#cash-amount-received').val('');
                        $('#digital-trx-id').val('');

                        // Show receipt print preview instead of opening a popup
                        if (response.receipt_url) {
                            showReceiptPreview(response.receipt_url);
                        }

                        // Reload products to sync stocks
                        fetchProducts();
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'অর্ডার প্রসেস করতে ব্যর্থ হয়েছে।';
                    toastr.error(msg);
                }
            });
        }
    });
}

// Print Preview Modal Handlers
function showReceiptPreview(receiptUrl) {
    let iframe = document.getElementById('receipt-iframe');
    let url = receiptUrl;
    if (url.indexOf('?') !== -1) {
        url += '&iframe=1';
    } else {
        url += '?iframe=1';
    }
    iframe.src = url;
    
    let modal = new bootstrap.Modal(document.getElementById('printPreviewModal'));
    modal.show();
}

function printReceiptFromIframe() {
    let iframe = document.getElementById('receipt-iframe');
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
}

// Shift Control AJAX
function submitOpenShift(e) {
    e.preventDefault();
    let amt = $('#opening_balance').val();

    $.ajax({
        url: window.POS_CONFIG.openShiftUrl,
        method: "POST",
        data: { opening_balance: amt },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                window.location.reload();
            }
        },
        error: function(xhr) {
            toastr.error('শিফট ওপেন করতে সমস্যা হয়েছে।');
        }
    });
}

function openCloseShiftModal() {
    $.ajax({
        url: window.POS_CONFIG.shiftStatusUrl,
        method: "GET",
        success: function(response) {
            if (response.success) {
                $('#close-shift-total-sales').text('৳' + parseFloat(response.summary.total_sales).toFixed(2));
                $('#close-shift-total-discount').text('৳' + parseFloat(response.summary.total_discount).toFixed(2));
                $('#close-shift-total-orders').text(response.summary.total_orders + ' টি');
                $('#closing-balance-input').val('');
                $('#closing-notes').val('');
                new bootstrap.Modal(document.getElementById('closeShiftModal')).show();
            }
        }
    });
}

function submitCloseShift() {
    let amt = $('#closing-balance-input').val();
    let notes = $('#closing-notes').val().trim();

    if (!amt) {
        toastr.error('সমাপনী ব্যালেন্স দিন।');
        return;
    }

    $.ajax({
        url: window.POS_CONFIG.closeShiftUrl,
        method: "POST",
        data: {
            closing_balance: amt,
            notes: notes
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                window.location.reload();
            }
        },
        error: function() {
            toastr.error('শিফট বন্ধ করতে সমস্যা হয়েছে।');
        }
    });
}

// Barcode Sticker Generation Handlers
function openBarcodeModal() {
    $('#generated-barcode-preview-container').addClass('d-none');
    $('#btn-print-barcode-label').prop('disabled', true);
    new bootstrap.Modal(document.getElementById('barcodeModal')).show();
}

function generateProductBarcode() {
    let productId = $('#barcode-product-select').val();
    if (!productId) {
        toastr.warning('দয়া করে একটি প্রোডাক্ট সিলেক্ট করুন।');
        return;
    }

    let btn = $('#btn-generate-barcode-submit');
    btn.prop('disabled', true).text('তৈরি হচ্ছে...');

    $.ajax({
        url: window.POS_CONFIG.generateBarcodeUrl,
        method: 'POST',
        data: {
            product_id: productId
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                let product = products.find(p => p.id == productId);
                if (product) {
                    product.barcode = response.barcode;
                }
                $('#generated-barcode-preview-container').removeClass('d-none');
                JsBarcode("#generated-barcode-svg", response.barcode, {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 50,
                    displayValue: true
                });
                $('#btn-print-barcode-label').prop('disabled', false);
                renderProducts();
            }
        },
        error: function(xhr) {
            let msg = 'বারকোড তৈরি করতে ব্যর্থ হয়েছে।';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            toastr.error(msg);
        },
        complete: function() {
            btn.prop('disabled', false).text('বারকোড তৈরি করুন');
        }
    });
}

function printBarcodeLabel() {
    let productId = $('#barcode-product-select').val();
    let product = products.find(p => p.id == productId);
    if (!product || !product.barcode) return;

    let labelWindow = window.open('', '_blank', 'width=300,height=200');
    let svgHtml = document.getElementById('generated-barcode-preview-container').innerHTML;
    
    labelWindow.document.write(`
        <html>
        <head>
            <title>Barcode Label - ${product.name}</title>
            <style>
                body {
                    margin: 0;
                    padding: 10px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    font-family: Arial, sans-serif;
                    text-align: center;
                }
                .title {
                    font-size: 11px;
                    font-weight: bold;
                    margin-bottom: 2px;
                    max-width: 180px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .price {
                    font-size: 12px;
                    font-weight: bold;
                    margin-top: 2px;
                }
                svg {
                    max-width: 100%;
                }
                @media print {
                    @page { margin: 0; }
                    body { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="title">${product.name}</div>
            ${svgHtml}
            <div class="price">৳${product.final_price.toFixed(2)}</div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                }
            <\/script>
        </body>
        </html>
    `);
    labelWindow.document.close();
}

// Keyboard Shortcuts
function setupKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        if (e.key === 'F1') {
            e.preventDefault();
            $('#product-search').focus().select();
        }

        if (e.key === 'F2') {
            e.preventDefault();
            $('#customer-search').focus().select();
        }

        if (e.key === 'F4') {
            e.preventDefault();
            let methods = ['cash', 'bkash', 'nagad', 'card', 'split'];
            let currentIdx = methods.indexOf(paymentMethod);
            let nextIdx = (currentIdx + 1) % methods.length;
            selectPaymentMethod(methods[nextIdx]);
            toastr.info(`পেমেন্ট মেথড: ${methods[nextIdx].toUpperCase()}`);
        }

        if (e.key === 'F8') {
            e.preventDefault();
            $('#discount-input').focus().select();
        }

        if (e.key === 'Escape') {
            $('.modal').modal('hide');
            clearProductSearch();
        }
    });
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().then(() => {
            $('#fullscreen-icon').removeClass('bi-fullscreen').addClass('bi-fullscreen-exit');
        }).catch(err => {
            toastr.error('ফুলস্ক্রিন মোড সচল করা যায়নি।');
        });
    } else {
        document.exitFullscreen();
        $('#fullscreen-icon').removeClass('bi-fullscreen-exit').addClass('bi-fullscreen');
    }
}

// Fullscreen state change synchronization
document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement) {
        $('#fullscreen-icon').removeClass('bi-fullscreen-exit').addClass('bi-fullscreen');
    } else {
        $('#fullscreen-icon').removeClass('bi-fullscreen').addClass('bi-fullscreen-exit');
    }
});
