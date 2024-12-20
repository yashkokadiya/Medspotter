// Ensure this runs after order details are generated and displayed
if (orderDetails) {
  const { customer, cart, total } = orderDetails;

  const deliveryCharge = total * 0.1;
  const finalTotal = total + deliveryCharge;

  // Generate random Order ID
  const orderId = `MS-${Math.floor(Math.random() * 1000000).toString().padStart(6, '0')}`;

  // Get current date and time
  const now = new Date();
  const orderDateTime = now.toLocaleString();

  // Build cart items HTML
  let cartItemsHTML = '';
  cart.forEach((item) => {
    cartItemsHTML += `
      <div class="order-item">
        <img src="${item.image}" alt="${item.name}" style="width: 50px; height: auto;">
        <p>${item.name} x ${item.quantity} - $${(item.price * item.quantity).toFixed(2)}</p>
      </div>
    `;
  });

  // Populate the confirmation details
  orderDetailsContainer.innerHTML = `
    <h3>Order Confirmation</h3>
    <p><strong>Order ID:</strong> ${orderId}</p>
    <p><strong>Order Date & Time:</strong> ${orderDateTime}</p>
    <p><strong>Order Platform:</strong> Medspotter</p>

    <h3>Customer Information:</h3>
    <p><strong>Name:</strong> ${customer.name}</p>
    <p><strong>Phone:</strong> ${customer.phone}</p>
    <p><strong>Address:</strong> ${customer.address}</p>
    <p><strong>Store Code:</strong> ${customer.storeCode}</p>
    <p><strong>Payment Mode:</strong> ${customer.paymentMode}</p>

    <h3>Ordered Products:</h3>
    ${cartItemsHTML}

    <h3>Summary:</h3>
    <p><strong>Grand Total:</strong> $${total.toFixed(2)}</p>
    <p><strong>Delivery Charges:</strong> $${deliveryCharge.toFixed(2)}</p>
    <p><strong>Final Total:</strong> $${finalTotal.toFixed(2)}</p>
    <p><strong>Expected Delivery:</strong> 4-5 hours</p>
  `;

  // Save the order details to localStorage
  const orderDetailsToSave = {
    customer: {
      name: customer.name,
      phone: customer.phone,
      address: customer.address,
      paymentMode: customer.paymentMode,
      storeCode: customer.storeCode,
    },
    cart: [...cart],
    orderId,
  };

  localStorage.setItem("orderDetails", JSON.stringify(orderDetailsToSave));

  // Clear cart after confirmation
  localStorage.removeItem('cart');
} else {
  orderDetailsContainer.innerHTML = '<p>No order details found.</p>';
}
