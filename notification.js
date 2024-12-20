document.addEventListener("DOMContentLoaded", () => {
  const orderNotificationsContainer = document.getElementById("orderNotifications");

  // Fetch all orders from localStorage
  const allOrders = JSON.parse(localStorage.getItem("allOrderDetails")) || [];

  if (allOrders.length > 0) {
    let notificationsHTML = "";

    // Loop through each order and display its details
    allOrders.forEach(order => {
      const { cart, customer, orderId, orderDateTime } = order;

      notificationsHTML += `
        <div class="order-notification">
          <h3>Order ID: ${orderId}</h3>
          <p><strong>Date & Time:</strong> ${orderDateTime}</p>
          <p><strong>Customer Name:</strong> ${customer.name}</p>
          <p><strong>Payment Mode:</strong> ${customer.paymentMode}</p>
          <h4>Products:</h4>
      `;

      cart.forEach(item => {
        notificationsHTML += `
          <p><strong>Product Name:</strong> ${item.name} (x${item.quantity})</p>
        `;
      });

      notificationsHTML += `
          <hr>
        </div>
      `;
    });

    orderNotificationsContainer.innerHTML = notificationsHTML;
  } else {
    orderNotificationsContainer.innerHTML = "<p>No order notifications available.</p>";
  }
});
