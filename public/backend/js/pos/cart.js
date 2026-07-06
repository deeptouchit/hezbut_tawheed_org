/**
 * Bogra Bazar POS - Cart Management Module
 */

// Cart Actions
function addToCart(product, qty = 1, isStockOverride = false) {
    let existing = cart.find(item => item.product_id === product.id);

    if (existing) {
        // If not stock override, check stock
        if (!isStockOverride && !existing.stock_override && (existing.quantity + qty) > product.stock) {
            toastr.error('পর্যাপ্ত স্টক নেই।');
            return;
        }
        existing.quantity += qty;
        existing.subtotal = existing.quantity * existing.price;
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            price: product.final_price,
            quantity: qty,
            subtotal: product.final_price * qty,
            stock: product.stock,
            stock_override: isStockOverride
        });
    }

    saveCart();
    renderCart();
    toastr.success('কার্টে যুক্ত করা হয়েছে।');
    autofocusSearch();
}

function updateQuantity(productId, newQty) {
    let item = cart.find(i => i.product_id === productId);
    if (!item) return;

    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty <= 0) {
        removeFromCart(productId);
        return;
    }

    // Check stock limits
    if (!item.stock_override && newQty > item.stock) {
        toastr.error('দুঃখিত, পর্যাপ্ত স্টক নেই।');
        renderCart();
        return;
    }

    item.quantity = newQty;
    item.subtotal = item.quantity * item.price;
    saveCart();
    renderCart();
}

function removeFromCart(productId) {
    cart = cart.filter(i => i.product_id !== productId);
    saveCart();
    renderCart();
    toastr.info('কার্ট থেকে বাদ দেওয়া হয়েছে।');
}

function clearCart() {
    cart = [];
    discountVal = 0;
    $('#discount-input').val(0);
    localStorage.removeItem('pos_cart');
    renderCart();
}

function saveCart() {
    localStorage.setItem('pos_cart', JSON.stringify(cart));
}

function restoreCart() {
    let stored = localStorage.getItem('pos_cart');
    if (stored) {
        try {
            cart = JSON.parse(stored);
            renderCart();
        } catch(e) {
            cart = [];
        }
    }
}

// Render Cart UI
function renderCart() {
    let tbody = $('#cart-tbody');
    tbody.empty();

    // Update mobile cart badge count
    let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    $('#mobile-cart-count').text(totalItems);

    if (cart.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="4" class="text-center text-secondary py-4">
                    <i class="bi bi-cart-x fs-3 d-block mb-1"></i>
                    কার্ট সম্পূর্ণ খালি রয়েছে
                </td>
            </tr>
        `);
        calculateTotals();
        return;
    }

    cart.forEach(function(item) {
        let overrideLabel = item.stock_override ? '<span class="badge bg-danger-subtle text-danger p-1" style="font-size: 8px;">ওভাররাইড</span>' : '';
        let row = `
            <tr>
                <td class="align-middle">
                    <div class="fw-semibold text-wrap lh-sm" style="max-width: 180px; font-size: 11px;">${item.name} ${overrideLabel}</div>
                    <div class="text-secondary" style="font-size: 10px;">৳${item.price.toFixed(2)}</div>
                </td>
                <td class="text-center align-middle">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <button class="btn btn-xs btn-outline-secondary qty-btn p-0" onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})"><i class="bi bi-dash"></i></button>
                        <input type="number" class="form-control form-control-sm qty-input fw-bold text-center p-0 m-0" value="${item.quantity}" min="1" onchange="updateQuantity(${item.product_id}, this.value)">
                        <button class="btn btn-xs btn-outline-secondary qty-btn p-0" onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})"><i class="bi bi-plus"></i></button>
                    </div>
                </td>
                <td class="text-end fw-bold align-middle text-dark-emphasis" style="font-size: 11.5px;">
                    ৳${item.subtotal.toFixed(2)}
                </td>
                <td class="text-center align-middle">
                    <button class="btn text-danger btn-sm p-1" onclick="removeFromCart(${item.product_id})" title="বাদ দিন"><i class="bi bi-trash3-fill" style="font-size: 11px;"></i></button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });

    calculateTotals();
}

// Calculate and Render Totals
function calculateTotals() {
    let subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    discountVal = parseFloat($('#discount-input').val()) || 0;
    discountType = $('#discount-type').val();

    let discountAmount = 0;
    if (discountType === 'percent') {
        discountAmount = (subtotal * discountVal) / 100;
    } else {
        discountAmount = discountVal;
    }

    // Ensure discount is not more than subtotal
    discountAmount = Math.min(discountAmount, subtotal);

    // Calculate Tax (0% standard VAT or adjustable)
    let taxableAmount = subtotal - discountAmount;
    let tax = taxableAmount * 0.00;
    totalPayable = taxableAmount + tax;

    $('#summary-subtotal').text('৳' + subtotal.toFixed(2));
    $('#summary-tax').text('৳' + tax.toFixed(2));
    $('#summary-total').text('৳' + totalPayable.toFixed(2));

    // Also update payment amounts
    calculateChange();
    if (paymentMethod === 'split') {
        updateSplitBalance();
    }
}

// Apply discount (Admin approval needed if discount is greater than 0)
let authorizedDiscountUserId = null;
function applyDiscount() {
    discountVal = parseFloat($('#discount-input').val()) || 0;
    if (discountVal > 0 && !authorizedDiscountUserId) {
        // Requires authorization
        requestAdminOverride("ডিসকাউন্ট অনুমোদন", null)
        .then((authId) => {
            authorizedDiscountUserId = authId;
            toastr.success('ডিসকাউন্ট অনুমোদন সফল হয়েছে।');
            calculateTotals();
        })
        .catch(() => {
            // Reset discount
            $('#discount-input').val(0);
            authorizedDiscountUserId = null;
            calculateTotals();
            toastr.warning('ডিসকাউন্ট অনুমোদন বাতিল করা হয়েছে।');
        });
    } else {
        calculateTotals();
    }
}

// Payment Method Selection
function selectPaymentMethod(method) {
    paymentMethod = method;
    $('.pay-method-btn').removeClass('active');
    $(`button[data-method="${method}"]`).addClass('active');

    // Hide/Show inputs
    $('#cash-payment-details').addClass('d-none');
    $('#digital-payment-details').addClass('d-none');
    $('#split-payment-details').addClass('d-none');

    if (method === 'cash') {
        $('#cash-payment-details').removeClass('d-none');
        $('#cash-amount-received').val(Math.ceil(totalPayable)).focus();
        calculateChange();
    } else if (method === 'split') {
        $('#split-payment-details').removeClass('d-none');
        initSplitPayments();
    } else {
        $('#digital-payment-details').removeClass('d-none');
        $('#digital-trx-id').focus();
    }
}

// Cash Change calculation
function calculateChange() {
    let received = parseFloat($('#cash-amount-received').val()) || 0;
    let change = Math.max(0, received - totalPayable);
    $('#cash-change-amount').text('৳' + change.toFixed(2));
}

// Split Payment Functions
function initSplitPayments() {
    splitPayments = [
        { method: 'cash', amount: Math.ceil(totalPayable / 2), transaction_id: '' },
        { method: 'bkash', amount: Math.floor(totalPayable / 2), transaction_id: '' }
    ];
    renderSplitRows();
}

function addSplitRow() {
    splitPayments.push({ method: 'cash', amount: 0, transaction_id: '' });
    renderSplitRows();
}

function removeSplitRow(index) {
    splitPayments.splice(index, 1);
    renderSplitRows();
}

function renderSplitRows() {
    let container = $('#split-rows-container');
    container.empty();

    splitPayments.forEach((pm, index) => {
        let row = `
            <div class="row g-1 align-items-center mb-1">
                <div class="col-4">
                    <select class="form-select form-select-sm" onchange="updateSplitRow(${index}, 'method', this.value)">
                        <option value="cash" ${pm.method === 'cash' ? 'selected' : ''}>ক্যাশ (Cash)</option>
                        <option value="bkash" ${pm.method === 'bkash' ? 'selected' : ''}>বিকাশ (bKash)</option>
                        <option value="nagad" ${pm.method === 'nagad' ? 'selected' : ''}>নগদ (Nagad)</option>
                        <option value="card" ${pm.method === 'card' ? 'selected' : ''}>কার্ড (Card)</option>
                    </select>
                </div>
                <div class="col-3">
                    <input type="number" class="form-control form-control-sm text-end fw-semibold" placeholder="Amount" value="${pm.amount}" oninput="updateSplitRow(${index}, 'amount', this.value)">
                </div>
                <div class="col-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Txn ID (Optional)" value="${pm.transaction_id}" oninput="updateSplitRow(${index}, 'transaction_id', this.value)">
                </div>
                <div class="col-1 text-center">
                    <button class="btn text-danger btn-xs p-0" onclick="removeSplitRow(${index})"><i class="bi bi-dash-circle-fill fs-5"></i></button>
                </div>
            </div>
        `;
        container.append(row);
    });

    updateSplitBalance();
}

function updateSplitRow(index, field, value) {
    if (field === 'amount') {
        splitPayments[index][field] = parseFloat(value) || 0;
    } else {
        splitPayments[index][field] = value;
    }
    updateSplitBalance();
}

function updateSplitBalance() {
    let totalPaid = splitPayments.reduce((sum, item) => sum + item.amount, 0);
    let balance = totalPayable - totalPaid;

    if (balance > 0) {
        $('#split-balance-warning').text(`বাকি: ৳${balance.toFixed(2)}`).removeClass('text-success').addClass('text-danger');
    } else if (balance < 0) {
        $('#split-balance-warning').text(`অতিরিক্ত: ৳${Math.abs(balance).toFixed(2)}`).removeClass('text-danger').addClass('text-success');
    } else {
        $('#split-balance-warning').text('পরিশোধিত (৳০.০০)').removeClass('text-danger').addClass('text-success');
    }
}

function confirmCancelOrder() {
    if (cart.length === 0) {
        toastr.info('কার্ট ইতিমধ্যে খালি রয়েছে।');
        return;
    }
    Swal.fire({
        title: 'অর্ডার বাতিল করতে চান?',
        text: "এটি কার্টের সমস্ত প্রোডাক্ট মুছে ফেলবে!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'হ্যাঁ, বাতিল করুন',
        cancelButtonText: 'না, রাখুন'
    }).then((result) => {
        if (result.isConfirmed) {
            clearCart();
            toastr.success('অর্ডারটি বাতিল করা হয়েছে।');
        }
    });
}
